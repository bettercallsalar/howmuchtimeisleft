<?php

class Experience_Ctrl extends Ctrl
{
    public function lifeExperience()
    {
        include("models/experience_model.php");
        $objExperienceModel = new Experience_Model();
        $experiences = $objExperienceModel->getPublicExperiences();

        if (isset($_SESSION['user'])) {
            foreach ($experiences as &$experience) {
                $experience['hasLiked'] = $objExperienceModel->hasUserLiked($experience['id'], $_SESSION['user']['id']);
                $experience['hasCommented'] = $objExperienceModel->hasUserCommented($experience['id'], $_SESSION['user']['id']);
                $experience['hasViewed'] = $objExperienceModel->hasUserViewed($experience['id'], $_SESSION['user']['id']);
            }
        }

        $this->_arrData['experiences'] = $experiences;
        $this->_arrData['strPage'] = "life_experiences";
        $this->_arrData['strTitleH1'] = "Life Experiences";
        $this->_arrData['strFirstP'] = "Explore the experiences shared by others.";

        $this->render('life_experiences');
    }

    public function createExperience()
    {
        include("models/experience_model.php");
        $objExperienceModel = new Experience_Model();
        $arrErrors = array();

        if (count($_POST) > 0) {
            include("entities/experience_entity.php");
            $objExperience = new Experience();
            $objExperience->hydrate($_POST);
            $objExperience->setUserId($_SESSION['user']['id']);

            // Check if the user has shared an experience within the last 24 hours
            $checkLastExperience = $objExperienceModel->getLastExperience($_SESSION['user']['id']);
            if ($checkLastExperience) {
                $lastExperience = strtotime($checkLastExperience);
                $currentTime = time();
                $timeDifference = $currentTime - $lastExperience;
                if ($timeDifference < 86400) { // 86400 seconds in 24 hours
                    $_SESSION['error'] = "You can only share one experience per day. Please try again later.";
                    header("Location: index.php?Controller=experience&Action=lifeExperience");
                    exit;
                }
            }

            // Validate the input
            if (empty($_POST['title'])) {
                $arrErrors['title'] = "Please enter a title.";
            }
            if (empty($_POST['content'])) {
                $arrErrors['content'] = "Please enter the content.";
            }

            if (count($arrErrors) == 0) {
                // Update the last experience timestamp
                $updateLastExperience = $objExperienceModel->updateLastExperience($_SESSION['user']['id']);
                if (!$updateLastExperience) {
                    $arrErrors['database'] = "An error occurred while updating your last experience. Please try again.";
                    $_SESSION['error'] = $arrErrors['database'];
                    header("Location: index.php?Controller=experience&Action=lifeExperience");
                    exit;
                }

                // Insert the new experience
                $boolInsert = $objExperienceModel->createExperience($objExperience);
                if ($boolInsert) {
                    $_SESSION['valid'] = "Your experience has been shared successfully.";
                    header("Location: index.php?Controller=experience&Action=lifeExperience");
                    exit;
                } else {
                    $arrErrors['database'] = "An error occurred while sharing your experience. Please try again.";
                }
            }
        }

        if (count($arrErrors) > 0) {
            echo "<div class='alert alert-danger'>";
            foreach ($arrErrors as $strError) {
                echo "<p>" . $strError . "</p>";
            }
            echo "</div>";
        }

        $this->_arrData['strPage'] = "create_experience";
        $this->_arrData['strTitleH1'] = "Share Your Experience";
        $this->_arrData['strFirstP'] = "Tell us about your life experiences.";

        $this->render('create_experience');
    }

    public function readMore()
    {
        include("models/experience_model.php");
        $objExperienceModel = new Experience_Model();

        $experienceId = $_GET['experience_id'] ?? 0;
        $experience = $objExperienceModel->getExperience($experienceId);

        if (!$experience) {
            $_SESSION['error'] = "Experience not found or has been removed.";
            header("Location: index.php?Controller=experience&Action=lifeExperience");
            exit;
        }

        // Increment view count, but only if the user is logged in and has not viewed this experience before
        if (isset($_SESSION['user']) && !$objExperienceModel->hasUserViewed($experienceId, $_SESSION['user']['id'])) {
            $objExperienceModel->incrementViewCount($experienceId, $_SESSION['user']['id']);
        }

        // Get updated view count
        $experience['view_count'] = $objExperienceModel->getViewCount($experienceId);
        $this->_arrData['experience'] = $experience;

        // Check if the user has liked, commented, or viewed this experience
        $hasLiked = $objExperienceModel->hasUserLiked($experienceId, $_SESSION['user']['id']);
        $hasCommented = $objExperienceModel->hasUserCommented($experienceId, $_SESSION['user']['id']);
        $hasViewed = $objExperienceModel->hasUserViewed($experienceId, $_SESSION['user']['id']);
        $this->_arrData['hasLiked'] = $hasLiked;
        $this->_arrData['hasCommented'] = $hasCommented;
        $this->_arrData['hasViewed'] = $hasViewed;

        // Check if the user is editing the experience
        $this->_arrData['isEditMode'] = isset($_GET['Action']) && $_GET['Action'] == 'editExperience';

        $this->experienceDetails();
    }

    public function experienceDetails()
    {
        $this->_arrData['strPage'] = "experience_details";
        $this->_arrData['strTitleH1'] = "Experience Details";
        $this->_arrData['strFirstP'] = "Read more about the experience shared by the user.";

        $this->render('experience_details');
    }

    public function likeAndDislikeExperience()
    {
        include("models/experience_model.php");
        $objExperienceModel = new Experience_Model();

        // Retrieve experience ID and user ID from GET parameters
        $experienceId = $_GET['experience_id'] ?? 0;
        $userId = $_GET['user_id'] ?? 0;

        // Ensure both experience ID and user ID are valid
        if ($experienceId > 0 && $userId > 0) {
            // Toggle like/dislike
            $objExperienceModel->toggleLikeDislike($experienceId, $userId);
        }

        header("Location: index.php?Controller=experience&Action=readMore&experience_id=" . $experienceId);
        exit;
    }

    public function getCommentOfExperience()
    {
        include("models/experience_model.php");
        $objExperienceModel = new Experience_Model();

        $experienceId = $_GET['experience_id'] ?? 0;
        $experience = $objExperienceModel->getExperience($experienceId);

        if (!$experience) {
            $_SESSION['error'] = "Experience not found or has been removed.";
            header("Location: index.php?Controller=experience&Action=lifeExperience");
            exit;
        }

        $comments = $objExperienceModel->getComments($experienceId);

        $this->_arrData['experience'] = $experience;
        $this->_arrData['comments'] = $comments;

        $hasLiked = isset($_SESSION['user']) ? $objExperienceModel->hasUserLiked($experienceId, $_SESSION['user']['id']) : false;
        $hasCommented = isset($_SESSION['user']) ? $objExperienceModel->hasUserCommented($experienceId, $_SESSION['user']['id']) : false;
        $hasViewed = isset($_SESSION['user']) ? $objExperienceModel->hasUserViewed($experienceId, $_SESSION['user']['id']) : false;
        $this->_arrData['hasLiked'] = $hasLiked;
        $this->_arrData['hasCommented'] = $hasCommented;
        $this->_arrData['hasViewed'] = $hasViewed;

        $this->experienceDetails();
    }

    public function writeComment()
    {
        include("models/experience_model.php");
        $objExperienceModel = new Experience_Model();
        $arrErrors = array();

        if (count($_POST) > 0) {
            include("entities/comment_entity.php");
            $objComment = new Comment();
            $objComment->hydrate($_POST);
            $objComment->setUserId($_SESSION['user']['id']);
            $objComment->setExperienceId($_POST['experience_id']);

            if (empty($_POST['comment'])) {
                $arrErrors['comment'] = "Please enter a comment.";
            }

            if (count($arrErrors) == 0) {
                $boolInsert = $objExperienceModel->createComment($objComment);
                if ($boolInsert) {
                    $_SESSION['valid'] = "Your comment has been shared successfully.";
                } else {
                    $arrErrors['database'] = "An error occurred while sharing your comment. Please try again.";
                }
            }

            if (count($arrErrors) > 0) {
                echo "<div class='alert alert-danger'>";
                foreach ($arrErrors as $strError) {
                    echo "<p>" . $strError . "</p>";
                }
                echo "</div>";
            }
        }

        header("Location:index.php?Controller=experience&Action=getCommentOfExperience&experience_id=" . $_POST['experience_id']);
        exit;
    }

    public function editExperience()
    {
        include("models/experience_model.php");
        $objExperienceModel = new Experience_Model();
        $arrErrors = array();

        $experienceId = $_GET['experience_id'] ?? 0;
        $experience = $objExperienceModel->getExperience($experienceId);

        if (!$experience) {
            $_SESSION['error'] = "Experience not found or has been removed.";
            header("Location: index.php?Controller=experience&Action=lifeExperience");
            exit;
        }

        if (count($_POST) > 0) {
            include("entities/experience_entity.php");
            $objExperience = new Experience();
            $objExperience->hydrate($_POST);
            $objExperience->setId($experienceId);

            // Validate the input
            if (empty($_POST['title'])) {
                $arrErrors['title'] = "Please enter a title.";
            }
            if (empty($_POST['content'])) {
                $arrErrors['content'] = "Please enter the content.";
            }

            if (count($arrErrors) == 0) {
                $boolUpdate = $objExperienceModel->updateExperience($objExperience);
                if ($boolUpdate) {
                    $_SESSION['valid'] = "Your experience has been updated successfully.";
                    header("Location: index.php?Controller=experience&Action=readMore&experience_id=" . $experienceId);
                    exit;
                } else {
                    $arrErrors['database'] = "An error occurred while updating your experience. Please try again.";
                }
            }
        }

        if (count($arrErrors) > 0) {
            echo "<div class='alert alert-danger'>";
            foreach ($arrErrors as $strError) {
                echo "<p>" . $strError . "</p>";
            }
            echo "</div>";
        }

        $this->_arrData['experience'] = $experience;
        $this->_arrData['strPage'] = "edit_experience";
        $this->_arrData['strTitleH1'] = "Edit Experience";
        $this->_arrData['strFirstP'] = "Update your experience information.";

        $this->render('edit_experience');
    }

    public function deleteExperience()
    {
        include("models/experience_model.php");
        $objExperienceModel = new Experience_Model();

        $experienceId = $_GET['experience_id'] ?? 0;
        $boolDelete = $objExperienceModel->deleteExperience($experienceId);

        if ($boolDelete) {
            $_SESSION['valid'] = "Experience has been deleted successfully.";
        } else {
            $_SESSION['error'] = "An error occurred while deleting the experience. Please try again.";
        }

        header("Location: index.php?Controller=experience&Action=lifeExperience");
        exit;
    }
}
