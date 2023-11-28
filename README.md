Many of the files discussed in this page can be found under the folder 'info'

Make sure MySQL is running on your laptop.

Connect to your MySQL.

Enter the command ‘create database PARKING_SYSTEM;’ to create the database.

Select database PARKING_SYSTEM as your current/default database: 'use PARKING_SYSTEM'

Execute the MySQL script to create 6 tables in the COMPANY database.
Let’s assume the script is under C:/CSE3241Repo directory.
'source C:/CSE3241Repo/Create_Tables.sql;'

Next, Copy-Paste each of the blocks of text within Database_Initializer.txt that begin with INSERT INTO 
(All the blocks of text after the CREATE TABLE statements) into your command line. Do this 1 block at a time. 

Run this command next:
grant all on PARKING_SYSTEM.* to phpuser@'%';

This should be it, access the page by visiting http://localhost:8080/ on your web browser.

The database begins with 2 users pre loaded in: 
Username: Admin  Password: Admin123  Which is a user with admin privledges.
Username: Syan_Raval  Password: SyanLovesPrimaryKeys   This is a standard user.

Things of note: 
This application assumes the following:
    your server name = "localhost"
    your database username = "phpuser"
    your database password = "phpwd"
    your database name = "parking_system"
    If this is not the case, unforseen errors may occur.
Please, when navigating the html site, do not use your forward/backward arrows, but rather use the built in navigation tools (ex: do not use the back arrow to access the log in page again, but rather use the log out button). Otherwise, unforseen errors may occur.
