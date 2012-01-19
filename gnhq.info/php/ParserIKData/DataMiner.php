<?php
class ParserIKData_DataMiner
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

    private function _getLink($tag)
    {
        $link = $this->_getExtracter()->mbExtract('href="', '"', $tag, false);
        $okrug = $this->_getExtracter()->mbExtract('>', '<', $tag, false);
        return array(
            'link'  => $link,
            'name'  => $okrug
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