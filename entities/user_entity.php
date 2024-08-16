<?php

include("entities/mother_entity.php");

class User extends Entity
{
    private string $_first_name = "";
    private string $_last_name = "";
    private string $_gendre = "";
    private string $_email = "";
    private string $_password = "";
    private string $_date_of_birth = "";
    private int $_country_id = 0;
    private string $_role = "";
    private string $_username = "";

    public function __construct()
    {
        $this->_prefixe = "user_";
    }

    // Setters and getters
    public function setFirstName(string $strFirstName)
    {
        $this->_first_name = strtolower(trim($strFirstName));
    }

    public function getFirstName(): string
    {
        return $this->_first_name;
    }
    // -----------------------------
    public function setLastName(string $strLastName)
    {
        $this->_last_name = strtoupper(trim($strLastName));
    }

    public function getLastName(): string
    {
        return $this->_last_name;
    }
    // -----------------------------

    public function setGendre(string $strGendre)
    {
        $this->_gendre = strtolower(trim($strGendre));
    }

    public function getGendre(): string
    {
        return $this->_gendre;
    }
    // -----------------------------

    public function setEmail(string $strEmail)
    {
        $this->_email = strtolower(trim($strEmail));
    }

    public function getEmail(): string
    {
        return $this->_email;
    }
    // -----------------------------

    public function setPassword(string $strPassword)
    {
        $this->_password = $strPassword;
    }

    public function getPassword(): string
    {
        return $this->_password;
    }
    public function getHashedPassword(): string
    {
        return password_hash($this->_password, PASSWORD_DEFAULT);
    }
    // -----------------------------

    public function setDateOfBirth(string $strDateOfBirth)
    {
        $this->_date_of_birth = $strDateOfBirth;
    }

    public function getDateOfBirth(): string
    {
        return $this->_date_of_birth;
    }
    // -----------------------------

    public function setCountryId(int $intCountryId)
    {
        $this->_country_id = $intCountryId;
    }

    public function getCountryId(): int
    {
        return $this->_country_id;
    }
    // -----------------------------

    public function setRole(string $strRole)
    {
        $this->_role = strtolower(trim($strRole));
    }

    public function getRole(): string
    {
        return $this->_role;
    }
    // -----------------------------

    public function setUsername(string $strUsername)
    {
        $this->_username = strtolower(trim($strUsername));
    }

    public function getUsername(): string
    {
        return $this->_username;
    }
}
