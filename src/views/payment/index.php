<?php
require_once __DIR__ . '/../../_init_.php';

use App\Controllers\TransactionController;

header('Content-Type: application/json');

$userID = $_SESSION['user_id'] ?? null;
$nftID = $_POST['nft_id'] ?? null;

$controller = new TransactionController($con);
$response = $controller->handlePayment($nftID, $userID);

echo json_encode($response);
