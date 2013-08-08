<?php
/**
 * Parsing of twitter
 * @author admin
 */
class ParserIKData_XMLProcessor_Twitter extends ParserIKData_XMLProcessor_Abstract
{
    const URL = 'http://twitter.com/statuses/user_timeline/357438351.rss';

    /**
     * @return boolean
     */
    public function import()
    {
        $sXml = $this->load();
        if (!$sXml) {
            return false;
        }
        $newTwits = array();
        foreach ($sXml->xpath('/rss/channel/item') as $item) {
            $nextTwit = $this->createFromItem($item);
            $newTwits[$nextTwit->getUniqueId()] = $nextTwit;
        }
        $twitGateway = new ParserIKData_Gateway_Twit();

        $currentTwits = $twitGateway->findByGuids(array_keys($newTwits));
        if ($currentTwits) {
            foreach ($currentTwits as $cTwit) {
                unset($newTwits[$cTwit->getGuid()]);
            }
        }

        foreach ($newTwits as $twit) {
            $twitGateway->save($twit);
        }
        return true;
    }

    /**
     * @return SimpleXMLElement
     */
    public function load()
    {
        return simplexml_load_file(self::URL);
    }

    /**
     * @param SimpleXMLElement $xml
     * @return ParserIKData_Model_Twit
     */
    public function createFromItem($xml)
    {
        $fullName = $this->_filterString(html_entity_decode((string)$xml->title));
        $link = $this->_filterString((string)$xml->link);

        $params = array();

        $guid = (string)$xml->guid;
        $guidParts = explode('/', $guid);
        $guid = $guidParts[count($guidParts)-1];
        $params['guid'] = $this->_filterString($guid);

        $params['time'] = date('Y-m-d H:i:s', strtotime((string)$xml->pubDate));
        $params['description'] = $this->_filterString(html_entity_decode((string)$xml->description));

        $twit = ParserIKData_Model_Twit::createFromPageInfo($fullName, $link, $params);
        return $twit;
    }
}