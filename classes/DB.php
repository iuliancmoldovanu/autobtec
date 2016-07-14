<?php

class DB
{
    private static $_instance = null;
    private $_pdo,
        $_query,
        $_error = false,
        $_results,
        $_count;

    public function __construct()
    {
        try {
	        // establish the connection to the database
            $this->_pdo = new PDO('mysql:host=' .
                                Config::get('mysql/host') . ';dbname=' .
                                Config::get('mysql/db'),
					            Config::get('mysql/username'),
					            Config::get('mysql/password'));
        } catch (PDOException $e) {
	        // close the connection and shows the error
            die($e->getMessage());
        }
    }

	// this function will help the app to be faster by instantiating only once
    public static function getInstance()
    {
        if (!isset(self::$_instance)) {
            self::$_instance = new DB();
        }
        return self::$_instance;
    }

	// this function return the data from database
    public function query($sql, $params = array())
    {

        $this->_error = false;
        if ($this->_query = $this->_pdo->prepare($sql)) {
            $i = 1;
            if (count($params)) {
                foreach ($params as $param) {
                    $this->_query->bindValue($i, $param);
                    $i++;
                }
            }
            if ($this->_query->execute()) {
                $this->_results = $this->_query->fetchAll(PDO::FETCH_OBJ);
                $this->_count = $this->_query->rowCount();
            } else {
                $this->_error = true;
            }
        }
        return $this;
    }

	// using this function will return a specific data from a table
    public function action($action, $table, $where = array())
    {
        if (count($where) === 3) {
            $operators = array('=', '>', '<', '>=', '<=');
            $field = $where[0];
            $operator = $where[1];
            $value = $where[2];
            if (in_array($operator, $operators)) {
                $sql = "{$action} FROM {$table} WHERE {$field} {$operator} ?";
                if (!$this->query($sql, array($value))->error()) {
                    return $this;
                }
            }
        }
        return false;
    }

	// using this function will return all data from a table
    public function get($table, $where)
    {
        return $this->action('SELECT *', $table, $where);
    }

	// using this function will delete a specific data from a table
    public function delete($table, $where)
    {
        return $this->action('DELETE', $table, $where);
    }

	// using this function will create new data inside a table
    public function insert($table, $fields = array())
    {
        if (count($fields)) {
            $keys = array_keys($fields);
            $values = '';
            $i = 1;
            foreach ($fields as $field) {
                $values .= '?';
                if ($i < count($fields)) {
                    $values .= ', ';
                }
                $i++;
            }
            $sql = "INSERT INTO {$table} (`" . implode('`, `', $keys) . "`) VALUES ({$values})";
            if (!$this->query($sql, $fields)->error()) {
                return true;
            }
        }
        return false;
    }

	// using this function will update a specific data from a table
    public function update($table, $id, $fields = array())
    {
        if (count($fields)) {
            $set = '';
            $i = 1;
            foreach ($fields as $name => $values) {
                $set .= "{$name} = ?";
                if ($i < count($fields)) {
                    $set .= ', ';
                }
                $i++;
            }
            $sql = "UPDATE {$table} SET {$set} WHERE id = {$id}";
            if (!$this->query($sql, $fields)->error()) {
                return true;
            }
        }
        return false;
    }

	// using this function will return array data from a table
    public function results()
    {
        return $this->_results;
    }

	// using this function will return first value from the array
    public function first()
    {
        return $this->results()[0];
    }

	// using this function will return if there are any errors
    public function error()
    {
        return $this->_error;
    }

	// using this function will return the length of the array
    public function count()
    {
        return $this->_count;
    }

}