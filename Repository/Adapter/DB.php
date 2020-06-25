<?php

namespace App;

use Exception;
use mysqli;
use mysqli_stmt;

/**
 * Database class
 *
 * Class DB
 */
class DB
{
    /**
     * @var string - Configuration parameters
     */
    private string $host;
    private string $user;
    private string $pass;
    private string $name;
    private string $charset = 'utf8';

    /**
     * @var DB|null - Instance
     */
    protected static ?DB $_instance = null;

    /**
     * @var mysqli - Database connection
     */
    protected mysqli $connection;

    /**
     * @var mysqli_stmt - Database query
     */
    protected mysqli_stmt $query;

    /**
     * @var int - Database query count
     */
    public int $queryCount = 0;

    /**
     * Get instance
     *
     * @return DB|null
     * @throws Exception
     */
    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    /**
     * DB constructor.
     *
     * @throws Exception
     */
    public function __construct()
    {
        $this->host = getenv('MYSQL_HOST');
        $this->user = getenv('MYSQL_USER');
        $this->pass = getenv('MYSQL_PASSWORD');
        $this->name = getenv('MYSQL_DATABASE');

        $this->connection = new mysqli(
            $this->host, $this->user, $this->pass, $this->name
        );
        if ($this->connection->connect_error) {
            throw new Exception(
                'Failed to connect to MySQL - '
                . $this->connection->connect_error
            );
        }
        $this->connection->set_charset($this->charset);
    }

    /**
     * Execute the database query
     *
     * @param $query
     *
     * @return $this
     * @throws Exception
     */
    public function query($query)
    {
        $this->query = $this->connection->prepare($query);
        if ($this->query) {
            if (func_num_args() > 1) {
                $x       = func_get_args();
                $args    = array_slice($x, 1);
                $types   = '';
                $argsRef = [];
                foreach ($args as $k => &$arg) {
                    if (is_array($args[$k])) {
                        foreach ($args[$k] as $j => &$a) {
                            $types     .= $this->_getType($args[$k][$j]);
                            $argsRef[] = &$a;
                        }
                    } else {
                        $types     .= $this->_getType($args[$k]);
                        $argsRef[] = &$arg;
                    }
                }
                array_unshift($argsRef, $types);
                call_user_func_array([$this->query, 'bind_param'], $argsRef);
            }
            $this->query->execute();
            if ($this->query->errno) {
                throw new Exception(
                    'Unable to process MySQL query (check your params) - '
                    . $this->query->error
                );
            }
            $this->queryCount++;
        } else {
            throw new Exception(
                'Unable to prepare statement (check your syntax) - '
                . $this->connection->error
            );
        }
        return $this;
    }

    /**
     * Fetch the database request
     *
     * @param string $type - Result type (all|array)
     *
     * @return array
     */
    public function fetch($type = 'all')
    {
        $params = [];
        $row    = [];
        $meta   = $this->query->result_metadata();
        while ($field = $meta->fetch_field()) {
            $params[] = &$row[$field->name];
        }
        call_user_func_array([$this->query, 'bind_result'], $params);
        $result = [];
        while ($this->query->fetch()) {
            if ('all' == $type) {
                $r = [];
                foreach ($row as $key => $val) {
                    $r[$key] = $val;
                }
                $result[] = $r;
            } else {
                foreach ($row as $key => $val) {
                    $result[$key] = $val;
                }
            }
        }
        $this->query->close();
        return $result;
    }

    /**
     * Return number of rows
     *
     * @return mixed
     */
    public function numRows()
    {
        $this->query->store_result();
        return $this->query->num_rows;
    }

    /**
     * Close database connection
     *
     * @return bool
     */
    public function close()
    {
        return $this->connection->close();
    }

    /**
     * Return the number of affected rows
     *
     * @return mixed
     */
    public function affectedRows()
    {
        return $this->query->affected_rows;
    }

    /**
     * Return insert_id
     *
     * @return bool|int
     */
    public function getInsertId()
    {
        if (!$this->query) {
            return false;
        } else {
            return $this->query->insert_id;
        }
    }

    /**
     * Get variable type
     *
     * @param $var
     *
     * @return string
     */
    private function _getType($var)
    {
        if (is_string($var)) {
            return 's';
        }

        if (is_float($var)) {
            return 'd';
        }

        if (is_int($var)) {
            return 'i';
        }

        return 'b';
    }
}
