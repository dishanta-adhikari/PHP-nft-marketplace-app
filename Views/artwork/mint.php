<?php
session_start();
require_once __DIR__ . "/../../App/App.php";
require_once __DIR__ . "/../../Config/Url.php";

$app = new App();
$conn = $app->connect();

if (!isset($_SESSION['user_id'])) {
    header("Location: " . VIEW_URL . "/auth/login");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['artwork_id'])) {
    $userID = $_SESSION['user_id'];
    $artworkID = (int)$_POST['artwork_id'];

    // Check if user already owns NFT of this artwork (optional)
    $stmt = $conn->prepare("SELECT * FROM nft WHERE Owner_User_ID = ? AND Artwork_ID = ?");
    $stmt->execute([$userID, $artworkID]);
    if ($stmt->fetch()) {
        $_SESSION['message'] = "You already own this NFT.";
        header("Location: " . VIEW_URL . "/user/dashboard");
        exit;
    }

    // Generate unique token
    $token = bin2hex(random_bytes(16)); // 32-character secure token

    // Insert NFT with token
    $stmt = $conn->prepare("INSERT INTO nft (Owner_User_ID, Artwork_ID, token) VALUES (?, ?, ?)");
    $stmt->execute([$userID, $artworkID, $token]);

    $_SESSION['message'] = "NFT minted successfully!";
    header("Location: " . VIEW_URL . "/user/dashboard");
    exit;
}

header("Location: " . BASE_URL);
exit;
