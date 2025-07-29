<?php

namespace App\Models;

use PDO;

class Transaction
{
    private $con;

    public function __construct($db)
    {
        $this->con = $db;
    }

    public function getOwnedNFTWithPrice($nftID, $userID)
    {
        $stmt = $this->con->prepare("
            SELECT a.Price 
            FROM nfts
            JOIN artworks a ON nfts.Artwork_ID = a.Artwork_ID 
            WHERE nfts.NFT_ID = ? AND nfts.Owner_User_ID = ?
            LIMIT 1
        ");
        $stmt->execute([$nftID, $userID]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function createTransaction($amount, $senderID, $receiverID, $nftID)
    {
        $stmt = $this->con->prepare("
            INSERT INTO `transactions` (Amount, Sender, Receiver, NFT_ID) 
            VALUES (?, ?, ?, ?)
        ");
        if ($stmt->execute([$amount, $senderID, $receiverID, $nftID])) {
            return true;
        }
        return false;
    }

    public function markNFTAsPaid($nftID)
    {
        $stmt = $this->con->prepare("UPDATE nfts SET Is_Paid = 1 WHERE NFT_ID = ?");
        if ($stmt->execute([$nftID])) {;
            return true;
        }
        return false;
    }
}
