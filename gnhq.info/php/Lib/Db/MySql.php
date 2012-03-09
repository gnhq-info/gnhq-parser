<?php
class Lib_Db_MySql
{
    private static $_instances = array();

    /**
     * @var Lib_Db_Config
     */
    private $_config = null;

    private $_connection = null;

    public static function getForConfig(Lib_Db_Config $dbConf)
    {
        $hash = sha1(serialize($dbConf));
        if (!isset(self::$_instances[$hash])) {
            self::$_instances[$hash] = new self($dbConf);
        }
        return self::$_instances[$hash];
    }

    private function __construct(Lib_Db_Config $dbConf)
    {
        $this->_config = $dbConf;
        $this->_getConnection();
        if ($this->_config->getDefaultDatabase()) {
            $this->selectDb($this->_config->getDefaultDatabase());
        }
    }

    public function __destruct()
    {
        mysql_close($this->_connection);
    }

    public function truncateTable($tableName)
    {
        $this->query('TRUNCATE TABLE '.$tableName);
    }

    public function selectDb($dbName)
    {
        mysql_select_db($dbName, $this->_getConnection());
    }

    public function escapeString($string)
    {
        return mysql_real_escape_string($string);
    }

    public function escapeArray($array)
    {
        $res = array();
        foreach ($array as $k => $v) {
            $res[$k] = $this->escapeString($v);
        }
        return $res;
    }

    /**
     * @param resourse $result
     * @return array
     */
    public function fetchResultToArray($result)
    {
        return mysql_fetch_array($result, MYSQL_NUM);
    }

    /**
     * @param string $what
     * @param string $from
     * @param string $where
     * @param string $limit
     * @param string $order
     * @param string $groupBy
     * @return array()
     */
    public function selectAssoc($what, $from, $where = null, $limit = null, $order = null, $groupBy = null)
    {
        $result = $this->select($what, $from, $where, $limit, $order, $groupBy);
        $data = array();
        if ($result) {
            while ($row = mysql_fetch_assoc($result)) {
                $data[] = $row;
            }
        }
        return $data;
    }

    /**
     * @param string $what
     * @param string $from
     * @param string $where
     * @param string $limit
     * @param string $order
     * @return array()
     */
    public function selectRows($what, $from, $where = null, $limit = null, $order = null)
    {
        $result = $this->select($what, $from, $where, $limit, $order);
        $data = array();
        if ($result) {
            while ($row = mysql_fetch_row($result)) {
                $data[] = $row;
            }
        }
        return $data;
    }

    /**
     * @param string $what
     * @param string $from
     * @param string $where
     * @param string $limit
     * @param string $order
     * @param string $groupBy
     */
    public function select($what, $from, $where = null, $limit = null, $order = null, $groupBy = null)
    {
        $query = 'SELECT '.$what . ' FROM ' .$from;
        if ($where) {
            $query .= ' WHERE ' . $where;
        }
        if ($groupBy) {
            $query .= ' GROUP BY ' . $groupBy;
        }
        if ($order) {
            $query .= ' ORDER BY ' . $order;
        }
        if ($limit) {
            $query .= ' LIMIT ' . $limit;
        }

        return $this->_query($query);
    }


    /**
     * @param string $query
     * @return resource
     */
    public function query($query)
    {
        return $this->_query($query);
    }

    /**
     * @param string $query
     * @return resource
     */
    private function _query($query)
    {
        global $PRINT_QUERIES;

        if (!empty($PRINT_QUERIES)) {
            print_r($query.PHP_EOL.str_repeat('=', 20) . PHP_EOL);
        }
        $result = mysql_query($query, $this->_getConnection());
        if ($error = mysql_error($this->_connection)) {
            throw new Exception('Wrong DB query '.$query. ' error : '.$error);
        }
        return $result;
    }

    /**
     * @return resource
     */
    private function _getConnection()
    {
        if ($this->_connection == null) {
            $this->_connection = mysql_connect(
            $this->_getConfig()->getHost(),
            $this->_getConfig()->getUser(),
            $this->_getConfig()->getPwd()
            );
            if (!$this->_connection) {
                throw new Exception('cant connect to database: '.mysql_error());
            }
            $charset = $this->_getConfig()->getCharset();
            mysql_query('SET character_set_client = '.$charset);
            mysql_query('SET character_set_connection = '.$charset);
            mysql_query('SET character_set_results = '.$charset);
            mysql_query('SET NAMES '.$charset);
        }
        return $this->_connection;
    }

    /**
     * @return DbConfig
     */
    private function _getConfig()
    {
        return $this->_config;
    }
}