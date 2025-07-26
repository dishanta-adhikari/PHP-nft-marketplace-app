<?php
session_start();

require_once __DIR__ . "/../../App/App.php";
require_once __DIR__ . "/../../Config/Url.php";
include_once __DIR__ . "/../../Views/Components/header.php";

$app = new App();
$conn = $app->connect();

// Check if admin is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ". VIEW_URL ."/auth/login");
    exit();
}

$adminId = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT Name FROM user WHERE User_ID = ?");
$stmt->execute([$adminId]);
$admin = $stmt->fetch();
?>
<link rel="stylesheet" href="<?php echo BASE_URL ?>/assets/css/admin_panel.css">
<div class="nft-dashboard-container nft-fade-slide-in">

    <h2 class="nft-text-center mb-4">Admin Dashboard</h2>

    <div class="nft-alert-info">
        Welcome, <strong><?= htmlspecialchars($admin['Name']) ?></strong>!
    </div>

    <div class="nft-dashboard-grid">

        <div class="nft-card">
            <h5 class="nft-card-title">Create NFT</h5>
            <p class="nft-card-text">Upload and manage NFT artworks.</p>
            <a href="<?php echo VIEW_URL; ?>/artwork/create" class="nft-btn-light">Create</a>
        </div>

        <div class="nft-card">
            <h5 class="nft-card-title">Artworks</h5>
            <p class="nft-card-text">Edit all uploaded NFT artworks.</p>
            <a href="<?php echo VIEW_URL; ?>/artwork/edit" class="nft-btn-light">Edit</a>
        </div>

        <div class="nft-card">
            <h5 class="nft-card-title">Artists</h5>
            <p class="nft-card-text">Add or update artist information.</p>
            <a href="<?php echo VIEW_URL; ?>/artist/manage" class="nft-btn-light">Manage</a>
        </div>

        <div class="nft-card">
            <h5 class="nft-card-title">Assign Roles</h5>
            <p class="nft-card-text">See and update users.</p>
            <a href="<?php echo VIEW_URL; ?>/admin/users" class="nft-btn-light">Edit</a>
        </div>

    </div>

</div>

<?php include_once __DIR__ . "/../../Views/Components/footer.php"; ?>