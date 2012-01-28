SET PHP=C:\php\php.exe
SET ACTION_DIR=..\ParserIKData\Actions\
SET OUTPUT_FILE=out.txt

%PHP% %ACTION_DIR%initOkrugsAndTiks.php >%OUTPUT_FILE%

%PHP% %ACTION_DIR%initUiks.php >%OUTPUT_FILE%
