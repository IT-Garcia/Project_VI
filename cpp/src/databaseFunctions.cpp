// Includes required (headers located in /usr/include) 
#include "../include/databaseFunctions.h"
#include <stdlib.h>
#include <iostream>
#include <ctime>
#include <iomanip>
#include <sstream>
#include <mysql_connection.h>
#include <mysql_driver.h>
#include <cppconn/driver.h>
#include <cppconn/exception.h>
#include <cppconn/resultset.h>
#include <cppconn/statement.h>
#include <cppconn/prepared_statement.h>
 
using namespace std; 
 
int db_getFloorNum() {
	sql::Driver *driver; 			// Create a pointer to a MySQL driver object
	sql::Connection *con; 			// Create a pointer to a database connection object
	sql::Statement *stmt;			// Crealte a pointer to a Statement object to hold statements 
	sql::ResultSet *res;			// Create a pointer to a ResultSet object to hold results 
	int floorNum;					// Floor number 
	
	// Create a connection 
	driver = get_driver_instance();
	con = driver->connect("tcp://127.0.0.1:3306", "ese", "ese");	
	con->setSchema("elevator");		
	
	// Query database
	// ***************************** 
	stmt = con->createStatement();
	res = stmt->executeQuery("SELECT requestedFloor FROM elevatorNetwork ORDER BY nodeID DESC LIMIT 1");	// get most recent floor request
	while(res->next()){
		floorNum = res->getInt("requestedFloor");
	}
	
	// Clean up pointers 
	delete res;
	delete stmt;
	delete con;
	
	return floorNum;
}
 
std::string getCurrentTime() {
    std::time_t now = std::time(nullptr);
    std::tm *localtm = std::localtime(&now);
    std::stringstream ss;
    ss << std::put_time(localtm, "%H:%M:%S");  // Store in 24-hour format
    return ss.str();
}

std::string getCurrentDate() {
    std::time_t now = std::time(nullptr);
    std::tm *localtm = std::localtime(&now);
    std::stringstream ss;
    ss << std::put_time(localtm, "%Y-%m-%d");  // Store in standard date format
    return ss.str();
}

//Inserts a new floor request from the CAN bus into the database
int insReqDB(int curFlr, int floorRequest) {
	sql::Driver *driver; 				// Create a pointer to a MySQL driver object
	sql::Connection *con; 				// Create a pointer to a database connection object
	sql::PreparedStatement *pstmt; 		// Create a pointer to a prepared statement	
    std::string curtime = getCurrentTime();
    std::string curdate = getCurrentDate();

    try {	
		// Create a connection 
		driver = get_driver_instance();
		con = driver->connect("tcp://127.0.0.1:3306", "ese", "ese");	
		con->setSchema("elevator");										
		
		// Update database
		// *****************************
        pstmt = con->prepareStatement("INSERT INTO elevatorNetwork (status, currentFloor, requestedFloor, otherInfo, date, time) VALUES (?, ?, ?, ?, ?, ?)");
        pstmt->setInt(1, 2); // Status
        pstmt->setInt(2, curFlr); // Most Recent Current floor
        pstmt->setInt(3, floorRequest); // Requested floor 
        pstmt->setString(4, "Processing Local Command: Floor " + std::to_string(floorRequest) + " Requested"); // Other info
        pstmt->setString(5, curdate); // Current date
        pstmt->setString(6, curtime); // Current time
        pstmt->executeUpdate();
		
        // Clean up pointers
        delete pstmt;
        delete con;
    } 
	catch (sql::SQLException &e) 
	{
        std::cerr << "SQL error: " << e.what() << std::endl;
        if (con) delete con;
        return -1; // Return an error code in case of failure
    }
	return 0; // Return 0 for successful execution
}  

//Inserts the current floor into the database to match the requested floor once the requested floor has been reached
int insFlrDB(int floorNum) {
    sql::Driver *driver; // Create a pointer to a MySQL driver object
    sql::Connection *con; // Create a pointer to a database connection object
    sql::PreparedStatement *pstmt; // Create a pointer to a prepared statement
    std::string curtime = getCurrentTime();
    std::string curdate = getCurrentDate();

    try {
        // Create a connection
        driver = get_driver_instance();
        con = driver->connect("tcp://127.0.0.1:3306", "ese", "ese");
        con->setSchema("elevator");

        // Prepared statement with additional date and time fields
        pstmt = con->prepareStatement("INSERT INTO elevatorNetwork (status, currentFloor, requestedFloor, otherInfo, date, time) VALUES (?, ?, ?, ?, ?, ?)");
        pstmt->setInt(1, 1); // Status
        pstmt->setInt(2, floorNum); // Current floor
        pstmt->setInt(3, floorNum); // Requested floor 
        pstmt->setString(4, "System Okay: Floor " + std::to_string(floorNum) + " Request Successful"); // Other info
        pstmt->setString(5, curdate); // Current date
        pstmt->setString(6, curtime); // Current time
        pstmt->executeUpdate();

        // Clean up pointers
        delete pstmt;
        delete con;
    } 
	catch (sql::SQLException &e) 
	{
        std::cerr << "SQL error: " << e.what() << std::endl;
        if (con) delete con;
        return -1; // Return an error code in case of failure
    }
	return 0; // Return 0 for successful execution
}
