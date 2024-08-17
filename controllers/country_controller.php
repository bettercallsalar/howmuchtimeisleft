<?php

class Country_Ctrl extends Ctrl
{
    public function getAverage(int $countryId)
    {
        include_once("models/country_model.php");
        $objCountryModel = new Country_Model();
        $arrErrors = array();

        // Ensure the country ID is valid
        if ($countryId <= 0) {
            $arrErrors[] = "Invalid country ID provided.";
        } else {
            $averageLifeExpectancy = $objCountryModel->getAverage($countryId);

            if ($averageLifeExpectancy > 0) {
                $this->_arrData['average_life_expectancy'] = $averageLifeExpectancy;
            } else {
                $arrErrors[] = "Could not retrieve the average life expectancy for the selected country.";
            }
        }

        if (count($arrErrors) > 0) {
            $this->_arrData['errors'] = $arrErrors;
        }

        return $this->_arrData;
    }
    public function getUserCountry(int $userId): int
    {
        include_once("models/country_model.php");
        $objCountryModel = new Country_Model();
        $arrErrors = array();

        // Ensure the user ID is valid
        if ($userId <= 0) {
            $arrErrors[] = "Invalid user ID provided.";
        } else {
            $countryId = $objCountryModel->getUserCountry($userId);

            if ($countryId > 0) {
                $this->_arrData['country_id'] = $countryId;
            } else {
                $arrErrors[] = "Could not retrieve the country ID for the selected user.";
            }
        }

        if (count($arrErrors) > 0) {
            $this->_arrData['errors'] = $arrErrors;
        }

        return $this->_arrData['country_id'];
    }
}
