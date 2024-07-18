#include "../include/pcanFunctions.h"

#include <stdio.h>
#include <stdlib.h>
#include <stdlib.h>  
#include <errno.h>
#include <unistd.h> 
#include <signal.h>
#include <string.h>
#include <fcntl.h>    					// O_RDWR
#include <unistd.h>
#include <ctype.h>
#include <libpcan.h>   					// PCAN library


// Globals
// ***********************************************************************************************************
HANDLE h;
HANDLE h2;
TPCANMsg Txmsg;
TPCANMsg Rxmsg;
DWORD status;

// Code
// ***********************************************************************************************************

// Functions
// *****************************************************************
int pcanTx(int id, int data){
	h = LINUX_CAN_Open("/dev/pcanusb32", O_RDWR);		// Open PCAN channel

	// Initialize an opened CAN 2.0 channel with a 125kbps bitrate, accepting standard frames
	status = CAN_Init(h, CAN_BAUD_125K, CAN_INIT_TYPE_ST);

	// Clear the channel - new - Must clear the channel before Tx/Rx
	status = CAN_Status(h);

	// Set up message
	Txmsg.ID = id; 	
	Txmsg.MSGTYPE = MSGTYPE_STANDARD; 
	Txmsg.LEN = 1; 
	Txmsg.DATA[0] = data; 

	sleep(1);  
	status = CAN_Write(h, &Txmsg);

	// Close CAN 2.0 channel and exit	
	CAN_Close(h);
}

int pcanRx(int num_msgs){
	int i = 0;

	// Open a CAN channel 
	h2 = LINUX_CAN_Open("/dev/pcanusb32", O_RDWR);

	// Initialize an opened CAN 2.0 channel with a 125kbps bitrate, accepting standard frames
	status = CAN_Init(h2, CAN_BAUD_125K, CAN_INIT_TYPE_ST);

	// Clear the channel - new - Must clear the channel before Tx/Rx
	status = CAN_Status(h2);

	// Clear screen to show received messages
	system("@cls||clear");

	// receive CAN message  - CODE adapted from PCAN BASIC C++ examples pcanread.cpp
	printf("\nReady to receive message(s) over CAN bus\n");
	
	// Read 'num' messages on the CAN bus
	while(i < num_msgs) {
		while((status = CAN_Read(h2, &Rxmsg)) == PCAN_RECEIVE_QUEUE_EMPTY){
			sleep(1);
		}
		if(status != PCAN_NO_ERROR) {						// If there is an error, display the code
			printf("Error 0x%x\n", (int)status);
			//break;
		}
										
		if(Rxmsg.ID != 0x01 && Rxmsg.LEN != 0x04) {		// Ignore status message on bus	
			printf("  - R ID:%4x LEN:%1x DATA:%02x \n",	// Display the CAN message
				(int)Rxmsg.ID, 
				(int)Rxmsg.LEN,
				(int)Rxmsg.DATA[0]);
		i++;
		}
	}
	

	// Close CAN 2.0 channel and exit	
	CAN_Close(h2);
	//printf("\nEnd Rx\n");
	return ((int)Rxmsg.DATA[0]);						// Return the last value received
}

//This function performs a basic scan of the CAN network in 1 second intervals to retrieve and return the most recent CAN value on the network
int sCAN() {
    HANDLE h2;
    TPCANMsg Rxmsg;
    int status;

    // Open a CAN channel
    h2 = LINUX_CAN_Open("/dev/pcanusb32", O_RDWR);
    if (h2 == NULL) {
        // Error opening CAN channel
        printf("Error opening CAN channel\n");
        return -1;
    }

    // Initialize an opened CAN 2.0 channel with a 125kbps bitrate, accepting standard frames
    status = CAN_Init(h2, CAN_BAUD_125K, CAN_INIT_TYPE_ST);
    if (status != PCAN_NO_ERROR) {
        // Error initializing CAN channel
        printf("Error initializing CAN channel\n");
        CAN_Close(h2);
        return -1;
    }

    // Clear the channel - Must clear the channel before Tx/Rx
    status = CAN_Status(h2);
    if (status != PCAN_NO_ERROR) {
        // Error clearing CAN channel
        printf("Error clearing CAN channel\n");
        CAN_Close(h2);
        return -1;
    }

    // Read messages on the CAN bus until a valid one is received
    while (true) {
        status = CAN_Read(h2, &Rxmsg);
        if (status == PCAN_NO_ERROR) {
            // Check for valid floor request messages
            if ((Rxmsg.ID == 0x201 && Rxmsg.DATA[0] == FLOOR1) ||
                (Rxmsg.ID == 0x202 && Rxmsg.DATA[0] == FLOOR2) ||
                (Rxmsg.ID == 0x203 && Rxmsg.DATA[0] == FLOOR3)) {
                CAN_Close(h2);
                return (int)Rxmsg.DATA[0]; // Return the floor value
            }
        } else if (status != PCAN_RECEIVE_QUEUE_EMPTY) {
            // Error reading CAN message
            printf("Error reading CAN message\n");
            CAN_Close(h2);
            return -1;
        }

        usleep(1000); // Sleep for 1ms to avoid busy waiting
    }
}