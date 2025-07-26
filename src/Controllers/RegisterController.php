<?php

namespace App\Controllers;

use App\Models\User;
use Exception;

class RegisterController
{
    private $User;

    public function __construct($db)
    {
        $this->User = new User($db);
    }

    public function register(array $values)
    {
        try {
            if (
                empty($values['name']) ||
                empty($values['email']) ||
                empty($values['dob']) ||
                empty($values['password']) ||
                empty($values['confirm_password'])
            ) {
                throw new Exception('Required Fields are Empty !');
            }

            $name             = trim($values['name']);
            $email            = trim($values['email']);
            $dob              = trim($values['dob']);
            $password         = trim($values['password']);
            $confirm_password = trim($values['confirm_password']);

            if ($this->User->getByEmail($email)) {
                throw new Exception('User Already Exists!');
            }

            if ($confirm_password !== $password) {
                throw new Exception("Confirm password Should be same as Password !");
            }

            if (strlen($password) < 8) {
                throw new Exception("Password must be at least 8 characters long.");
            }

            if (
                !preg_match('/[A-Z]/', $password) ||
                !preg_match('/[a-z]/', $password) ||
                !preg_match('/[0-9]/', $password)
            ) {
                throw new Exception("Password must contain at least one uppercase letter, one lowercase letter, and one number !");
            }

            $password_Hash = password_hash($password, PASSWORD_DEFAULT);

            $values = [
                'Name' => $name,
                'Email' => $email,
                'DOB' => $dob,
                'Role' => 'user',
                'Password' => $password_Hash
            ];

            $created = $this->User->create($values);

            if (!$created) {
                throw new Exception('Failed to Create User !');
            }

            $user = $this->User->getByEmail($email);

            session_regenerate_id(true);

            $_SESSION['user_id'] = $user['User_ID'];
            $_SESSION['role'] = $user['Role'];
            $_SESSION['user_name'] = $user['Name'];

            $_SESSION['success'] = 'Welcome ' . $user['Name'];
            header("Location: " . APP_URL);
            exit;
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            header('Location: ' . APP_URL . '/register');
            exit;
        }
    }
}
