<?php

namespace App\Controllers;

use App\Models\Artwork;

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
