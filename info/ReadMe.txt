To initialize this database:
Enter the following statement: 

create database PARKING_SYSTEM;

Enter the following statement:

use PARKING_SYSTEM;

Execute the MySQL script to create the tables in the PARKING_SYSTEM database.
Make sure the PARKING_SYSTEM database is your default database. To check it is your default database,
execute ‘select database();’ and it should return ‘PARKING_SYSTEM’.

First, create the tables by Copy-Pasting the blocks of text within Database_Initializer.txt that begin with CREATE TABLE 
(they are the top-most blocks of text) into your command line.

Next, Copy-Paste the blocks of text within Database_Initializer.txt that begin with INSERT INTO 
(All the blocks of text after the CREATE TABLE statements) into your command line. Do this 1 block at a time. 

This should initialize your database. Now you can access the html page: after ensuring your MySQL service has been started 

localhost:8080