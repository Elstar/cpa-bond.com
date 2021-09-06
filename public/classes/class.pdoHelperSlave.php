<?php

/**
 * Class pdoHelper
 */
class pdoHelperSlave
{
    /**
     * @var PDO $pdo
     */
    static $host = DB_SERVER;
    static $dataBaseName = DB_NAME;
    static $charset = DB_CHARSET;
    static $login = DB_LOGIN;
    static $pass = DB_PASSWORD;
    public $pdo;
    private static $instance = null;

    public static function getInstance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct() {
        $this->pdo = new PDO("mysql:host=".self::$host.";dbname=".self::$dataBaseName.";charset=".self::$charset, self::$login, self::$pass,
            array(
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES '.self::$charset));
    }

    /**
     * @param $table string Table for insert
     * @param $vars array Column of table which we need insert. For example: array("name","age","weight"...)
     * @param array $values Values which we need insert. It can be 1 row insert and multi insert. 1 row insert must be associated
     * @param int $needLastInsertId
     * @param int $ignore
     * @return bool|string
     */
    public function insert($table, $vars, $values = [], $needLastInsertId=0, $ignore=0) {
        $valuesInsert = array();
        if ($ignore) {
            $ignore = " IGNORE";
        } else {
            $ignore = "";
        }
        if (!$values) {
            if (isset($_POST)) {
                $values = &$_POST;
            } elseif (isset($_GET)) {
                $values = &$_GET;
            }
        }
        $result = false;
        if (isset($values[0])) {
            $valuesStructure = array();
            foreach ($values as $value) {
                $allow_values = array();
                foreach($value as $key => $val) {
                    if (in_array($key, $vars)) {
                        $allow_values[] = $val;
                        $valuesInsert[] = $val;
                    }
                }
                $structure  = self::set_placeholder($allow_values);
                $valuesStructure[] = "($structure)";
            }
            $stmt = $this->pdo->prepare("INSERT$ignore INTO $table (`".implode("`,`",$vars)."`) VALUES ".implode(",",$valuesStructure).";");
        } else {
            $allow_values = array();
            foreach($values as $key => $value) {
                if (in_array($key, $vars)) {
                    $allow_values[$key] = $value;
                }
            }
            $stmt = $this->pdo->prepare("INSERT$ignore INTO $table (`".implode("`,`",$vars)."`) VALUES(:".implode(",:",$vars).");");
            $valuesInsert = $allow_values;
        }
        if (!empty($valuesInsert)) {
            $result = $stmt->execute($valuesInsert);
        }

        if ($needLastInsertId) {
            return $this->pdo->lastInsertId();
        } else {
            return $result;
        }
    }

    /**
     * @param $query string
     * @param $values array
     * @return bool
     */

    public function update($query, $values = []) {
        $stmt = $this->pdo->prepare($query);
        return $stmt->execute($values);
    }

    /**
     * @param $allowed array
     * @param array $source
     * @return bool|string
     */
    static function pdoSet($allowed, $source = []) {
        $set = '';
        if (!$source) {
            if (isset($_POST)) {
                $source = &$_POST;
            } elseif (isset($_GET)) {
                $source = &$_GET;
            }

        }
        foreach ($allowed as $field) {
            if (isset($source[$field])) {
                $set.="`".str_replace("`","``",$field)."`". "=:$field, ";
            }
        }
        return substr($set, 0, -2);
    }

    /**
     * Return indexing array of associative data
     * @param $query string
     * @param array $values
     * @param int $noNeedEmulation Use if query have LIMIT
     * @param int $unique Use for unique query result. For unique use first get field in query
     * @param array $bindByType in 0 index we have types (in PDO kind such as PARAM_STR, PARAM_INT ...) and in 1 index of bindByType array we have dop params
     * @return array
     */
    public function selectRows($query, $values = [], $noNeedEmulation=0, $unique=0, $bindByType=[]) {
        if ($noNeedEmulation) {
            $this->pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        }
        $stmt = $this->pdo->prepare($query);
        if (!empty($bindByType)) {
            $stmt = $this->bindParams($stmt, $values, $bindByType);
            $stmt->execute();
        } else {
            $stmt->execute($values);
        }

        if ($unique) {
            return $stmt->fetchAll(PDO::FETCH_UNIQUE);
        } else {
            return $stmt->fetchAll();
        }
    }

    /**
     * Return just one-dimensional associative array
     * @param $query
     * @param array $values
     * @param int $noNeedEmulation
     * @return mixed
     */
    public function selectRow($query, $values = [], $noNeedEmulation=0) {
        if ($noNeedEmulation) {
            $this->pdo->setAttribute( PDO::ATTR_EMULATE_PREPARES, false );
        }
        $stmt = $this->pdo->prepare($query);
        $stmt->execute($values);
        return $stmt->fetch();
    }

    /**
     * Return just 1 first value, not array
     * @param $query string
     * @param array $values
     * @param array $bindByType
     * @return mixed
     */
    public function selectVar($query, $values = [], $bindByType=[]) {
        $stmt = $this->pdo->prepare($query);
        if (!empty($bindByType)) {
            $stmt = $this->bindParams($stmt, $values, $bindByType);
            $stmt->execute();
        } else {
            $stmt->execute($values);
        }
        return $stmt->fetchColumn();
    }

    /**
     * Return indexing array by column from param $var
     * @param $query string
     * @param array $values
     * @param string $var
     * @param int $noNeedEmulation Use if query have LIMIT
     * @param int $unique
     * @return array
     */
    public function selectRowsVar($query, $values = [], $var="", $noNeedEmulation=0, $unique=0) {
        if ($noNeedEmulation) {
            $this->pdo->setAttribute( PDO::ATTR_EMULATE_PREPARES, false );
        }
        $stmt = $this->pdo->prepare($query);
        $stmt->execute($values);
        $result = array();
        if ($unique) {
            $stmt = $stmt->fetchAll(PDO::FETCH_UNIQUE);
        } else {
            $stmt = $stmt->fetchAll();
        }
        foreach($stmt as $row) {
            $result[] = $row[$var];
        }
        return $result;
    }

    /**
     * @param $query string
     * @param $values array
     * @return bool
     */
    public function delete($query, $values = []) {
        $stmt = $this->pdo->prepare($query);
        return $stmt->execute($values);
    }

    public function count($query, $values = []) {
        $stmt = $this->pdo->prepare($query);
        $stmt->execute($values);
        return (int)$stmt->fetchColumn();
    }

    /**
     * If you need pdo result for example to get data by row if you probably wait for big data result
     * @param $query string
     * @param array $values
     * @param int $no_need_buffering
     * @return bool|PDOStatement
     */
    public function getStmt($query, $values = [], $no_need_buffering=0) {
        if ($no_need_buffering) {
            $this->pdo->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, false);
        } else {
            $this->pdo->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true);
        }
        $stmt = $this->pdo->prepare($query);
        $stmt->execute($values);
        return $stmt;
    }

    /**
     * If you need just to do query for example create table
     * @param $query string
     * @return bool|PDOStatement
     */
    public function query($query) {
        try {
            $this->pdo->exec($query);
            return true;
        }
        catch(PDOException $e) {
            return false;
        }
    }

    public function getPDO() {
        return $this->pdo;
    }

    private function bindParams($stmt, $values, $bindByType) {
        foreach ($values as $key => $value) {
            if (is_numeric($key)) {
                $bindIndex = $key + 1;
                if (isset($bindByType[1][$key]) && $bindByType[1][$key]) {
                    $stmt->bindParam($bindIndex, $value, constant('PDO::'.$bindByType[0][$key]), $bindByType[1][$key]);
                } else {
                    $stmt->bindParam($bindIndex, $value, constant('PDO::'.$bindByType[0][$key]));
                }
            } else {
                if (isset($bindByType[1][$key])) {
                    $stmt->bindParam(':$key', $value, constant('PDO::'.$bindByType[0][$key]), $bindByType[1][$key]);
                } else {
                    $stmt->bindParam(':$key', $value, constant('PDO::'.$bindByType[0][$key]));
                }
            }
        }
        return $stmt;
    }

    /**
     * Changed array with data to array with sing of question instead
     * @param $values array
     * @return string
     */
    static function set_placeholder($values) {
        $result_string  = str_repeat('?,', count($values) - 1) . '?';
        return $result_string;
    }
}