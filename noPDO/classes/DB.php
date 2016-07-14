<?php

/**
 * Created by PhpStorm.
 * User: Ciprian
 * Date: 25/01/2016
 * Time: 15:55
 */
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
        // establish the connection to the database
        $this->_pdo = mysqli_connect(Config::get('mysql/host'), Config::get('mysql/username'), Config::get('mysql/password'), Config::get('mysql/db'));

        if (!$this->_pdo) {
            die(mysqli_error($this->_pdo));
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
                $this->_results = $this->_query->fetch();
                $this->_count = $this->_query->num_rows;
                print_r($this->_count);
                print_r($this->_results);
            } else {
                $this->_error = true;
            }
        }
        return $this;
    }
} 