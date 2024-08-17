<?php

include_once("db.php");

class User_Model extends Db
{
    public function createUser($objUser)
    {
        $strQuery = "INSERT INTO user (first_name, last_name, email, password, date_of_birth, country_id, gendre, role, username) 
                     VALUES (:first_name, :last_name, :email, :password, :date_of_birth, :country_id, :gendre, :role, :username);";

        $strPrepare = $this->_db->prepare($strQuery);
        $strPrepare->bindValue(":first_name", $objUser->getFirstName(), PDO::PARAM_STR);
        $strPrepare->bindValue(":last_name", $objUser->getLastName(), PDO::PARAM_STR);
        $strPrepare->bindValue(":email", $objUser->getEmail(), PDO::PARAM_STR);
        $strPrepare->bindValue(":password", $objUser->getHashedPassword(), PDO::PARAM_STR);
        $strPrepare->bindValue(":date_of_birth", $objUser->getDateOfBirth(), PDO::PARAM_STR);
        $strPrepare->bindValue(":country_id", $objUser->getCountryId(), PDO::PARAM_INT);
        $strPrepare->bindValue(":gendre", $objUser->getGendre(), PDO::PARAM_STR);
        $strPrepare->bindValue(":role", $objUser->getRole(), PDO::PARAM_STR);
        $strPrepare->bindValue(":username", $objUser->getUsername(), PDO::PARAM_STR);

        return $this->queryExecuter($strPrepare);
    }

    public function verifMail(string $strMail): bool
    {
        $strQuery = "SELECT last_name FROM user WHERE email = :email;";
        $strPrepare = $this->_db->prepare($strQuery);
        $strPrepare->bindValue(":email", $strMail, PDO::PARAM_STR);
        $strPrepare = $this->queryExecuter($strPrepare);

        if ($strPrepare !== false) {
            return is_array($strPrepare->fetch());
        }
        return false;
    }

    public function getByMail(string $strMail): array|bool
    {
        $strQuery = "SELECT id, password, first_name, last_name, email, username
                     FROM user
                     WHERE email = :email;";
        $strPrepare = $this->_db->prepare($strQuery);
        $strPrepare->bindValue(":email", $strMail, PDO::PARAM_STR);
        $strPrepare->execute();

        $result = $strPrepare->fetch();
        return $result !== false ? $result : false;
    }

    public function updateLastConnection(string $strId)
    {
        $strQuery = "UPDATE user SET last_login = NOW() WHERE id = :id;";
        $strPrepare = $this->_db->prepare($strQuery);
        $strPrepare->bindValue(":id", $strId, PDO::PARAM_STR);

        return $this->queryExecuter($strPrepare);
    }

    public function getUserById(string $strId): array|bool
    {
        $strQuery = "SELECT date_of_birth, country_id, gendre, hobbies, bio, is_private
                     FROM user
                     WHERE id = :id;";
        $strPrepare = $this->_db->prepare($strQuery);
        $strPrepare->bindValue(":id", $strId, PDO::PARAM_STR);
        $strPrepare->execute();

        $result = $strPrepare->fetch();
        return $result !== false ? $result : false;
    }
    public function updateUser($objUser)
    {
        $strQuery = "UPDATE user
                     SET first_name = :first_name, last_name = :last_name, email = :email, date_of_birth = :date_of_birth, country_id = :country_id, gendre = :gendre, username = :username, hobbies = :hobbies, bio = :bio, is_private = :is_private
                     WHERE id = :id;";
        $strPrepare = $this->_db->prepare($strQuery);
        $strPrepare->bindValue(":first_name", $objUser->getFirstName(), PDO::PARAM_STR);
        $strPrepare->bindValue(":last_name", $objUser->getLastName(), PDO::PARAM_STR);
        $strPrepare->bindValue(":email", $objUser->getEmail(), PDO::PARAM_STR);
        $strPrepare->bindValue(":date_of_birth", $objUser->getDateOfBirth(), PDO::PARAM_STR);
        $strPrepare->bindValue(":country_id", $objUser->getCountryId(), PDO::PARAM_INT);
        $strPrepare->bindValue(":gendre", $objUser->getGendre(), PDO::PARAM_STR);
        $strPrepare->bindValue(":username", $objUser->getUsername(), PDO::PARAM_STR);
        $strPrepare->bindValue(":hobbies", $objUser->getHobbies(), PDO::PARAM_STR);
        $strPrepare->bindValue(":bio", $objUser->getBio(), PDO::PARAM_STR);
        $strPrepare->bindValue(":is_private", $objUser->getIsPrivate(), PDO::PARAM_BOOL);
        $strPrepare->bindValue(":id", $objUser->getId(), PDO::PARAM_INT);

        return $this->queryExecuter($strPrepare);
    }
}
