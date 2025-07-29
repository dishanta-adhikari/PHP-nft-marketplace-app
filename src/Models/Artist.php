<?php

namespace App\Models;

use PDO;

class Artist
{
    private $con;

    public function __construct($db)
    {
        $this->con = $db;
    }

    public function fetchAll()
    {
        $stmt = $this->con->prepare("SELECT * FROM artists");
        if ($stmt->execute()) {
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        return false;
    }

    public function getAll()
    {
        $stmt = $this->con->query("SELECT * FROM artists ORDER BY Artist_ID DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function emailExists($email, $excludeId = null)
    {
        if ($excludeId) {
            $stmt = $this->con->prepare("SELECT COUNT(*) FROM artists WHERE Email = ? AND Artist_ID != ?");
            $stmt->execute([$email, $excludeId]);
        } else {
            $stmt = $this->con->prepare("SELECT COUNT(*) FROM artists WHERE Email = ?");
            $stmt->execute([$email]);
        }

        return $stmt->fetchColumn() > 0;
    }

    public function create($name, $email)
    {
        $stmt = $this->con->prepare("INSERT INTO artists (Name, Email) VALUES (?, ?)");
        return $stmt->execute([$name, $email]);
    }

    public function update($id, $name, $email)
    {
        $stmt = $this->con->prepare("UPDATE artists SET Name = ?, Email = ? WHERE Artist_ID = ?");
        return $stmt->execute([$name, $email, $id]);
    }

    public function delete($id)
    {
        $stmt = $this->con->prepare("DELETE FROM artists WHERE Artist_ID = ?");
        return $stmt->execute([$id]);
    }
}
