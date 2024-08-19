<?php

class Db
{
    protected $_db;

    public function __construct()
    {
        try {
            $this->_db = new PDO(
                "mysql:host=51.159.3.196;dbname=howmuchisleft",  // Ensure this is the correct IP and DB name
                "bettercallsalar",
                "Vuxim494",
                array(PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC)
            );
            // Resolve encoding issues
            $this->_db->exec("SET CHARACTER SET utf8");
            $this->_db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            // Log error instead of displaying it
            error_log($e->getMessage(), 3, '/var/log/php_errors.log');
            echo "A database error occurred. Please try again later.";
        }
    }

    protected function queryExecuter($strRq)
    {
        try {
            $strRq->execute();
            return $strRq;
        } catch (PDOException $e) {
            // Log detailed error and provide a user-friendly message
            error_log($e->getMessage(), 3, '/var/log/php_errors.log');
            error_log(var_export($strRq->errorInfo(), true), 3, '/var/log/php_errors.log');
            $_SESSION['error']  = "Error while executing the query.";
            return false;
        }
    }
}
