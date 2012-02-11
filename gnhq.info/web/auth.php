<?php
if (!defined('PROJECT_STARTED')) {
    die();
}

/**
 * session authorizing. Same logic as in
 * "checkuser" perl procedure defined in GN.pm
 *
 * @author admin
 */
class Gnhq_Info_Auth
{
    public static function auth()
    {
        $session = self::_getCurrentSession();
        if (!$session) {
            self::_redirectToLogin();
        }

        $uid = self::_isActiveSession($session);
        if ($uid < 1) {
            self::_redirectToLogin();
        }
    }

    private static function _redirectToLogin()
    {
        header('Location: https://gnhq.info/login.cgi?rp=https://gnhq.info/stat/web/index.php');
        exit;
    }

    /**
     * -1 - если нет пользователя, uid - если есть
     * @param string $session
     * @return int
     */
    private static function _isActiveSession($session)
    {
        $data = self::_getDb()->selectAssoc('uid', 'sessions', 'session="'.self::_getDb()->escapeString($session).'"');
        if (empty($data)) {
            return -1;
        } else {
            return intval($data[0]['uid']);
        }
    }

    private static function _getCurrentSession()
    {
        return $_COOKIE['session_id'];
    }

    private static function _getDb()
    {
        $locator = ParserIKData_ServiceLocator::getInstance();
        $db = $locator->getMySql();
        $db->selectDb($locator->getMySqlConfig()->getValue('db'));
        return $db;
    }
}

Gnhq_Info_Auth::auth();