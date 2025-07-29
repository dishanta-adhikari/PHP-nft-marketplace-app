<?php

namespace App\Controllers;

use App\Models\NFT;

class NFTController
{
    private NFT $nft;

    public function __construct($db)
    {
        $this->nft = new NFT($db);
    }

    public function mint()
    {
        if (!isset($_SESSION['user_id'])) {
            echo "<script>window.location.href = '" . APP_URL . "/login';</script>";
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['artwork_id'])) {
            echo "<script>window.location.href = '" . APP_URL . "';</script>";
            exit;
        }

        $userId = $_SESSION['user_id'];
        $artworkId = (int) $_POST['artwork_id'];

        if ($this->nft->userOwnsArtwork($userId, $artworkId)) {
            $_SESSION['error'] = "You already own this NFT.";
        } else {
            if ($this->nft->mint($userId, $artworkId)) {
                $_SESSION['success'] = "NFT minted successfully!";
            } else {
                $_SESSION['error'] = "Something went wrong. Try again.";
            }
        }

        echo "<script>window.location.href = '" . APP_URL . "/user/dashboard';</script>";
        exit;
    }


    public function getUserNFTs(int $userId, int $page = 1, int $limit = 6): array
    {
        $paginator = $this->nft->getUserNFTs($userId, $page, $limit);

        return [
            'nfts'       => $paginator->data,
            'page'       => $paginator->page,
            'totalPages' => $paginator->pages,
            'limit'      => $paginator->limit,
            'total'      => $paginator->total
        ];
    }
}
