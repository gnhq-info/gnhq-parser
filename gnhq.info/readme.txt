configuring -

1. for working with mysql you should create file 
php/Config/mysql.ini with content 
host=yourhost
user=youruser
password=yourpws
db=yourdb
charset=charset

2. for authorization - put const AUTH_NEEDED in webinclude.php and make your own auth in auth.php

3. For parser tasks if using cache - give web server user write access to directories 
php/ParserIKData/SrcCache/Gateway
php/ParserIKData/SrcCache/Web

4. Error logs are put into file 
php/log/error.log 
it should be accesible for writing

5. If using csv for saving data (for parser tasks) - make directory php/ParserIKData/Output writable 