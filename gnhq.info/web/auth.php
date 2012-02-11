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
    public static function auth($requiredRoles)
    {
        $session = self::_getCurrentSession();
        if (!$session) {
            self::_redirectToLogin();
        }

        $uid = self::_isActiveSession($session);
        if ($uid < 1) {
            self::_redirectToLogin();
        }
        // $uid = 5;

        $uroles = self::_getUserRoles($uid);

        if (!is_array($requiredRoles)) {
            $requiredRoles = array($requiredRoles);
        }
        foreach ($requiredRoles as $role) {
            if (in_array(strtolower($role), $uroles)) {
                return;
            }
        }
        die('You dont have access for this application!');
    }

    private static function _getUserRoles($userId)
    {
        $data = self::_getDb()->selectAssoc('r.id, r.tag', 'rbac_roles AS r INNER JOIN rbac_u2r AS ru ON r.id = ru.role', 'ru.user = '.intval($userId));
        $roles = array();
        foreach ($data as $i => $row) {
            $roles[] = strtolower($row['tag']);
        }
        return $roles;
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

Gnhq_Info_Auth::auth('LOGIN');