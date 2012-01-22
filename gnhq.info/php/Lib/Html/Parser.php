<?php
class Lib_Html_Parser
{
    const MAX_AMOUNT = 1000;

    private $_pageSource;
    private $_len;

    private $_nextOffset = 0;

    /**
     * минимальный тэг заданного типа, содержащий строку
     * @param string $needle
     * @param string $tag
     * @return string|false
     */
    public function findMinContainingTag($needle, $tag)
    {
        $pos = mb_strpos($this->_pageSource, $needle, null, $this->_getEncoding());
        if ($pos === false) {
            return false;
        }
        $prevPos = $openPos = -1;
        $openTag = '<' . $tag;
        $closeTag = '</'.$tag.'>';
        while ($openPos < $pos && $openPos !== false) {
            $prevPos = $openPos;
            $openPos = mb_strpos($this->_pageSource, $openTag, $openPos+1, $this->_getEncoding());
        }
        $closePos = mb_strpos($this->_pageSource, $closeTag, $prevPos + 1, $this->_getEncoding());
        if ($closePos === false || $closePos < $pos) {
            return false;
        }
        $len = $closePos + mb_strlen($closeTag, $this->_getEncoding()) - $prevPos;
        $result = mb_substr($this->_pageSource, $prevPos, $len, $this->_getEncoding());
        return $result;
    }

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
        $extracter = new Lib_String_Extracter();
        return $extracter->stringInBetween($haystack, $from, $to, $includeEnds);
    }

    /**
     * @param string $pageSource
     * @return Lib_Html_Parser
     */
    public function setPageSource($pageSource)
    {
        $this->_pageSource = $pageSource;
        $this->_len = mb_strlen($this->_pageSource);
        $this->_nextOffset = 0;
        return $this;
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
    private function _getEncoding()
    {
        return mb_detect_encoding($this->_pageSource);
    }


    /**
     * @return string
     */
    private function _getPageSource()
    {
        return $this->_pageSource;
    }

}