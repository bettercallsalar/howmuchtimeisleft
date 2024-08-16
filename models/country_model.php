<?php
include_once("db.php");

class Country_Model extends Db
{
    public function getAllCountries(): array
    {
        $strQuery = "SELECT id, country_name FROM country ORDER BY country_name ASC";
        $strPrepare = $this->_db->prepare($strQuery);
        $strPrepare->execute();
        return $strPrepare->fetchAll(PDO::FETCH_ASSOC);
    }
}
