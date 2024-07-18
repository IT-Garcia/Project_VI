#ifndef DB_FUNCTIONS

#define DB_FUNCTIONS

std::string getCurrentTime();
std::string getCurrentDate(); 

int db_getFloorNum();
int insReqDB(int curFlr, int floorRequest);
int insFlrDB(int floorNum);

#endif
