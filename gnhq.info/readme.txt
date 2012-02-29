configuring -

1. for working with mysql you should create file 
php/Config/mysql.ini with content 
host=yourhost
user=youruser
password=yourpassword
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

5. Dump of the database is in /php/ParserIKData/Sql/structure.sql

6. cron tasks. all import from external sources is made by cron tasks
you should put this lines into /etc/cron.d/www (change the user www-data to the 
user of your web server)

# begin of agregator cron tasks
MAILTO=YOUR_ADMIN_MAIL
ACTIONPATH=/home/gnsite/nabludatel/statistic/php/ParserIKData/Actions

#importing twits
*/5 * * * * www-data php $ACTIONPATH/import-twits.php

# end of agregator cron tasks