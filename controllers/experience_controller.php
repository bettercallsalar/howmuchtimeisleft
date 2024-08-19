<?php

class Experience_Ctrl extends Ctrl
{
    private $objExperienceModel;

    public function __construct()
    {
        include("models/experience_model.php");
        $this->objExperienceModel = new Experience_Model();
    }

    private function loadExperienceData($experienceId)
    {
        $experience = $this->objExperienceModel->getExperience($experienceId);

        if (!$experience) {
            $_SESSION['error'] = "Experience not found or has been removed.";
            header("Location: index.php?Controller=experience&Action=lifeExperience");
            exit;
        }

        // Increment view count if the user is logged in and hasn't viewed it yet
        if (isset($_SESSION['user']) && !$this->objExperienceModel->hasUserViewed($experienceId, $_SESSION['user']['id'])) {
            $this->objExperienceModel->incrementViewCount($experienceId, $_SESSION['user']['id']);
        }

        // Prepare common data
        $experience['views'] = $this->objExperienceModel->getViewCount($experienceId);
        $this->_arrData['experience'] = $experience;
        $this->_arrData['hasLiked'] = isset($_SESSION['user']) ? $this->objExperienceModel->hasUserLiked($experienceId, $_SESSION['user']['id']) : false;
        $this->_arrData['hasCommented'] = isset($_SESSION['user']) ? $this->objExperienceModel->hasUserCommented($experienceId, $_SESSION['user']['id']) : false;
        $this->_arrData['hasViewed'] = isset($_SESSION['user']) ? $this->objExperienceModel->hasUserViewed($experienceId, $_SESSION['user']['id']) : false;
    }

    private function handleValidation($data)
    {
        $arrErrors = [];

        if (empty($data['title'])) {
            $arrErrors['title'] = "Please enter a title.";
        }
        if (empty($data['content'])) {
            $arrErrors['content'] = "Please enter the content.";
        }

        return $arrErrors;
    }

    private function renderForm($viewName, $arrErrors = [])
    {
        if (count($arrErrors) > 0) {
            echo "<div class='alert alert-danger'>";
            foreach ($arrErrors as $strError) {
                echo "<p>" . $strError . "</p>";
            }
            echo "</div>";
        }

        $this->render($viewName);
    }

    public function lifeExperience()
    {
        include_once("models/experience_model.php");
        $objExperienceModel = new Experience_Model();

        // Get sorting criteria from the GET request
        $sort = $_GET['sort'] ?? 'most_recent';

        // Fetch the sorted experiences based on the selected criteria
        $experiences = $objExperienceModel->getSortedExperiences($sort);

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
        if ($_POST) {
            include("entities/experience_entity.php");
            $objExperience = new Experience();
            $objExperience->hydrate($_POST);
            $objExperience->setUserId($_SESSION['user']['id']);

            // Check for errors
            $arrErrors = $this->handleValidation($_POST);
            if (count($arrErrors) == 0) {
                $checkLastExperience = $this->objExperienceModel->getLastExperience($_SESSION['user']['id']);
                if ($checkLastExperience && (time() - strtotime($checkLastExperience)) < 86400) {
                    $_SESSION['error'] = "You can only share one experience per day. Please try again later.";
                    header("Location: index.php?Controller=experience&Action=lifeExperience");
                    exit;
                }

                $boolInsert = $this->objExperienceModel->createExperience($objExperience);
                if ($boolInsert) {
                    $_SESSION['valid'] = "Your experience has been shared successfully.";
                    header("Location: index.php?Controller=experience&Action=lifeExperience");
                    exit;
                } else {
                    $arrErrors['database'] = "An error occurred while sharing your experience. Please try again.";
                }
            }

            $this->renderForm('create_experience', $arrErrors);
        }

        $this->_arrData['strPage'] = "create_experience";
        $this->_arrData['strTitleH1'] = "Share Your Experience";
        $this->_arrData['strFirstP'] = "Tell us about your life experiences.";

        $this->render('create_experience');
    }

    public function readMore()
    {
        $experienceId = $_GET['experience_id'] ?? 0;
        $this->loadExperienceData($experienceId);

        $this->_arrData['isEditMode'] = (isset($_GET['Action']) && $_GET['Action'] === 'editExperience');
        $this->getCommentOfExperience();
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
        $experienceId = $_GET['experience_id'] ?? 0;
        $userId = $_GET['user_id'] ?? 0;

        if ($experienceId > 0 && $userId > 0) {
            $this->objExperienceModel->toggleLikeDislike($experienceId, $userId);
        }

        header("Location: index.php?Controller=experience&Action=readMore&experience_id=" . $experienceId);
        exit;
    }

    public function getCommentOfExperience()
    {
        $experienceId = $_GET['experience_id'] ?? 0;
        $this->loadExperienceData($experienceId);

        $this->_arrData['comments'] = $this->objExperienceModel->getComments($experienceId);
        $this->_arrData['isEditMode'] = false;

        $this->experienceDetails();
    }

    public function writeComment()
    {
        if ($_POST) {
            include("entities/comment_entity.php");
            $objComment = new Comment();
            $objComment->hydrate($_POST);
            $objComment->setUserId($_SESSION['user']['id']);
            $objComment->setExperienceId($_POST['experience_id']);

            // Ensure comment field is not empty
            if (empty($_POST['comment'])) {
                $_SESSION['error'] = "Please enter a comment.";
                header("Location: index.php?Controller=experience&Action=getCommentOfExperience&experience_id=" . $_POST['experience_id']);
                exit;
            }

            // Insert the comment into the database
            if ($this->objExperienceModel->createComment($objComment)) {
                $_SESSION['valid'] = "Your comment has been shared successfully.";
            } else {
                $_SESSION['error'] = "An error occurred while sharing your comment. Please try again.";
            }

            header("Location: index.php?Controller=experience&Action=getCommentOfExperience&experience_id=" . $_POST['experience_id']);
            exit;
        }
    }

    public function editExperience()
    {
        $experienceId = $_GET['experience_id'] ?? 0;
        $this->loadExperienceData($experienceId);

        if ($_POST) {
            include("entities/experience_entity.php");
            $objExperience = new Experience();
            $objExperience->hydrate($_POST);
            $objExperience->setId($experienceId);

            $arrErrors = $this->handleValidation($_POST);
            if (count($arrErrors) == 0) {
                if ($this->objExperienceModel->updateExperience($objExperience)) {
                    $_SESSION['valid'] = "Your experience has been updated successfully.";
                    header("Location: index.php?Controller=experience&Action=readMore&experience_id=" . $experienceId);
                    exit;
                } else {
                    $arrErrors['database'] = "An error occurred while updating your experience. Please try again.";
                }
            }

            $this->renderForm('experience_details', $arrErrors);
        }

        $this->_arrData['isEditMode'] = true;
        $this->experienceDetails();
    }

    public function deleteExperience()
    {
        $experienceId = $_GET['experience_id'] ?? 0;
        if ($this->objExperienceModel->deleteExperience($experienceId)) {
            $_SESSION['valid'] = "Experience has been deleted successfully.";
        } else {
            $_SESSION['error'] = "An error occurred while deleting the experience. Please try again.";
        }

        header("Location: index.php?Controller=experience&Action=lifeExperience");
        exit;
    }
    public function deleteComment()
    {
        $commentId = $_GET['comment_id'] ?? 0;
        $experienceId = $_GET['experience_id'] ?? 0;

        if ($this->objExperienceModel->deleteComment($commentId)) {
            $_SESSION['valid'] = "Comment has been deleted successfully.";
        } else {
            $_SESSION['error'] = "An error occurred while deleting the comment. Please try again.";
        }

        header("Location: index.php?Controller=experience&Action=getCommentOfExperience&experience_id=" . $experienceId);
        exit;
    }
}
