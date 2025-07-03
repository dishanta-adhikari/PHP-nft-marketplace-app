<?php
session_start();

require_once __DIR__ . "/../../App/App.php";
require_once __DIR__ . "/../../Config/Url.php";
include_once __DIR__ . "/../../Views/Components/header.php";

$app = new App();
$conn = $app->connect();

// Check if admin is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login");
    exit;
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
            <h5 class="nft-card-title">Add New NFT</h5>
            <p class="nft-card-text">Upload and manage NFT artworks.</p>
            <a href="<?php echo VIEW_URL; ?>/admin/create-nft" class="nft-btn-light">Go</a>
        </div>

        <div class="nft-card">
            <h5 class="nft-card-title">Manage Artworks</h5>
            <p class="nft-card-text">See all uploaded NFT artworks.</p>
            <a href="view_artworks" class="nft-btn-light">Manage</a>
        </div>

        <div class="nft-card">
            <h5 class="nft-card-title">Manage Artists</h5>
            <p class="nft-card-text">Add or update artist information.</p>
            <a href="manage_artists" class="nft-btn-light">Manage</a>
        </div>

        <div class="nft-card">
            <h5 class="nft-card-title">Manage User Roles</h5>
            <p class="nft-card-text">See and update users.</p>
            <a href="manage_users" class="nft-btn-light">Manage</a>
        </div>

    </div>

</div>

<?php include_once __DIR__ . "/../../Views/Components/footer.php"; ?>