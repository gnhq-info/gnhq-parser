1. cd APPLICATION_DIR_ROOT

2. занести нужную информацию в /Actions/create-new-elections.php

3. php Actions/create-new-elections.php

3. запустить  
php /Elections/{Omsk}/Actions/import-ik.php region
php /Elections/{Omsk}/Actions/import-ik.php tik
php /Elections/{Omsk}/Actions/import-ik.php uik

4. запустить
php /Elections/{Omsk}/Actions/create-js.php tik

5. прописать nginx.conf

------

6. загрузка результатов с сайта ЦИК 
php /Elections/{Omsk}/Actions/import-of-result.php