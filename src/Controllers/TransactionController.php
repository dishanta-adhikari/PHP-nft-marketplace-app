<?php

namespace App\Controllers;

use App\Models\Transaction;
use PDO;

class TransactionController
{
    private $model;

    public function __construct($con)
    {
        $this->model = new Transaction($con);
    }

    public function handlePayment($nftID, $userID)
    {
        if (!$nftID || !$userID) {
            return ['success' => false, 'message' => 'Missing user or NFT ID'];
        }

        $nft = $this->model->getOwnedNFTWithPrice($nftID, $userID);
        
        if (!$nft) {
            return ['success' => false, 'message' => 'NFT not found or not owned'];
        }

        $price = $nft['Price'];

        $this->model->createTransaction($price, $userID, 0, $nftID);
        $this->model->markNFTAsPaid($nftID);

        return ['success' => true];
    }
}
