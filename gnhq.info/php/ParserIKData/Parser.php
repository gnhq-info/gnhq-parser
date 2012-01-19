<?php
class ParserIKData_Parser
{
    const MAX_AMOUNT = 1000;

    private $_pageSource;
    private $_len;

    private $_nextOffset = 0;

    /**
     * @param $needle
     * @return string[]
     */
    public function findSurroundingTags($needle)
    {
        $tags = array();
        for ($i = 0; $i < self::MAX_AMOUNT; $i++) {
            $nextTag = $this->_findSurroundingTag($needle, $this->_nextOffset);
            if ($nextTag) {
                $tags[] = $nextTag;
            } else {
                break;
            }
        }
        return $tags;
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

    /**
     * @param string $pageSource
     */
    public function setPageSource($pageSource)
    {
        $this->_pageSource = $pageSource;
        $this->_len = mb_strlen($this->_pageSource);
        $this->_nextOffset = 0;
    }


    /**
     * @param string $needle
     * @param int $offset
     * @return string|false
     */
    private function _findSurroundingTag($needle, $offset)
    {
        $pos = mb_strpos($this->_pageSource, $needle, $offset);
        if ($pos === false) {
            return false;
        }
        $startPos = 0;
        $endPos = 0;
        for ($i = $pos; $i > 0; $i--) {
            $char = $this->_getCharAt($i);
            if ($char == '<') {
                $startPos = $i;
                break;
            }
        }

        for ($i = $pos; $i < $this->_len; $i++) {
            $char = $this->_getCharAt($i);
            if ($char == '>') {
                $endPos = $i;
                break;
            }
        }
        $this->_nextOffset = $endPos;
        return mb_substr($this->_pageSource, $startPos, $endPos - $startPos + 1);
    }


    /**
     * @param int $i
     * @return string
     */
    private function _getCharAt($i)
    {
        return mb_substr($this->_pageSource, $i, 1);
    }

    /**
     * @return string
     */
    private function _getPageSource()
    {
        return $this->_pageSource;
    }

}