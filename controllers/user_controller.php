<?php

class User_Ctrl extends Ctrl
{

    public function createAccount()
    {
        $this->loadModels();

        $arrErrors = array();

        if ($_POST) {
            include_once("entities/user_entity.php");
            $objUser = new User();
            $objUser->hydrate($_POST);
            $this->_arrData['objUser'] = $objUser;

            $arrErrors = $this->validateUserInput($objUser, $_POST);

            if (empty($arrErrors)) {
                $boolInsert = $this->_arrData['userModel']->createUser($objUser);
                if ($boolInsert) {
                    $_SESSION['valid'] = "Your account has been created successfully. You can now log in.";
                    header("Location:index.php?Controller=user&Action=login");
                    exit;
                } else {
                    $arrErrors['database'] = "An error occurred while creating your account. Please try again.";
                }
            }

            $this->displayErrors($arrErrors);
        }

        $this->renderPage('create_account', "Create Account", "Create your account");
    }
    protected function validatePassword($password)
    {
        $errors = [];

        error_log("Validating Password: " . $password);

        if (strlen($password) < 8) {
            $errors[] = "Password must be at least 8 characters long.";
        }

        if (!preg_match('/[A-Z]/', $password)) {
            $errors[] = "Password must include at least one uppercase letter.";
        }

        if (!preg_match('/[a-z]/', $password)) {
            $errors[] = "Password must include at least one lowercase letter.";
        }

        if (!preg_match('/\d/', $password)) {
            $errors[] = "Password must include at least one number.";
        }

        if (!preg_match('/[@$!%*?&]/', $password)) {
            $errors[] = "Password must include at least one special character.";
        } else {
            error_log("Special character found in password.");
        }

        error_log("Password Validation Errors: " . print_r($errors, true));

        return $errors;
    }


    public function login()
    {
        $this->loadModels();
        $arrErrors = array();

        if ($_POST) {
            $strEmail = trim($_POST['email']);
            $strPassword = $_POST['password'];

            if (empty($strEmail) || empty($strPassword)) {
                $arrErrors[] = "Email and password are required.";
            } else {
                $arrUser = $this->_arrData['userModel']->getByMail($strEmail);

                if (!$arrUser || !password_verify($strPassword, $arrUser['password'])) {
                    $arrErrors[] = "Incorrect email or password. Please try again.";
                } else {
                    unset($arrUser['password']);
                    $_SESSION['user'] = $arrUser;
                    $_SESSION['valid'] = "You are now connected.";
                    $this->_arrData['userModel']->updateLastConnection($arrUser['id']);
                    header("Location:index.php");
                    exit;
                }
            }

            if (!empty($arrErrors)) {
                $this->displayErrors($arrErrors);
            }
        }

        $this->renderPage('login', "Login", "Login page");
    }

    public function logout()
    {
        session_destroy();
        session_start();
        $_SESSION['valid'] = "You have successfully logged out.";
        header("Location:index.php");
    }

    public function editProfile()
    {

        $this->checkUserSession();
        $this->loadModels();

        $userId = $_SESSION['user']['id'];
        $userData = $_SESSION['user'];

        include_once("entities/user_entity.php");
        $objUser = new User();
        $objUser->hydrate($userData);
        $this->_arrData['objUser'] = $objUser;

        if ($_POST) {
            $objUser->hydrate($_POST);
            $arrErrors = $this->validateUserInput($objUser, $_POST, $userData['email']);

            if (empty($arrErrors)) {
                $boolUpdate = $this->_arrData['userModel']->updateUser($objUser, $userId);
                if ($boolUpdate) {
                    $_SESSION['user'] = $this->updateSessionData($objUser);
                    $_SESSION['valid'] = "Your profile has been updated successfully.";
                    header("Location:index.php?Controller=user&Action=editProfile");
                    exit;
                } else {
                    $arrErrors['database'] = "An error occurred while updating your profile. Please try again.";
                }
            }

            $this->displayErrors($arrErrors);
        }

        $this->renderPage('edit_profile', "Edit Profile", "Update your profile information.");
    }

    private function loadModels()
    {
        include_once("models/country_model.php");
        $objCountryModel = new Country_Model();
        $this->_arrData['countries'] = $objCountryModel->getAllCountries();

        include_once("models/user_model.php");
        $this->_arrData['userModel'] = new User_Model();
    }

    private function checkUserSession()
    {
        if (!isset($_SESSION['user'])) {
            $_SESSION['error'] = "Sorry, You must be logged in to edit your profile.";
            header("Location:index.php?Controller=user&Action=login");
            exit;
        }
    }

    private function validateUserInput($objUser, $postData, $existingEmail = null)
    {
        $arrErrors = [];

        if (empty($postData['first_name'])) {
            $arrErrors['first_name'] = "Please enter your first name.";
        }
        if (empty($postData['last_name'])) {
            $arrErrors['last_name'] = "Please enter your last name.";
        }
        if (empty($postData['gendre'])) {
            $arrErrors['gendre'] = "Please enter your gender.";
        }
        if (empty($postData['email'])) {
            $arrErrors['email'] = "Email is required.";
        } elseif (!filter_var($objUser->getEmail(), FILTER_VALIDATE_EMAIL)) {
            $arrErrors['email'] = "Invalid email format.";
        } elseif ($this->_arrData['userModel']->verifMail($objUser->getEmail()) !== false && $objUser->getEmail() !== $existingEmail) {
            $arrErrors['email'] = "Email already exists.";
        }
        if (empty($postData['username'])) {
            $arrErrors['username'] = "Please enter your username.";
        }
        if (empty($postData['date_of_birth'])) {
            $arrErrors['date_of_birth'] = "Please enter your date of birth.";
        }
        if (empty($postData['country_id'])) {
            $arrErrors['country_id'] = "Please select your country.";
        } else {
            $objUser->setCountryId((int)$postData['country_id']);
        }

        $passwordErrors = $this->validatePassword($postData['password']);

        error_log("Password Validation Errors: " . print_r($passwordErrors, true));

        if (!empty($passwordErrors)) {
            $arrErrors['password'] = $passwordErrors;
        }

        return $arrErrors;
    }


    private function updateSessionData($objUser)
    {
        return [
            'id' => $objUser->getId(),
            'first_name' => $objUser->getFirstName(),
            'last_name' => $objUser->getLastName(),
            'email' => $objUser->getEmail(),
            'username' => $objUser->getUsername(),
            'country_id' => $objUser->getCountryId(),
            'gendre' => $objUser->getGendre(),
            'hobbies' => $objUser->getHobbies(),
            'bio' => $objUser->getBio(),
            'is_private' => $objUser->getIsPrivate(),
            'date_of_birth' => $objUser->getDateOfBirth(),
            'role' => $_SESSION['user']['role']
        ];
    }

    private function displayErrors($arrErrors)
    {
        if (!empty($arrErrors)) {
            echo "<div class='alert alert-danger'>";
            foreach ($arrErrors as $key => $strError) {
                if (is_array($strError)) {
                    foreach ($strError as $error) {
                        echo "<p><strong>" . ucfirst($key) . " Error:</strong> " . $error . "</p>";
                    }
                } else {
                    echo "<p>" . $strError . "</p>";
                }
            }
            echo "</div>";
        }
    }

    public function getUserById(int $userId)
    {
        $this->loadModels();

        if ($userId <= 0) {
            $this->_arrData['errors'][] = "Invalid user ID provided.";
            return false;
        }

        $userData = $this->_arrData['userModel']->getUserById($userId);
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
            $this->_arrData['errors'][] = "Could not retrieve the user information.";
            return false;
        }
    }
}
