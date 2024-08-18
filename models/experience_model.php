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
    public function updateLastExperience($userId)
    {
        $strQuery = "UPDATE user SET last_experience = NOW() WHERE id = :user_id;";
        $strPrepare = $this->_db->prepare($strQuery);
        $strPrepare->bindValue(":user_id", $userId, PDO::PARAM_INT);

        return $this->queryExecuter($strPrepare);
    }

    public function getLastExperience($userId)
    {
        $strQuery = "SELECT last_experience FROM user WHERE id = :user_id;";
        $strPrepare = $this->_db->prepare($strQuery);
        $strPrepare->bindValue(":user_id", $userId, PDO::PARAM_INT);
        $strPrepare->execute();

        return $strPrepare->fetchColumn();
    }

    public function getPublicExperiences()
    {
        $strQuery = "
            SELECT 
                ue.*, 
                (SELECT COUNT(*) FROM comments WHERE experience_id = ue.id) as comments, 
                (SELECT COUNT(*) FROM likes WHERE experience_id = ue.id) as likes,
                (SELECT COUNT(*) FROM views WHERE experience_id = ue.id) as views
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




    public function getSortedExperiences($sort)
    {
        $orderClause = "ue.created_at DESC"; // Default sorting (most recent)

        switch ($sort) {
            case 'oldest':
                $orderClause = "ue.created_at ASC";
                break;
            case 'most_liked':
                $orderClause = "(SELECT COUNT(*) FROM likes WHERE experience_id = ue.id) DESC";
                break;
            case 'most_viewed':
                $orderClause = "(SELECT COUNT(*) FROM views WHERE experience_id = ue.id) DESC";
                break;
            case 'most_commented':
                $orderClause = "(SELECT COUNT(*) FROM comments WHERE experience_id = ue.id) DESC";
                break;
        }

        $strQuery = "
            SELECT 
                ue.*, 
                (SELECT COUNT(*) FROM comments WHERE experience_id = ue.id) as comments, 
                (SELECT COUNT(*) FROM likes WHERE experience_id = ue.id) as likes,
                (SELECT COUNT(*) FROM views WHERE experience_id = ue.id) as views
            FROM 
                user_experience ue 
            WHERE 
                ue.is_public = 1 
                AND ue.is_deleted = 0 
            ORDER BY 
                $orderClause;
        ";

        $strPrepare = $this->_db->prepare($strQuery);
        $strPrepare->execute();

        return $strPrepare->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getExperience($experienceId)
    {
        $strQuery = "
            SELECT 
                ue.*, 
                u.username, 
                TIMESTAMPDIFF(YEAR, u.date_of_birth, CURDATE()) AS age,
                c.country_name as country_name,
                (SELECT COUNT(*) FROM comments WHERE experience_id = ue.id) as comments, 
                (SELECT COUNT(*) FROM likes WHERE experience_id = ue.id) as likes,
                (SELECT COUNT(*) FROM views WHERE experience_id = ue.id) as views
            FROM 
                user_experience ue 
            JOIN 
                user u ON ue.user_id = u.id
            JOIN 
                country c ON u.country_id = c.id
            WHERE 
                ue.id = :experience_id 
                AND ue.is_public = 1 
                AND ue.is_deleted = 0;
        ";

        $strPrepare = $this->_db->prepare($strQuery);
        $strPrepare->bindValue(":experience_id", $experienceId, PDO::PARAM_INT);
        $strPrepare->execute();

        $experience = $strPrepare->fetch(PDO::FETCH_ASSOC);


        $this->incrementViewCount($experienceId, $_SESSION['user']['id']);

        return $experience;
    }


    public function incrementViewCount($experienceId, $userId)
    {
        // Check if the user has already viewed this experience
        $strQuery = "SELECT COUNT(*) FROM views WHERE experience_id = :experience_id AND user_id = :user_id";
        $strPrepare = $this->_db->prepare($strQuery);
        $strPrepare->bindParam(':experience_id', $experienceId, PDO::PARAM_INT);
        $strPrepare->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $strPrepare->execute();

        if ($strPrepare->fetchColumn() == 0) {
            // If the user hasn't viewed the experience, insert a new record in the views table
            $strQuery = "INSERT INTO views (experience_id, user_id, viewed_at) VALUES (:experience_id, :user_id, NOW())";
            $strPrepare = $this->_db->prepare($strQuery);
            $strPrepare->bindParam(':experience_id', $experienceId, PDO::PARAM_INT);
            $strPrepare->bindParam(':user_id', $userId, PDO::PARAM_INT);
            $strPrepare->execute();
        }
    }

    public function getViewCount($experienceId)
    {
        $strQuery = "SELECT COUNT(*) FROM views WHERE experience_id = :experience_id";
        $strPrepare = $this->_db->prepare($strQuery);
        $strPrepare->bindParam(':experience_id', $experienceId, PDO::PARAM_INT);
        $strPrepare->execute();

        return $strPrepare->fetchColumn();
    }
    public function toggleLikeDislike($experienceId, $userId)
    {
        $strQuery = "SELECT COUNT(*) FROM likes WHERE experience_id = :experience_id AND user_id = :user_id";
        $strPrepare = $this->_db->prepare($strQuery);
        $strPrepare->bindParam(':experience_id', $experienceId, PDO::PARAM_INT);
        $strPrepare->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $strPrepare->execute();
        $likeExists = $strPrepare->fetchColumn();

        if ($likeExists) {
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
    public function hasUserCommented($experienceId, $userId)
    {
        $strQuery = "SELECT COUNT(*) FROM comments WHERE experience_id = :experience_id AND user_id = :user_id";
        $strPrepare = $this->_db->prepare($strQuery);
        $strPrepare->bindParam(':experience_id', $experienceId, PDO::PARAM_INT);
        $strPrepare->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $strPrepare->execute();

        return $strPrepare->fetchColumn() > 0;
    }
    public function hasUserViewed($experienceId, $userId)
    {
        $strQuery = "SELECT COUNT(*) FROM views WHERE experience_id = :experience_id AND user_id = :user_id";
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
        $strQuery = "
            SELECT 
                c.*, 
                u.username, 
                u.date_of_birth, 
                TIMESTAMPDIFF(YEAR, u.date_of_birth, CURDATE()) AS age,
                co.country_name
            FROM 
                comments c 
            JOIN 
                user u ON c.user_id = u.id 
            JOIN 
                country co ON u.country_id = co.id
            WHERE 
                c.experience_id = :experience_id 
            ORDER BY 
                c.created_at ASC";

        $strPrepare = $this->_db->prepare($strQuery);
        $strPrepare->bindParam(':experience_id', $experienceId, PDO::PARAM_INT);
        $strPrepare->execute();

        return $strPrepare->fetchAll(PDO::FETCH_ASSOC);
    }



    public function updateExperience($objExperience)
    {
        $strQuery = "UPDATE user_experience SET title = :title, content = :content, updated_at = NOW() WHERE id = :experience_id";
        $strPrepare = $this->_db->prepare($strQuery);
        $strPrepare->bindParam(':title', $objExperience->getTitle(), PDO::PARAM_STR);
        $strPrepare->bindParam(':content', $objExperience->getContent(), PDO::PARAM_STR);
        $strPrepare->bindParam(':experience_id', $objExperience->getId(), PDO::PARAM_INT);

        return $strPrepare->execute();
    }

    public function deleteExperience($experienceId)
    {
        $strQuery = "UPDATE user_experience SET is_deleted = 1 WHERE id = :experience_id";
        $strPrepare = $this->_db->prepare($strQuery);
        $strPrepare->bindParam(':experience_id', $experienceId, PDO::PARAM_INT);

        return $strPrepare->execute();
    }

    public function deleteComment($commentId)
    {
        $strQuery = "DELETE FROM comments WHERE id = :comment_id";
        $strPrepare = $this->_db->prepare($strQuery);
        $strPrepare->bindParam(':comment_id', $commentId, PDO::PARAM_INT);

        return $strPrepare->execute();
    }
}
