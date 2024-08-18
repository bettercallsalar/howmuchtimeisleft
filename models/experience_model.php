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
        $strQuery = "
            SELECT 
                ue.*, 
                (SELECT COUNT(*) FROM comments WHERE experience_id = ue.id) as comments, 
                (SELECT COUNT(*) FROM likes WHERE experience_id = ue.id) as likes,
                ue.view_count
            FROM 
                user_experience ue 
            WHERE 
                ue.is_public = 1 
                AND ue.is_deleted = 0 
            ORDER BY 
                ue.created_at DESC;
        ";

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

    public function getExperience($experienceId)
    {
        $strQuery = "
            SELECT 
                ue.*, 
                (SELECT COUNT(*) FROM comments WHERE experience_id = ue.id) as comments, 
                (SELECT COUNT(*) FROM likes WHERE experience_id = ue.id) as likes,
                ue.view_count
            FROM 
                user_experience ue 
            WHERE 
                ue.id = :experience_id 
                AND ue.is_public = 1 
                AND ue.is_deleted = 0;
        ";

        $strPrepare = $this->_db->prepare($strQuery);
        $strPrepare->bindValue(":experience_id", $experienceId, PDO::PARAM_INT);
        $strPrepare->execute();

        return $strPrepare->fetch(PDO::FETCH_ASSOC);
    }

    public function incrementViewCount($experienceId)
    {
        $strQuery = "UPDATE user_experience SET view_count = view_count + 1 WHERE id = :experience_id";
        $strPrepare = $this->_db->prepare($strQuery);
        $strPrepare->bindParam(':experience_id', $experienceId, PDO::PARAM_INT);
        $strPrepare->execute();
    }

    public function toggleLikeDislike($experienceId, $userId)
    {
        // Check if the user has already liked the experience
        $strQuery = "SELECT COUNT(*) FROM likes WHERE experience_id = :experience_id AND user_id = :user_id";
        $strPrepare = $this->_db->prepare($strQuery);
        $strPrepare->bindParam(':experience_id', $experienceId, PDO::PARAM_INT);
        $strPrepare->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $strPrepare->execute();
        $likeExists = $strPrepare->fetchColumn();

        if ($likeExists) {
            // If the like exists, remove it (dislike)
            $strQuery = "DELETE FROM likes WHERE experience_id = :experience_id AND user_id = :user_id";
            $strPrepare = $this->_db->prepare($strQuery);
        } else {
            // If the like does not exist, add it (like)
            $strQuery = "INSERT INTO likes (experience_id, user_id) VALUES (:experience_id, :user_id)";
            $strPrepare = $this->_db->prepare($strQuery);
        }

        // Execute the query
        $strPrepare->bindParam(':experience_id', $experienceId, PDO::PARAM_INT);
        $strPrepare->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $strPrepare->execute();
    }
    public function hasUserLiked($experienceId, $userId)
    {
        $strQuery = "SELECT COUNT(*) FROM likes WHERE experience_id = :experience_id AND user_id = :user_id";
        $strPrepare = $this->_db->prepare($strQuery);
        $strPrepare->bindParam(':experience_id', $experienceId, PDO::PARAM_INT);
        $strPrepare->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $strPrepare->execute();

        return $strPrepare->fetchColumn() > 0;
    }
    public function createComment(Comment $comment)
    {
        $strQuery = "INSERT INTO comments (experience_id, user_id, comment, created_at) VALUES (:experience_id, :user_id, :comment, NOW())";
        $strPrepare = $this->_db->prepare($strQuery);
        $strPrepare->bindParam(':experience_id', $comment->getExperienceId(), PDO::PARAM_INT);
        $strPrepare->bindParam(':user_id', $comment->getUserId(), PDO::PARAM_INT);
        $strPrepare->bindParam(':comment', $comment->getComment(), PDO::PARAM_STR);

        return $strPrepare->execute();
    }

    public function getComments($experienceId)
    {
        $strQuery = "SELECT c.*, u.username FROM comments c JOIN user u ON c.user_id = u.id WHERE c.experience_id = :experience_id ORDER BY c.created_at ASC";
        $strPrepare = $this->_db->prepare($strQuery);
        $strPrepare->bindParam(':experience_id', $experienceId, PDO::PARAM_INT);
        $strPrepare->execute();

        return $strPrepare->fetchAll(PDO::FETCH_ASSOC);
    }
}
