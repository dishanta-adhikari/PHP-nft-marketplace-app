<?php

namespace App\Models;

use PDO;
use App\Helpers\Paginator;

class Artwork
{
    private PDO $con;

    public function __construct(PDO $db)
    {
        $this->con = $db;
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
