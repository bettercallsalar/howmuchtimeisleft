<?php
class Bdd
{
	protected $_db;

	public function __construct()
	{
		try {
			$this->_db = new PDO(
				"mysql:host=localhost;dbname=howmuchisleft",
				"root",
				"",
				array(PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC)
			);
			// Pour résoudre les problèmes d’encodage
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
			$_SESSION['error']  = "Erreur while executing the query";
			return false;
		}
	}
}
