1. создать файл INI_CONFIG_PATH . /Elections/ . {Electcode}.ini.php по аналогии Mosmer.ini.php

2. cd APPLICATION_DIR_ROOT/bin 

3. new-elections.bat {Electcode} 

---

4. прописать nginx.conf

------

6. загрузка результатов с сайта ЦИК 
php /Elections/{Electcode}/Actions/import-of-result.php