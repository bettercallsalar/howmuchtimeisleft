<?php

class User_Model extends Db
{
    public function createUser($objUser)
    {
        $strQuery = "INSERT INTO user (first_name, last_name, email, password, date_of_birth, country_id, gendre, username) 
                     VALUES (:first_name, :last_name, :email, :password, :date_of_birth, :country_id, :gendre, :username);";

        $strPrepare = $this->_db->prepare($strQuery);
        $strPrepare->bindValue(":first_name", $objUser->getFirstName(), PDO::PARAM_STR);
        $strPrepare->bindValue(":last_name", $objUser->getLastName(), PDO::PARAM_STR);
        $strPrepare->bindValue(":email", $objUser->getEmail(), PDO::PARAM_STR);
        $strPrepare->bindValue(":password", $objUser->getHashedPassword(), PDO::PARAM_STR);
        $strPrepare->bindValue(":date_of_birth", $objUser->getDateOfBirth(), PDO::PARAM_STR);
        $strPrepare->bindValue(":country_id", $objUser->getCountryId(), PDO::PARAM_INT);
        $strPrepare->bindValue(":gendre", $objUser->getGendre(), PDO::PARAM_STR);
        $strPrepare->bindValue(":username", $objUser->getUsername(), PDO::PARAM_STR);

        return $this->queryExecuter($strPrepare);
    }

    public function verifMail(string $strMail): bool
    {
        $strQuery = "SELECT last_name FROM user WHERE email = :email;";
        $strPrepare = $this->_db->prepare($strQuery);
        $strPrepare->bindValue(":email", $strMail, PDO::PARAM_STR);
        $result = $this->queryExecuter($strPrepare);

        return $result !== false && is_array($result->fetch());
    }

    public function getByMail(string $strMail)
    {
        $strQuery = "SELECT id, password, first_name, last_name, email, username, role, is_private, date_of_birth, country_id, gendre, hobbies, bio, last_login
                 FROM user
                 WHERE email = :email;";
        $strPrepare = $this->_db->prepare($strQuery);
        $strPrepare->bindValue(":email", $strMail, PDO::PARAM_STR);
        $result = $this->queryExecuter($strPrepare);

        return $result !== false ? $result->fetch() : false;
    }

    public function updateLastConnection(string $strId)
    {
        $strQuery = "UPDATE user SET last_login = NOW() WHERE id = :id;";
        $strPrepare = $this->_db->prepare($strQuery);
        $strPrepare->bindValue(":id", $strId, PDO::PARAM_STR);

        return $this->queryExecuter($strPrepare);
    }

    public function updateUser($objUser): bool
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

        return $this->queryExecuter($strPrepare) !== false;
    }

    public function getPermissions(int $userId): array|bool
    {
        $strQuery = "SELECT role FROM user WHERE id = :id;";
        $strPrepare = $this->_db->prepare($strQuery);
        $strPrepare->bindValue(":id", $userId, PDO::PARAM_INT);
        $result = $this->queryExecuter($strPrepare);

        return $result !== false ? $result->fetch() : false;
    }

    public function banUser(string $email): bool|string
    {
        $strQuery = "SELECT is_banned FROM user WHERE email = :email;";
        $strPrepare = $this->_db->prepare($strQuery);
        $strPrepare->bindValue(":email", $email, PDO::PARAM_STR);
        $result = $this->queryExecuter($strPrepare);

        if (!$result || ($userStatus = $result->fetch()) === false) {
            return "user_not_found";
        }

        if ($userStatus['is_banned'] == 1) {
            return "already_banned";
        }

        $strQuery = "UPDATE user SET is_banned = 1 WHERE email = :email;";
        $strPrepare = $this->_db->prepare($strQuery);
        $strPrepare->bindValue(":email", $email, PDO::PARAM_STR);

        return $this->queryExecuter($strPrepare) && $strPrepare->rowCount() > 0 ? true : "error_banning_user";
    }


    public function makeAdmin(string $email): bool|string
    {
        $strQuery = "SELECT role FROM user WHERE email = :email;";
        $strPrepare = $this->_db->prepare($strQuery);
        $strPrepare->bindValue(":email", $email, PDO::PARAM_STR);
        $result = $this->queryExecuter($strPrepare);

        if (!$result || ($userRole = $result->fetch()) === false) {
            return "user_not_found";
        }

        if ($userRole['role'] === 'administrator') {
            return "already_admin";
        }

        $strQuery = "UPDATE user SET role = 'administrator' WHERE email = :email;";
        $strPrepare = $this->_db->prepare($strQuery);
        $strPrepare->bindValue(":email", $email, PDO::PARAM_STR);

        return $this->queryExecuter($strPrepare) && $strPrepare->rowCount() > 0 ? true : "error_making_admin";
    }

    public function makeUser(string $email): bool|string
    {
        $strQuery = "SELECT role FROM user WHERE email = :email;";
        $strPrepare = $this->_db->prepare($strQuery);
        $strPrepare->bindValue(":email", $email, PDO::PARAM_STR);
        $result = $this->queryExecuter($strPrepare);

        if (!$result || ($userRole = $result->fetch()) === false) {
            return "user_not_found";
        }

        if ($userRole['role'] === 'user') {
            return "already_user";
        }

        $strQuery = "UPDATE user SET role = 'user' WHERE email = :email;";
        $strPrepare = $this->_db->prepare($strQuery);
        $strPrepare->bindValue(":email", $email, PDO::PARAM_STR);

        return $this->queryExecuter($strPrepare) && $strPrepare->rowCount() > 0 ? true : "error_making_user";
    }

    public function unbanUser(string $email): bool|string
    {
        $strQuery = "SELECT is_banned FROM user WHERE email = :email;";
        $strPrepare = $this->_db->prepare($strQuery);
        $strPrepare->bindValue(":email", $email, PDO::PARAM_STR);
        $result = $this->queryExecuter($strPrepare);

        if (!$result || ($userStatus = $result->fetch()) === false) {
            return "user_not_found";
        }

        if ($userStatus['is_banned'] == 0) {
            return "already_unbanned";
        }

        $strQuery = "UPDATE user SET is_banned = 0 WHERE email = :email;";
        $strPrepare = $this->_db->prepare($strQuery);
        $strPrepare->bindValue(":email", $email, PDO::PARAM_STR);

        return $this->queryExecuter($strPrepare) && $strPrepare->rowCount() > 0 ? true : "error_unbanning_user";
    }
    public function getAllUsers(): array|bool
    {
        $strQuery = "SELECT id, username, first_name, last_name, email, role, is_banned, is_active, is_private, last_login FROM user;";
        $strPrepare = $this->_db->prepare($strQuery);
        $result = $this->queryExecuter($strPrepare);

        return $result !== false ? $result->fetchAll() : false;
    }
}
