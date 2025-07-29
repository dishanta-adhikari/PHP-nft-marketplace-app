<?php

namespace App\Controllers;

use App\Models\Artwork;
use Exception;

class ArtworkController
{
    private $Artwork;

    public function __construct($db)
    {
        $this->Artwork = new Artwork($db);
    }

    public function index(): void
    {
        $search = $_GET['search'] ?? '';
        $page   = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit  = 6;

        $results    = $this->Artwork->paginate($search, $page, $limit);
        $artworks   = $results->data;
        $totalPages = $results->pages;

        $data = [
            'artworks'   => $artworks,
            'page'       => $results->page,
            'totalPages' => $totalPages,
            'limit'      => $results->limit,
            'total'      => $results->total,
            'search'     => $search,
        ];

        $this->render('artwork/index', $data);
    }

    public function show($id)
    {
        $artwork = $this->Artwork->findById($id);

        if (!$artwork) {
            $_SESSION['error'] = "Artwork not found.";
            header('Location: ' . APP_URL . '/404.php');
            exit;
        }

        return $artwork;
    }

    public function create($values)
    {
        try {
            if (    // Check required fields
                empty($values['title']) ||
                empty($values['price']) ||
                empty($values['description']) ||
                empty($values['artist_id'])
            ) {
                throw new Exception('Required fields are empty!');
            }

            $title       = trim($values['title']);
            $price       = trim($values['price']);
            $description = trim($values['description']);
            $artist_id   = trim($values['artist_id']);
            $photo_path  = '';

            if (    // image upload error check
                empty($values['photo']) ||
                $values['photo']['error'] !== 0
            ) {
                throw new Exception('Invalid or missing image file!');
            }

            $target_dir = __DIR__ . "/../../public/uploads/images/";    // Upload directory (filesystem path)

            if (!is_dir($target_dir)) {
                mkdir($target_dir, 0777, true);
            }

            $file_ext   = pathinfo($values['photo']['name'], PATHINFO_EXTENSION);      // Generate a unique filename
            $file_name  = uniqid('art_', true) . '.' . $file_ext;
            $photo_path = $file_name;
            $full_path  = $target_dir . $file_name;

            if (!move_uploaded_file($values["photo"]["tmp_name"], $full_path)) {
                throw new Exception('Failed to store image!');
            }

            $data = [   // Build final data array
                'title'       => $title,
                'price'       => $price,
                'photo_path'  => $photo_path,
                'description' => $description,
                'artist_id'   => $artist_id
            ];

            $created = $this->Artwork->create($data);   // Insert into DB

            if (!$created) {
                throw new Exception('Failed to create the artwork!');
            }

            $_SESSION['success'] = 'Artwork created successfully.';
            header('Location: ' . APP_URL . '/artwork');
            exit;
        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
            header('Location: ' . APP_URL . '/artwork/create');
            exit;
        }
    }

    public function listArtworks($searchTerm, $statusFilter, $page = 1, $itemsPerPage = 6)
    {
        $offset = ($page - 1) * $itemsPerPage;

        $artworks = $this->Artwork->searchArtworks($searchTerm, $statusFilter, $itemsPerPage, $offset);
        $totalItems = $this->Artwork->countArtworks($searchTerm, $statusFilter);
        $totalPages = ceil($totalItems / $itemsPerPage);
        $artists = $this->Artwork->getAllArtists();

        return compact('artworks', 'totalItems', 'totalPages', 'artists');
    }

    public function updateStatus($data)
    {
        return $this->Artwork->toggleStatus($data['artwork_id'], $data['current_status']);
    }

    public function updateArtwork($postData, $files)
    {
        $photoPath = null;

        if (isset($files['photo']) && $files['photo']['error'] === UPLOAD_ERR_OK) {
            $targetDir = __DIR__ . '/../../public/uploads/images/';

            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0777, true);
            }

            $fileExt   = pathinfo($files['photo']['name'], PATHINFO_EXTENSION);
            $fileName  = uniqid('art_', true) . '.' . $fileExt;
            $photoPath = $fileName; // Just the filename, not full path
            $fullPath  = $targetDir . $fileName;

            if (!move_uploaded_file($files['photo']['tmp_name'], $fullPath)) {
                $_SESSION['error'] = 'Failed to upload image.';
                return false;
            }
        }

        return $this->Artwork->updateArtwork($postData, $photoPath);
    }



    private function render(string $view, array $data = []): void
    {
        extract($data);
        $APP_URL = defined('APP_URL') ? APP_URL : '';
        include __DIR__ . '/../../views/' . $view . '.php';
    }

    public function getPaginatedArtworks(string $search = '', int $page = 1, int $limit = 6): array
    {
        $results    = $this->Artwork->paginate($search, $page, $limit);
        $artworks   = $results->data;
        $totalPages = $results->pages;

        return [
            'artworks'   => $artworks,
            'page'       => $results->page,
            'totalPages' => $totalPages,
            'limit'      => $results->limit,
            'total'      => $results->total,
            'search'     => $search,
        ];
    }
}
