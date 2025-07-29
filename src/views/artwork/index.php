<?php
require_once __DIR__ . '/../components/header.php';
require_once __DIR__ . '/../components/footer.php';

use App\Middleware\SessionMiddleware;
use App\Controllers\ArtworkController;
use App\Helpers\Flash;

SessionMiddleware::validateUserSession();

if (!isset($_GET['id'])) {
    echo '<div class="container my-4"><div class="alert alert-danger">Artwork not specified.</div></div>';
    exit;
}

$controller = new ArtworkController($con);
$artwork = $controller->show((int)$_GET['id']);
?>

<link rel="stylesheet" href="<?= APP_URL ?>/public/assets/css/artwork.css">
<div class="container my-4">
    <?php Flash::render(); ?>
    <div class="row g-4">
        <div class="col-md-6 text-center">
            <img src="<?= APP_URL . "/public/uploads/images/" . htmlspecialchars($artwork['Photo']) ?>"
                alt="<?= htmlspecialchars($artwork['Title']) ?>"
                class="img-fluid nft-artwork-img">
        </div>

        <div class="col-md-6">
            <h2 class="nft-artwork-title mb-3"><?= htmlspecialchars($artwork['Title']) ?></h2>
            <p class="nft-artwork-artist"><strong>Artist:</strong> <?= htmlspecialchars($artwork['ArtistName']) ?></p>
            <p class="nft-artwork-description"><strong>Description:</strong><br><?= nl2br(htmlspecialchars($artwork['Description'])) ?></p>
            <p class="nft-artwork-price">Price: <span>$<?= number_format($artwork['Price'], 2) ?></span></p>

            <?php if (isset($_SESSION['user_id'])): ?>
                <form method="post" action="<?= APP_URL ?>/artwork/mint" class="mt-4">
                    <input type="hidden" name="artwork_id" value="<?= $artwork['Artwork_ID'] ?>">
                    <button type="submit" class="nft-mint-btn">Mint NFT</button>
                </form>
            <?php else: ?>
                <p class="nft-login-prompt">Please <a href="<?= APP_URL ?>/auth/login">login</a> to mint this NFT.</p>
            <?php endif; ?>
        </div>
    </div>
</div>