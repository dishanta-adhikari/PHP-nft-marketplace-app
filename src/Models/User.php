<?php

namespace App\Models;

use PDO;

class User
{
    private $con;

    public function __construct($db)
    {
        $this->con = $db;
    }

    public function create(array $values)
    {
        $stmt = $this->con->prepare("INSERT INTO users (Name, Email, DOB, Role, Password) VALUES (?, ?, ?, ?, ?)");
        if ($stmt->execute([$values['Name'], $values['Email'], $values['DOB'], $values['Role'], $values['Password']])) {
            return true;
        }
        return false;
    }

    public function getByEmail($email)
    {
        $stmt = $this->con->prepare("SELECT * FROM users WHERE Email = ?");
        if ($stmt->execute([$email])) {
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }
        return false;
    }
}
