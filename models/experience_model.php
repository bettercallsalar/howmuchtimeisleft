<?php

include_once("db.php");

class Experience_Model extends Db
{
    public function createExperience($objExperience)
    {
        $strQuery = "INSERT INTO user_experience (user_id, title, content, created_at, updated_at, is_public, is_deleted) 
                     VALUES (:user_id, :title, :content, NOW(), NOW(), 1, 0);";

        $strPrepare = $this->_db->prepare($strQuery);
        $strPrepare->bindValue(":user_id", $objExperience->getUserId(), PDO::PARAM_INT);
        $strPrepare->bindValue(":title", $objExperience->getTitle(), PDO::PARAM_STR);
        $strPrepare->bindValue(":content", $objExperience->getContent(), PDO::PARAM_STR);

        return $this->queryExecuter($strPrepare);
    }

    public function getPublicExperiences()
    {
        $strQuery = "SELECT * FROM user_experience WHERE is_public = 1 AND is_deleted = 0 ORDER BY created_at DESC;";
        $strPrepare = $this->_db->prepare($strQuery);
        $strPrepare->execute();

        return $strPrepare->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUserExperiences($userId)
    {
        $strQuery = "SELECT * FROM user_experience WHERE user_id = :user_id AND is_deleted = 0 ORDER BY created_at DESC;";
        $strPrepare = $this->_db->prepare($strQuery);
        $strPrepare->bindValue(":user_id", $userId, PDO::PARAM_INT);
        $strPrepare->execute();

        return $strPrepare->fetchAll(PDO::FETCH_ASSOC);
    }
}
