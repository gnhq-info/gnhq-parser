<?php
class Lib_String_Extracter
{
    /**
     * @param string $from
     * @param string $to
     * @param string $string
     * @param boolean $includeEnds
     * @return string|false
     */
    public function mbExtract($from, $to, $string, $includeEnds = false)
    {
        $fromPos = mb_strpos($string, $from);
        if ($fromPos === false) {
            return false;
        }
        $fromLen = mb_strlen($from);
        $toLen = mb_strlen($to);


        $toPos = mb_strpos($string, $to, $fromPos+$fromLen+1);
        if ($toPos === false) {
            return false;
        }

        if ($includeEnds) {
            $eStart = $fromPos;
            $eFinish = $toPos + $toLen;
        } else {
            $eStart = $fromPos + $fromLen;
            $eFinish = $toPos;
        }
        return mb_substr($string, $eStart, $eFinish - $eStart);
    }

    /**
    * @param string $haystack
    * @param string $from
    * @param string $to
    * @param boolean $includeEnds
    * @return string|null
    */
    public function stringInBetween($haystack, $from, $to, $includeEnds = false)
    {
        $enc = mb_detect_encoding($haystack);
        $fromPos = strpos($haystack, $from, 0);//, $enc);
        if (!$fromPos) {
            return null;
        }
        $toPos  = strpos($haystack, $to, $fromPos);//, $enc);
        if (!$toPos) {
            return null;
        }
        // print_r($fromPos. ':'.$toPos.PHP_EOL);
        if ($includeEnds) {
            $res = substr($haystack, $fromPos, ($toPos - $fromPos + strlen($to)));
        } else {
            $res = substr($haystack, $fromPos + strlen($from), ($toPos - $fromPos - strlen($from)));
        }
        return $res;
    }
}