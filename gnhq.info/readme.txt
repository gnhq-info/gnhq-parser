configuring -

1. for working with mysql you should create file 
/Config/mysql.ini with content 
host=yourhost
user=youruser
password=yourpws
db=yourdb
charset=charset

2. for authorization - put const AUTH_NEEDED in webinclude.php and make your own auth in auth.php