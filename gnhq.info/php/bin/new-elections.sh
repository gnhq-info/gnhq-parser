ACTION_DIR=../ParserIKData/Actions/

ELECT_CODE=$1

OUTPUT_FILE=out/install_${ELECT_CODE}.txt

ELECT_ACTION_DIR=../ParserIKData/Elections/${ELECT_CODE}/Actions/

php ${ACTION_DIR}/create-new-elections.php ${ELECT_CODE} >> ${OUTPUT_FILE}

echo ELECTIONS CREATED

php ${ELECT_ACTION_DIR}/import-ik.php region >> ${OUTPUT_FILE}
echo REGIONS IMPORTED

php ${ELECT_ACTION_DIR}/import-ik.php tik >> ${OUTPUT_FILE}
echo TIK IMPORTED

php ${ELECT_ACTION_DIR}/import-ik.php uik >> ${OUTPUT_FILE}
echo UIK IMPORTED

php ${ELECT_ACTION_DIR}/create-js.php tik >> ${OUTPUT_FILE}
echo JS CREATED
