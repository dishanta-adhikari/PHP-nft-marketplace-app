<?php

namespace App\Controllers;

use App\Models\User;
use Exception;

class LoginController
{
    private $User;

    public function __construct($db)
    {
        $this->User = new User($db);
    }

    public function login(array $values)
    {
        try {
            if (
                empty($values['email']) ||
                empty($values['password'])
            ) {
                throw new Exception('Required Fields are Empty !');
            }

            $email    = trim($values['email'] ?? '');
            $password = trim($values['password'] ?? '');
            $isAdmin  = isset($values['is_admin']);

            $user = $this->User->getByEmail($email);

            if (!$user) {
                throw new Exception('User Not Found !');
            }

            if (!password_verify($password, $user['Password'])) {
                throw new Exception('Invalid Password !');
            }

            if ($isAdmin && $user['Role'] !== 'admin') {
                throw new Exception('You are not authorized as an admin !');
            }

            session_regenerate_id(true);

            $_SESSION['user_id'] = $user['User_ID'];
            $_SESSION['role'] = $user['Role'];
            $_SESSION['user_name'] = $user['Name'];

            if ($user['Role'] !== 'admin') {
                $_SESSION['success'] = 'Welcome ' . $user['Name'];
                header("Location: " . APP_URL);
            } else {
                $_SESSION['success'] = 'Welcome ' . $user['Name'];
                header("Location: " . APP_URL . "/admin/dashboard");
            }

            exit;
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            header('Location: ' . APP_URL . '/login');
            exit;
        }
    }
}
