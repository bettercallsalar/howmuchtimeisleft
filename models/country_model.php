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
    public function getAverage(int $countryId): float
    {
        $strQuery = "SELECT average_life_expectancy FROM country WHERE id = :country_id";
        $strPrepare = $this->_db->prepare($strQuery);
        $strPrepare->bindValue(':country_id', $countryId, PDO::PARAM_INT);
        $strPrepare->execute();
        $result = $strPrepare->fetch(PDO::FETCH_ASSOC);

        return $result['average_life_expectancy'] ?? 0.0;
    }
    public function getUserCountry(int $userId): int
    {
        $strQuery = "SELECT country_id FROM user WHERE id = :user_id";
        $strPrepare = $this->_db->prepare($strQuery);
        $strPrepare->bindValue(':user_id', $userId, PDO::PARAM_INT);
        $strPrepare->execute();
        $result = $strPrepare->fetch(PDO::FETCH_ASSOC);

        return $result['country_id'] ?? 0;
    }
}
