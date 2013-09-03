<?php
/**
 * Parsing of twitter
 * @author admin
 */
class ParserIKData_XMLProcessor_Twitter2 extends ParserIKData_XMLProcessor_Abstract
{
    /**
     * @var Lib_Config_Interface
     */
    private $_config = null;
    /**
     * @var string
     */
    private $_bearerToken = null;

    const USER_ID = '357438351';

    /**
     * @return boolean
     */
    public function import()
    {
        $tObjArray = $this->load();
        if (!is_array($tObjArray)) {
            return false;
        }
        $newTwits = array();
        foreach ($tObjArray as $item) {
            $nextTwit = $this->createFromItem($item);
            if ($nextTwit instanceof ParserIKData_Model_Twit) {
                $newTwits[$nextTwit->getUniqueId()] = $nextTwit;
            }
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
     * @return array|false
     */
    public function load()
    {
        if (!$this->_bearerToken) {
            return false;
        }
        $ch = curl_init('https://api.twitter.com/1.1/statuses/user_timeline.json?user_id='.self::USER_ID);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Authorization: Bearer '.$this->_bearerToken ,
                'Content-Type: application/x-www-form-urlencoded;charset=UTF-8'
        ) );
        curl_setopt($ch, CURLOPT_HTTPGET, 1);
        //curl_setopt($ch, CURLOPT_VERBOSE , 1 );
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        $result = curl_exec($ch); // run the curl
        curl_close($ch);  // stop curling

        if ($result) {
            $result = json_decode($result);
        }
        return $result;

    }

    /**
     * @param stdClass $tObj
     * @return false|ParserIKData_Model_Twit
     */
    public function createFromItem($tObj)
    {
        if (!$tObj instanceof stdClass) {
            return false;
        }

        $fullName = $this->_filterString(html_entity_decode((string)$tObj->text));
        $link = 'https://twitter.com/nabludatel_org/statuses/' . $this->_filterString((string)$tObj->id_str);

        $params = array();

        $params['guid'] = $this->_filterString((string)$tObj->id_str);

        $params['time'] = date('Y-m-d H:i:s', strtotime((string)$tObj->created_at));
        $params['description'] = $this->_filterString(html_entity_decode((string)$tObj->text));

        $twit = ParserIKData_Model_Twit::createFromPageInfo($fullName, $link, $params);
        return $twit;
    }

    public function __construct ($config)
    {
        $this->_config = $config;
        $this->_loadBearerToken();
    }

    /**
     * @return void
     */
    private function _loadBearerToken()
    {
        $key       = $this->_config->getValue('key');
        $secret    = $this->_config->getValue('secret');
        $key       = urlencode($key);
        $secret    = urlencode($secret);
        $concat    = $key . ':' . $secret;
        $b64concat = base64_encode($concat);

        $url = "https://api.twitter.com/oauth2/token";

        $ch = curl_init($url);  // setup a curl

        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Authorization: Basic '.$b64concat ,
                'Content-Type: application/x-www-form-urlencoded;charset=UTF-8',
                'Content-Length: 29'
        ) );

        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "grant_type=client_credentials");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        // curl_setopt($ch, CURLOPT_VERBOSE , 1 );
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);

        $result = curl_exec($ch); // run the curl
        curl_close($ch);  // stop curling

        $bearerToken = false;
        if ($result) {
            $rObj = json_decode($result);
            if ($rObj->token_type == 'bearer') {
                $bearerToken = $rObj->access_token;
            }
        }
        $this->_bearerToken = $bearerToken;
    }
}