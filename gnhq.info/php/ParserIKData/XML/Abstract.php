<?php
include_once('Violation.php');
include_once('Protocol403.php');
include_once('Twitter.php');

abstract class ParserIKData_XMLProcessor_Abstract
{
    const TIME_FORMAT = 'Y-m-d H:i:s';

    protected function _prepareTime($xmlDate)
    {
        $date = '20'. $xmlDate;
        $ourdate = date(self::TIME_FORMAT, strtotime($date));
        return $ourdate;
    }

    /**
     * @param string $string
     * @param int $maxLength
     * @return string
     */
    protected function _filterString($string, $maxLength = null)
    {
        $repl = str_replace(array("\0", '"', "'", '*'), array('', '', '', '', ''), $string);
        if ($maxLength) {
            $repl = mb_substr($repl, 0, $maxLength, mb_detect_encoding($repl));
        }
        return $repl;
    }
}