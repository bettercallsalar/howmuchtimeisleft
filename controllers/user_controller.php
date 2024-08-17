<?php

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
            include("entities/user_entity.php");
            $objUser = new User();
            $objUser->hydrate($_POST);
            $this->_arrData['objUser'] = $objUser;
            echo var_dump("Controller", $objUser);

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
            }

            if (count($arrErrors) == 0) {
                $boolInsert = $objUserModel->createUser($objUser);
                if ($boolInsert) {
                    $_SESSION['valid'] = "Le compte a bien été créé, vous pouvez vous connecter.";
                    header("Location:index.php?Controller=user&Action=login");
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
        $this->_arrData['strTitleH1'] = "Create Account";
        $this->_arrData['strFirstP'] = "Create your account";

        $this->render('create_account');
    }
    public function login()
    {
        include("models/user_model.php");
        $objUserModel = new User_Model();
        $arrErrors = array();

        if (count($_POST) > 0) {
            $strEmail = trim($_POST['email']);
            $strPassword = $_POST['password'];

            if (empty($strEmail) || empty($strPassword)) {
                $arrErrors[] = "Email and password are required.";
            }

            if (count($arrErrors) == 0) {
                $arrUser = $objUserModel->getByMail($strEmail);
                echo var_dump($arrUser);
                if ($arrUser === false) {
                    $arrErrors[] = "Error of connexion. Please try again.";
                } else {
                    if (password_verify($strPassword, $arrUser['password'])) {
                        unset($arrUser['password']);
                        $_SESSION['user'] = $arrUser;
                        $_SESSION['valid'] = "You are now connected.";
                        $objUserModel->updateLastConnection($arrUser['id']);
                        header("Location:index.php");
                        exit;
                    } else {
                        $arrErrors[] = "Error of connexion. Please try again.";
                    }
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


        $this->_arrData['strPage'] = "login";
        $this->_arrData['strTitleH1'] = "Login";
        $this->_arrData['strFirstP'] = "Login page";

        $this->render('login');
    }

    public function logout()
    {
        session_destroy();
        session_start();
        $_SESSION['valid'] = "Vous êtes bien déconnecté";
        header("Location:index.php");
    }

    public function editProfile()
    {
        if (!isset($_SESSION['user'])) {
            $_SESSION['error'] = "Sorry, You must be logged in to edit your profile.";
            header("Location:index.php?Controller=user&Action=login");
            exit;
        }

        include("models/country_model.php");
        $objCountryModel = new Country_Model();
        $countries = $objCountryModel->getAllCountries();
        $this->_arrData['countries'] = $countries;

        include("models/user_model.php");
        $objUserModel = new User_Model();
        $arrErrors = array();

        include("entities/user_entity.php");
        $objUser = new User();
        $userId = $_SESSION['user']['id'];

        $userData = $objUserModel->getUserById($userId);
        $objUser->hydrate($userData);
        $this->_arrData['objUser'] = $objUser;
        if (count($_POST) > 0) {
            $objUser->hydrate($_POST);

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
                $arrErrors['email'] = "Email is required.";
            } elseif (!filter_var($objUser->getEmail(), FILTER_VALIDATE_EMAIL)) {
                $arrErrors['email'] = "Invalid email format.";
            } elseif ($objUserModel->verifMail($objUser->getEmail()) !== false && $objUser->getEmail() !== $userData['email']) {
                $arrErrors['email'] = "Email already exists.";
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


            if (count($arrErrors) == 0) {
                $boolUpdate = $objUserModel->updateUser($objUser, $userId);
                if ($boolUpdate) {
                    $_SESSION['valid'] = "Your profile has been updated successfully.";
                    header("Location:index.php?Controller=user&Action=editProfile");
                    exit;
                } else {
                    $arrErrors['database'] = "An error occurred while updating your profile. Please try again.";
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

        $this->_arrData['strPage'] = "edit_profile";
        $this->_arrData['strTitleH1'] = "Edit Profile";
        $this->_arrData['strFirstP'] = "Update your profile information.";

        $this->render('edit_profile');
    }
    public function getUserById(int $userId): array|bool
    {
        include("models/user_model.php");
        $objUserModel = new User_Model();
        $arrErrors = array();

        if ($userId <= 0) {
            $arrErrors[] = "Invalid user ID provided.";
            return false;
        }

        $userData = $objUserModel->getUserById($userId);
        if ($userData !== false) {
            return [
                'date_of_birth' => $userData['date_of_birth'],
                'country_id' => $userData['country_id'],
                'gendre' => $userData['gendre'],
                'hobbies' => $userData['hobbies'],
                'bio' => $userData['bio'],
                'is_private' => $userData['is_private']
            ];
        } else {
            $arrErrors[] = "Could not retrieve the user information.";
            $this->_arrData['errors'] = $arrErrors;
            return false;
        }
    }
}
