<?php
class Lib_Db_MySql
{
    /**
     * @var Lib_Db_Config
     */
    private $_config = null;

    private $_connection = null;

    public function __construct(Lib_Db_Config $dbConf)
    {
        $this->_config = $dbConf;
    }

    public function __destruct()
    {
        if ($this->_connection) {
            mysql_close($this->_connection);
        }
    }

    public function truncateTable($tableName)
    {
        $this->query('TRUNCATE TABLE '.$tableName);
    }

    public function selectDb($dbName)
    {
        mysql_select_db($dbName, $this->_getConnection());
    }


    /**
     * @param string $what
     * @param string $from
     * @param string $where
     * @param string $limit
     * @param string $order
     * @return array()
     */
    public function selectAssoc($what, $from, $where = null, $limit = null, $order = null)
    {
        $result = $this->select($what, $from, $where, $limit, $order);
        if(!$result) {
            return array();
        } else {
            $data = array();
            while ($row = mysql_fetch_assoc($result)) {
                $data[] = $row;
            }
            return $data;
        }
    }

    /**
     * @param string $what
     * @param string $from
     * @param string $where
     * @param string $limit
     * @param string $order
     */
    public function select($what, $from, $where = null, $limit = null, $order = null)
    {
        $query = 'SELECT '.$what . ' FROM ' .$from;
        if ($where) {
            $query .= ' WHERE ' . $where;
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
        //var_dump($query);
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