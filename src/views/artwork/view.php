<?php
session_start();
require_once __DIR__ . "/../../App/App.php";
require_once __DIR__ . "/../../Config/Url.php";
include_once __DIR__ . "/../../Views/Components/header.php";

$app = new App();
$conn = $app->connect();

if (!isset($_GET['id'])) {
    echo '<div class="container my-4"><div class="alert alert-danger">Artwork not specified.</div></div>';
    exit;
}

$artworkID = (int)$_GET['id'];

$stmt = $conn->prepare("SELECT a.*, ar.Name AS ArtistName FROM artwork a JOIN artist ar ON a.Artist_ID = ar.Artist_ID WHERE a.Artwork_ID = ?");
$stmt->execute([$artworkID]);
$artwork = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$artwork) {
    echo '<div class="container my-4"><div class="alert alert-danger">Artwork not found.</div></div>';
    exit;
}
?>

<link rel="stylesheet" href="<?php echo BASE_URL ?>/assets/css/artwork.css">
<div class="container my-4">
    <div class="row g-4">
        <div class="col-md-6 text-center">
            <img src="<?php echo BASE_URL . "/" . htmlspecialchars($artwork['Photo']) ?>"
                alt="<?= htmlspecialchars($artwork['Title']) ?>"
                class="img-fluid nft-artwork-img">
        </div>

        <div class="col-md-6">
            <h2 class="nft-artwork-title mb-3"><?= htmlspecialchars($artwork['Title']) ?></h2>
            <p class="nft-artwork-artist"><strong>Artist:</strong> <?= htmlspecialchars($artwork['ArtistName']) ?></p>
            <p class="nft-artwork-description"><strong>Description:</strong><br><?= nl2br(htmlspecialchars($artwork['Description'])) ?></p>
            <p class="nft-artwork-price">Price: <span>$<?= number_format($artwork['Price'], 2) ?></span></p>

            <?php if (isset($_SESSION['user_id'])): ?>
                <form method="post" action="<?php echo VIEW_URL; ?>/artwork/mint" class="mt-4">
                    <input type="hidden" name="artwork_id" value="<?= $artworkID ?>">
                    <button type="submit" class="nft-mint-btn">Mint NFT</button>
                </form>
            <?php else: ?>
                <p class="nft-login-prompt">Please <a href="<?php echo VIEW_URL; ?>/auth/login">login</a> to mint this NFT.</p>
            <?php endif; ?>
        </div>
    </div>
</div>


<?php include_once __DIR__ . "/../../Views/Components/footer.php"; ?>