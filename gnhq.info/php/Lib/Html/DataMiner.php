<?php
class Lib_Html_DataMiner
{
    private $_extracter = null;

    /**
     * @param array $tags
     * @return multitype:Ambigous <Ambigous <string, false, boolean>>
     */
    public function getLinks($tags)
    {
        $results = array();
        foreach ($tags as $tag) {
            $pTag = $this->_getLink($tag);
            $results[$pTag['name']] = $pTag['link'];
        }
        return $results;
    }

    /**
     * @param array $tags
     * @return array
     */
    public function getOptions($tags)
    {
        $results = array();
        foreach ($tags as $tag) {
            $pTag = $this->_getOption($tag);
            $results[$pTag['text']] = $pTag['value'];
        }
        return $results;
    }

    /**
     * @param string $table
     * @param int $maxRows
     * @return string[]
     */
    public function extractRows($table, $maxRows)
    {
        return $this->_extractLastLevelChildren('<tr', '</tr>', $maxRows, $table);
    }

    /**
    * @param string $row
    * @param int $maxCells
    * @return string[]
    */
    public function extractCells($row, $maxCells)
    {
        return $this->_extractLastLevelChildren('<td', '</td>', $maxCells, $row);
    }


    /**
     * @param string $select
     * @param int $maxOptions
     * @return multitype:Ambigous <string, false, boolean>
     */
    public function extractOptions($select, $maxOptions)
    {
        return $this->_extractLastLevelChildren('<option', '</option>', $maxOptions, $select);
    }

    /**
     * @param string $start
     * @param string $end
     * @param int $max
     * @param string $string
     * @throws Exception
     */
    private function _extractLastLevelChildren($start, $end, $max, $string)
    {
        $i = 0;
        $children = array();
        while (($next = $this->_getExtracter()->mbExtract($start, $end, $string, true)) !== false) {
            $children[] = $next;
            $string = mb_substr($string, mb_strpos($string, $end) + mb_strlen($end));
            if ($i++ >= $max) {
                throw new Exception('More than '.$max . ' children');
            }
        }
        return $children;
    }

    private function _getLink($tag)
    {
        $link = $this->_getExtracter()->mbExtract('href="', '"', $tag, false);
        $name = $this->_getExtracter()->mbExtract('>', '<', $tag, false);
        return array(
            'link'  => $link,
            'name'  => $name
        );
    }

    private function _getOption($tag)
    {
        $value = $this->_getExtracter()->mbExtract('value="', '"', $tag, false);
        $text  = $this->_getExtracter()->mbExtract('>', '<', $tag, false);
        return array(
        	'value' => $value,
            'text'  => $text
        );
    }


    /**
     * @return Lib_String_Extracter
     */
    private function _getExtracter()
    {
        if ($this->_extracter === null) {
            $this->_extracter = new Lib_String_Extracter();
        }
        return $this->_extracter;
    }
}