<?php

class Experience_Ctrl extends Ctrl
{
    public function lifeExperience()
    {
        include("models/experience_model.php");
        $objExperienceModel = new Experience_Model();
        $experiences = $objExperienceModel->getPublicExperiences();
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

            if (empty($_POST['title'])) {
                $arrErrors['title'] = "Please enter a title.";
            }
            if (empty($_POST['content'])) {
                $arrErrors['content'] = "Please enter the content.";
            }

            if (count($arrErrors) == 0) {
                $boolInsert = $objExperienceModel->createExperience($objExperience);
                if ($boolInsert) {
                    $_SESSION['valid'] = "Your experience has been shared successfully.";
                    header("Location:index.php?Controller=experience&Action=lifeExperience");
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
}
