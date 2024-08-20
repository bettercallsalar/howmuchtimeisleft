<?php

class Db
{
    protected $_db;

    public function __construct()
    {
        try {
            $config = include('db_config.php');
            $this->_db = new PDO(
                "mysql:host={$config['host']};dbname={$config['dbname']}",
                $config['username'],
                $config['password'],
                array(PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC)
            );
            $this->_db->exec("SET CHARACTER SET utf8");
            $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    protected function queryExecuter($strRq)
    {
        try {
            $strRq->execute();
            return $strRq;
        } catch (PDOException $e) {
            error_log($e->getMessage(), 3, '/var/log/php_errors.log');
            error_log(var_export($strRq->errorInfo(), true), 3, '/var/log/php_errors.log');
            $_SESSION['error'] = "Error while executing the query.";
            return false;
        }
    }
}
