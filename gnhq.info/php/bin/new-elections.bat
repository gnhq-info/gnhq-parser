@echo off
SET PHP=php
SET ACTION_DIR=..\ParserIKData\Actions\
SET ELECT_CODE=%1
SET OUTPUT_FILE=out\install_%ELECT_CODE%.txt
SET ELECT_ACTION_DIR=..\ParserIKData\Elections\%ELECT_CODE%\Actions\
echo %ELECT_CODE%

%PHP% %ACTION_DIR%/create-new-elections.php %ELECT_CODE%  >> %OUTPUT_FILE%
echo ELECTIONS CREATED


%PHP% %ELECT_ACTION_DIR%/import-ik.php region >> %OUTPUT_FILE%
echo REGIONS IMPORTED
%PHP% %ELECT_ACTION_DIR%/import-ik.php tik >> %OUTPUT_FILE%
echo TIK IMPORTED
%PHP% %ELECT_ACTION_DIR%/import-ik.php uik >> %OUTPUT_FILE%
echo UIK IMPORTED

%PHP% %ELECT_ACTION_DIR%/create-js.php tik >> %OUTPUT_FILE%
echo JS CREATED
