<?php
include("db.php");

class User_Model extends Bdd
{
    public function createUser($objUser)
    {
        $strQuery = "	INSERT INTO user (first_name, last_name, username, gendre, email, hashed_password, date_of_birth, creation_date, last_connection) 
                                VALUES (:name, :firstname, :mail, :pwd);";
        $strPrepare = $this->_db->prepare($strQuery);
        $strPrepare->bindValue(":name", $objUser->getName(), PDO::PARAM_STR);
        $strPrepare->bindValue(":firstname", $objUser->getFirstname(), PDO::PARAM_STR);
        $strPrepare->bindValue(":mail", $objUser->getMail(), PDO::PARAM_STR);
        $strPrepare->bindValue(":pwd", $objUser->getHashedPwd(), PDO::PARAM_STR);
        return $this->queryExecuter($strPrepare);
    }

    public function verifMail(string $strMail): bool
    {
        $strQuery = "	SELECT user_name
	                            FROM users
                                WHERE user_mail = :mail;";
        $strPrepare = $this->_db->prepare($strQuery);
        $strPrepare->bindValue(":mail", $strMail, PDO::PARAM_STR);
        $strPrepare = $this->queryExecuter($strPrepare);
        if ($strPrepare !== false) {
            return is_array($strPrepare->fetch());
        }
        return false;
    }

    public function getByMail(string $strMail): array|bool
    {
        $strQuery        = "	SELECT user_pwd, user_name, user_firstname
	                            FROM users
                                WHERE user_mail = :mail;";
        $strPrepare     = $this->_db->prepare($strQuery);
        $strPrepare->bindValue(":mail", $strMail, PDO::PARAM_STR);
        $strPrepare->execute();

        return $strPrepare->fetch();
    }
}
