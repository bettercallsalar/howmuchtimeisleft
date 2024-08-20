<?php

include("entities/mother_entity.php");

class User extends Entity
{
    private string $first_name = '';
    private string $last_name = '';
    private string $gendre = '';
    private string $email = '';
    private string $password = '';
    private string $date_of_birth = '';
    private int $country_id = 0;
    private string $role = '';
    private string $username = '';
    private string $bio = '';
    private ?string $hobbies = null;
    private bool $is_private = false;

    public function setFirstName(string $strFirstName)
    {
        $this->first_name = strtolower(trim($strFirstName));
    }

    public function getFirstName(): string
    {
        return $this->first_name;
    }
    // -----------------------------
    public function setLastName(string $strLastName)
    {
        $this->last_name = strtoupper(trim($strLastName));
    }

    public function getLastName(): string
    {
        return $this->last_name;
    }
    // -----------------------------

    public function setGendre(string $strGendre)
    {
        $this->gendre = strtolower(trim($strGendre));
    }

    public function getGendre(): string
    {
        return $this->gendre;
    }
    // -----------------------------

    public function setEmail(string $strEmail)
    {
        $this->email = strtolower(trim($strEmail));
    }

    public function getEmail(): string
    {
        return $this->email;
    }
    // -----------------------------

    public function setPassword(string $strPassword)
    {
        $this->password = $strPassword;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getHashedPassword(): string
    {
        return password_hash($this->password, PASSWORD_DEFAULT);
    }
    // -----------------------------

    public function setDateOfBirth(string $strDateOfBirth)
    {
        $this->date_of_birth = $strDateOfBirth;
    }

    public function getDateOfBirth(): string
    {
        return $this->date_of_birth;
    }
    // -----------------------------

    public function setCountryId(int $intCountryId)
    {
        $this->country_id = $intCountryId;
    }

    public function getCountryId(): int
    {
        return $this->country_id;
    }
    // -----------------------------

    public function setRole(string $strRole)
    {
        $this->role = strtolower(trim($strRole));
    }

    public function getRole(): string
    {
        return $this->role;
    }
    // -----------------------------

    public function setUsername(string $strUsername)
    {
        $this->username = strtolower(trim($strUsername));
    }

    public function getUsername(): string
    {
        return $this->username;
    }
    // -----------------------------
    public function setBio(?string $strBio)
    {
        $this->bio = $strBio ?? '';
    }

    public function getBio(): string
    {
        return $this->bio;
    }
    // -----------------------------
    public function setHobbies(?string $strHobbies)
    {
        $this->hobbies = $strHobbies ?? '';
    }

    public function getHobbies(): string
    {
        return $this->hobbies ?? '';
    }
    // -----------------------------
    public function setIsPrivate(bool $boolIsPrivate)
    {
        $this->is_private = $boolIsPrivate;
    }

    public function getIsPrivate(): bool
    {
        return $this->is_private;
    }
}
