<?php

class Admin_Ctrl extends Ctrl
{
    public function dashboard()
    {
        $this->renderPage('admin_dashboard', "Admin Dashboard", "Admin Dashboard");
    }

    private function checkPermission(): bool
    {
        include_once 'models/user_model.php';
        $this->_arrData['userModel'] = new User_Model();

        if (!isset($_SESSION['user']) || !$_SESSION['user']['id']) {
            $_SESSION['error'] = "You do not have permission to edit this profile.";
            return false;
        }

        $userRole = $this->_arrData['userModel']->getPermissions($_SESSION['user']['id']);
        if ($userRole !== false && $userRole['role'] !== 'administrator') {
            $_SESSION['error'] = "You do not have permission to do this. Please contact the administrator.";
            return false;
        }

        return true;
    }


    public function getAllUsers()
    {
        if (!$this->checkPermission()) {
            $this->renderPage('admin_dashboard', "Admin Dashboard", "Admin Dashboard");
            return;
        }

        include_once 'models/user_model.php';
        $userModel = new User_Model();
        $users = $userModel->getAllUsers();

        // Pass the users to the view
        $this->_arrData['users'] = $users;

        // Render the dashboard with the users data
        $this->renderPage('admin_dashboard', "Admin Dashboard", "Admin Dashboard", $this->_arrData);
    }

    public function processUserAction()
    {
        if (!$this->checkPermission()) {
            header("Location:index.php?Controller=admin&Action=dashboard");
            exit;
        }

        include_once 'models/user_model.php';
        $email = $_POST['email'];
        $action = $_POST['action'];
        $userModel = new User_Model();

        // Mapping actions to model methods and error messages
        $resultMap = [
            'makeAdmin' => [
                'method' => 'makeAdmin',
                'success' => 'User has been made an admin.',
                'already' => 'User is already an admin.',
                'not_found' => 'User not found.'
            ],
            'makeUser' => [
                'method' => 'makeUser',
                'success' => 'User has been made a user.',
                'already' => 'User is already a user.',
                'not_found' => 'User not found.'
            ],
            'banUser' => [
                'method' => 'banUser',
                'success' => 'User has been banned.',
                'already' => 'User is already banned.',
                'not_found' => 'User not found.'
            ],
            'unbanUser' => [
                'method' => 'unbanUser',
                'success' => 'User has been unbanned.',
                'already' => 'User is already unbanned.',
                'not_found' => 'User not found.'
            ],
        ];

        if (isset($resultMap[$action])) {
            $result = $userModel->{$resultMap[$action]['method']}($email);

            if ($result === true) {
                $_SESSION['valid'] = $resultMap[$action]['success'];
            } elseif ($result === "already_banned" || $result === "already_admin" || $result === "already_user" || $result === "already_unbanned") {
                $_SESSION['error'] = $resultMap[$action]['already'];
            } else {
                $_SESSION['error'] = $resultMap[$action]['not_found'];
            }
        }

        $users = $userModel->getAllUsers();
        $this->_arrData['users'] = $users;

        $this->renderPage('admin_dashboard', "Admin Dashboard", "Admin Dashboard", $this->_arrData);
    }
}
