<?php

namespace App\Models;

use PDO;
use App\Helpers\Paginator;

class NFT
{
    private $con;

    public function __construct($db)
    {
        $this->con = $db;
    }

    public function userOwnsArtwork($userId, $artworkId): bool
    {
        $stmt = $this->con->prepare("SELECT 1 FROM nfts WHERE Owner_User_ID = ? AND Artwork_ID = ?");
        $stmt->execute([$userId, $artworkId]);
        return (bool)$stmt->fetch();
    }

    public function mint($userId, $artworkId): bool
    {
        $token = bin2hex(random_bytes(16));
        $stmt = $this->con->prepare("INSERT INTO nfts (Owner_User_ID, Artwork_ID, token) VALUES (?, ?, ?)");
        return $stmt->execute([$userId, $artworkId, $token]);
    }

    public function getUserNFTs(int $userId, int $page = 1, int $limit = 6): Paginator
    {
        $page   = max(1, $page);
        $limit  = max(1, $limit);
        $offset = ($page - 1) * $limit;

        $countStmt = $this->con->prepare("
            SELECT COUNT(*)
            FROM nfts n
            JOIN artworks a ON n.Artwork_ID = a.Artwork_ID
            WHERE n.Owner_User_ID = :userId
        ");
        $countStmt->execute([':userId' => $userId]);
        $total = (int) $countStmt->fetchColumn();

        $stmt = $this->con->prepare("
            SELECT n.NFT_ID, a.Title, a.Photo, a.Price, n.Is_Paid, n.token
            FROM nfts n
            JOIN artworks a ON n.Artwork_ID = a.Artwork_ID
            WHERE n.Owner_User_ID = :userId
            ORDER BY n.NFT_ID DESC
            LIMIT :limit OFFSET :offset
        ");

        $stmt->bindValue(':userId', $userId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return new Paginator($data, $total, $page, $limit);
    }
}
