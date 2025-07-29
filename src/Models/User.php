<?php

namespace App\Models;

use PDO;

class User
{
    private $con;   //$con is the data variable

    public function __construct($db)    //connect to database using constructor
    {
        $this->con = $db;
    }

    public function create(array $values)   //create new user
    {
        $stmt = $this->con->prepare("INSERT INTO users (Name, Email, DOB, Role, Password) VALUES (?, ?, ?, ?, ?)");
        if ($stmt->execute([$values['Name'], $values['Email'], $values['DOB'], $values['Role'], $values['Password']])) {
            return [
                'user_id' => $this->con->lastInsertId() ?? '',
                'name' => $values['Name'],
                'role' => $values['Role']
            ];
        }
        return false;
    }

    public function getByEmail($email)  //get user by email
    {
        $stmt = $this->con->prepare("SELECT * FROM users WHERE Email = ?");
        if ($stmt->execute([$email])) {
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }
        return false;
    }

    public function getById($id)    //get user by id
    {
        $stmt = $this->con->prepare("SELECT * FROM users WHERE User_ID = ?");
        if ($stmt->execute([$id])) {
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }
        return false;
    }

    public function getAll()    //get all users
    {
        $stmt = $this->con->prepare("SELECT * FROM users");
        if ($stmt->execute()) {
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        return false;
    }

    public function getAllExcept($userId)
    {
        $stmt = $this->con->prepare("SELECT user_id, name, email, role FROM users WHERE user_id != ?");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateRole($userId, $newRole)
    {
        $stmt = $this->con->prepare("UPDATE users SET role = ? WHERE user_id = ?");
        return $stmt->execute([$newRole, $userId]);
    }
}
