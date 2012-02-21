configuring -

1. for working with mysql you should create file 
php/Config/mysql.ini with content 
host=yourhost
user=youruser
password=yourpws
db=yourdb
charset=charset

2. for authorization - put const AUTH_NEEDED in webinclude.php and make your own auth in auth.php

3. If you want to use caching either in parser tasks or when working with db in gateways, then you
must give web server user write access to directories 
php/ParserIKData/SrcCache/Gateway
php/ParserIKData/SrcCache/Web

4. Error logs are put into file 
php/log/error.log 
it should be accesible for writing for web server user

5. If using csv for saving data (for parser tasks) - make directory php/ParserIKData/Output writable

6. Cache is based on Zend_Cache component. You should change const PATH_TO_ZEND in php/Lib/ZendConfig.php
to point to the real path of Zend library source on your server. By default this path points to the
directory of this project, which contains used Zend components 