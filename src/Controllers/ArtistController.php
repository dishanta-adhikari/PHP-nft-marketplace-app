<?php

namespace App\Controllers;

use App\Models\Artist;
use Exception;

class ArtistController
{
    private $artist;

    public function __construct($db)
    {
        $this->artist = new Artist($db);
    }

    public function fetchAll()
    {
        try {
            $result = $this->artist->fetchAll();
            if (!$result) {
                throw new Exception('Failed to Fetch Artist Data.');
            }
            return $result;
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            header('Location:' . APP_URL . '/admin/artist');
            exit;
        }
    }

    public function sanitize($data)
    {
        return htmlspecialchars(trim($data));
    }

    public function handleRequest()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['create_artist'])) {
                $this->create();
            } elseif (isset($_POST['update_artist'])) {
                $this->update();
            } elseif (isset($_POST['delete_artist'])) {
                $this->delete();
            }
        }

        return $this->artist->getAll();
    }

    private function create()
    {
        $name = $this->sanitize($_POST['name'] ?? '');
        $email = $this->sanitize($_POST['email'] ?? '');

        if ($name && $email && !$this->artist->emailExists($email)) {
            $this->artist->create($name, $email);
        }
    }

    private function update()
    {
        $id = (int) ($_POST['artist_id'] ?? 0);
        $name = $this->sanitize($_POST['name'] ?? '');
        $email = $this->sanitize($_POST['email'] ?? '');

        if ($id && $name && $email && !$this->artist->emailExists($email, $id)) {
            $this->artist->update($id, $name, $email);
        }
    }

    private function delete()
    {
        $id = (int) ($_POST['artist_id'] ?? 0);
        if ($id) {
            $this->artist->delete($id);
        }
    }
}
