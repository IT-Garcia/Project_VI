#include "../include/pcanFunctions.h"
#include "../include/databaseFunctions.h"
#include "../include/mainFunctions.h"
#include "../include/audioplayer.h"

#include <stdio.h>
#include <stdlib.h>
#include <unistd.h> 
#include <iostream>

using namespace std;


// ******************************************************************

int main() {

	int choice; 
	int ID; 
	int data; 
	int numRx, flrHx;
	int floorRequest = 1, curFlr = 1;

	while(1) {
		system("@cls||clear");
		choice = menu(); 
		switch (choice) {
			case 1: 
				ID = chooseID();		// user to select ID depending on intended recipient
				data = chooseMsg();		// user to select message data
				pcanTx(ID, data);		// transmit ID and data 
				insReqDB(FloorFromHex(data)); 		// change floor number in database ** NEW **
				sleep(2);				
				insFlrDB(FloorFromHex(data)); 		// change floor number in database ** NEW **
				break; 
				
			case 2:
				printf("\nHow many messages to receive? ");
				scanf("%d", &numRx);
				pcanRx(numRx);
				break;
				
			case 3:
				printf("\nNow listening to commands from the website - press ctrl-z to cancel\n");
				// Synchronize elevator db and CAN (start at 1st floor)
				pcanTx(ID_SC_TO_EC, GO_TO_FLOOR1);
				insFlrDB(1);
				numRx = GO_TO_FLOOR1;
				flrHx = numRx;

				while(1)
				{
					//CAN Network Operations
					numRx = sCAN();											// Scan the CAN network 
					if(flrHx != numRx && numRx != -1)						// If the value on the CAN network has changed without errors update the database
					{
						floorRequest = FloorFromHex(numRx);					// Ensure correct floor value is inserted into the database
						insReqDB(curFlr, floorRequest);						// Insert the newly requested floor into the database
						flrHx = numRx;										// Update the global Hex floor variable
					}
					//if sCAN() returns -1 report error on dB "ERROR: Unable to read CAN network"
					//if insReqDB returns -1 report error on dB "ERROR: Inserting Local Request Failed"

					//Web Based Operations
					floorRequest = db_getFloorNum();						// Check for floor requests on the database
					if (curFlr != floorRequest) 							// If requested floor does not match the current floor
					{							
						pcanTx(ID_SC_TO_EC, HexFromFloor(floorRequest));	// Change floors in the elevator - send command over CAN
                        sleep(2);                                           // wait 2 seconds
                        insFlrDB(floorRequest);                       		// update current floor number in db to requested floor number after elevator has moved
						curFlr = floorRequest; 								// Update the global current floor variable
					}
					//add error handling for db_getFloorNum() ??
					//if insFlrDB returns -1 report error on dB "ERROR: Inserting Current Floor Failed"
					//sleep(1);												// poll database once every second to check for change in floor number
				}
				break;
				
			case 4: 
				return(0);
			
			default:
				printf("Error on input values");
				sleep(3);
				break;
		}
		sleep(1);					// delay between send/receive
	}
	
	return(0);
}
