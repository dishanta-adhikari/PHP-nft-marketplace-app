<?php

namespace App\Models;

use PDO;
use App\Helpers\Paginator;

class Artwork
{
    private $con;

    public function __construct($db)
    {
        $this->con = $db;
    }

    public function findById($id)
    {
        $stmt = $this->con->prepare("
            SELECT a.*, ar.Name AS ArtistName 
            FROM artworks a 
            JOIN artists ar ON a.Artist_ID = ar.Artist_ID 
            WHERE a.Artwork_ID = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($values)
    {
        $stmt = $this->con->prepare("INSERT INTO artworks (Title, Price, Photo, Description, Artist_ID) VALUES (?, ?, ?, ?, ?)");
        if ($stmt->execute([$values['title'], $values['price'], $values['photo_path'], $values['description'], $values['artist_id']])) {
            return true;
        }
        return false;
    }

    public function searchArtworks($searchTerm, $statusFilter, $limit, $offset)
    {
        $query = "SELECT a.*, ar.Name AS ArtistName, COALESCE(nfts.Is_Paid, 0) AS Is_Paid
                  FROM artworks a
                  JOIN artists ar ON a.Artist_ID = ar.Artist_ID
                  LEFT JOIN nfts ON a.Artwork_ID = nfts.Artwork_ID
                  WHERE 1";
        $params = [];

        if (!empty($searchTerm)) {
            $query .= " AND (a.Title LIKE ? OR a.Description LIKE ? OR ar.Name LIKE ?)";
            $params = array_fill(0, 3, "%$searchTerm%");
        }

        if ($statusFilter === 'active') {
            $query .= " AND a.Status = 'active'";
        } elseif ($statusFilter === 'disabled') {
            $query .= " AND a.Status = 'disabled'";
        }

        $query .= " ORDER BY a.Created_At DESC LIMIT $limit OFFSET $offset";
        $stmt = $this->con->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function countArtworks($searchTerm, $statusFilter)
    {
        $query = "SELECT COUNT(*) FROM artworks a
                  JOIN artists ar ON a.Artist_ID = ar.Artist_ID
                  LEFT JOIN nfts ON a.Artwork_ID = nfts.Artwork_ID
                  WHERE 1";
        $params = [];

        if (!empty($searchTerm)) {
            $query .= " AND (a.Title LIKE ? OR a.Description LIKE ? OR ar.Name LIKE ?)";
            $params = array_fill(0, 3, "%$searchTerm%");
        }

        if ($statusFilter === 'active') {
            $query .= " AND a.Status = 'active'";
        } elseif ($statusFilter === 'disabled') {
            $query .= " AND a.Status = 'disabled'";
        }

        $stmt = $this->con->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchColumn();
    }

    public function updateArtwork($data, $photoPath = null)
    {
        if ($photoPath) {
            $stmt = $this->con->prepare("UPDATE artworks SET Title = ?, Description = ?, Price = ?, Photo = ?, Artist_ID = ? WHERE Artwork_ID = ?");
            return $stmt->execute([
                $data['title'],
                $data['description'],
                $data['price'],
                $photoPath,
                $data['artist_id'],
                $data['artwork_id']
            ]);
        } else {
            $stmt = $this->con->prepare("UPDATE artworks SET Title = ?, Description = ?, Price = ?, Artist_ID = ? WHERE Artwork_ID = ?");
            return $stmt->execute([
                $data['title'],
                $data['description'],
                $data['price'],
                $data['artist_id'],
                $data['artwork_id']
            ]);
        }
    }

    public function toggleStatus($artworkId, $currentStatus)
    {
        $newStatus = $currentStatus === 'active' ? 'disabled' : 'active';
        $stmt = $this->con->prepare("UPDATE artworks SET Status = ? WHERE Artwork_ID = ?");
        return $stmt->execute([$newStatus, $artworkId]);
    }

    public function getAllArtists()
    {
        $stmt = $this->con->query("SELECT Artist_ID, Name FROM artists ORDER BY Name ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function paginate(?string $search, int $page = 1, int $limit = 6): Paginator
    {
        $page   = max(1, $page);
        $limit  = max(1, $limit);
        $offset = ($page - 1) * $limit;

        $params = [];
        $where  = "a.Status = 'active'";

        if (!empty($search)) {
            $like = '%' . $search . '%';
            $where .= " AND (a.Title LIKE :q1 OR a.Description LIKE :q2 OR ar.Name LIKE :q3)";
            $params[':q1'] = $like;
            $params[':q2'] = $like;
            $params[':q3'] = $like;
        }

        $countSql = "
            SELECT COUNT(*) 
            FROM artworks a
            JOIN artists ar ON a.Artist_ID = ar.Artist_ID
            WHERE $where
        ";
        $stmt = $this->con->prepare($countSql);
        $stmt->execute($params);
        $totalRows = (int) $stmt->fetchColumn();

        $dataSql = "
            SELECT 
                a.*, 
                ar.Name AS ArtistName,
                EXISTS(
                    SELECT 1 FROM nfts n 
                    WHERE n.Artwork_ID = a.Artwork_ID AND n.Is_Paid = 1
                ) AS IsSold
            FROM artworks a
            JOIN artists ar ON a.Artist_ID = ar.Artist_ID
            WHERE $where
            ORDER BY a.Artwork_ID DESC
            LIMIT {$limit} OFFSET {$offset}
        ";

        $stmt = $this->con->prepare($dataSql);
        $stmt->execute($params);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return new Paginator($rows, $totalRows, $page, $limit);
    }

    public function find(int $id): ?array
    {
        $sql = "
            SELECT 
                a.*, 
                ar.Name AS ArtistName,
                EXISTS(
                    SELECT 1 FROM nfts n 
                    WHERE n.Artwork_ID = a.Artwork_ID AND n.Is_Paid = 1
                ) AS IsSold
            FROM artworks a
            JOIN artists ar ON a.Artist_ID = ar.Artist_ID
            WHERE a.Artwork_ID = :id
            LIMIT 1
        ";

        $stmt = $this->con->prepare($sql);
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ?: null;
    }
}
