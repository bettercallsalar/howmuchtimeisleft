<?php

/**
 * Controller des utilisateurs
 */

class User_Ctrl extends Ctrl
{
    public function createAccount()
    {
        include("models/country_model.php");
        $objCountryModel = new Country_Model();
        $countries = $objCountryModel->getAllCountries();
        $this->_arrData['countries'] = $countries;

        include("models/user_model.php");
        $objUserModel = new User_Model();
        $arrErrors = array();

        if (count($_POST) > 0) {
            echo var_dump($_POST);
            include("entities/user_entity.php");
            $objUser = new User();
            $objUser->hydrate($_POST);
            $this->_arrData['objUser'] = $objUser;
            echo "test";

            if (empty($_POST['first_name'])) {
                $arrErrors['first_name'] = "Please enter your first name.";
            }
            if (empty($_POST['last_name'])) {
                $arrErrors['last_name'] = "Please enter your last name.";
            }
            if (empty($_POST['gendre'])) {
                $arrErrors['gendre'] = "Please enter your gender.";
            }
            if (empty($_POST['email'])) {
                $arrErrors['email'] = "Le mail est obligatoire";
            } elseif (!filter_var($objUser->getEmail(), FILTER_VALIDATE_EMAIL)) {
                $arrErrors['email'] = "Le mail n'est pas correct";
            } elseif ($objUserModel->verifMail($objUser->getEmail()) !== false) {
                $arrErrors['email'] = "Le mail existe déjà";
            }
            if (empty($_POST['username'])) {
                $arrErrors['username'] = "Please enter your username.";
            }
            if (empty($_POST['date_of_birth'])) {
                $arrErrors['date_of_birth'] = "Please enter your date of birth.";
            }
            if (empty($_POST['country_id'])) {
                $arrErrors['country_id'] = "Please select your country.";
            } else {
                // Cast country_id to integer
                $objUser->setCountryId((int)$_POST['country_id']);
            }

            $password = $_POST['password'];
            $confirmPassword = $_POST['confirmPassword'];
            $regex = '#^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[\W_]).{16,20}$#';

            if (empty($password)) {
                $arrErrors['password'] = "Le mot de passe est obligatoire";
            } elseif (!preg_match($regex, $password)) {
                $arrErrors['password'] = "Le mot de passe n'est pas correct";
            } elseif ($password !== $confirmPassword) {
                $arrErrors['password'] = "Le mot de passe et sa confirmation sont incorrect";
            } else {
                // Set the hashed password
                $objUser->setPassword(password_hash($password, PASSWORD_DEFAULT));
            }

            if (count($arrErrors) == 0) {
                $boolInsert = $objUserModel->createUser($objUser);
                if ($boolInsert) {
                    $_SESSION['valid'] = "Le compte a bien été créé, vous pouvez vous connecter.";
                    exit;
                } else {
                    $arrErrors['database'] = "An error occurred while creating your account. Please try again.";
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

        $this->_arrData['strPage'] = "create_account";
        $this->_arrData['strTitleH1'] = "Créer un compte";
        $this->_arrData['strFirstP'] = "Page de création de compte";

        $this->render('create_account');
    }
}
