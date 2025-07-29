<?php

namespace App\Controllers;

use App\Models\User;
use Exception;

class UserController
{
    private $User;

    public function __construct($db)
    {
        $this->User = new User($db);
    }

    public function getAll()
    {
        try {
            $result =  $this->User->getAll();

            if (!$result) {
                throw new Exception('Failed to Get User Data.');
            }

            return $result;
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            return;
        }
    }

    public function getById($id)
    {
        try {
            $result =  $this->User->getById($id);

            if (!$result) {
                throw new Exception('Failed to Get User');
            }

            return $result;
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            return;
        }
    }

    public function manageUsers()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_id'], $_POST['new_role'])) {
            $this->User->updateRole($_POST['user_id'], $_POST['new_role']);
            $_SESSION['success'] = "updated successfully.";
            header("Location: " . APP_URL . "/admin/update-role");
            exit();
        }

        $users = $this->User->getAllExcept($_SESSION['user_id']);
        return $users;
    }
}
