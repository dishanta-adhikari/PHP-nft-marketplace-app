<?php
require_once __DIR__ . '/../components/header.php';
require_once __DIR__ . '/../components/footer.php';

use App\Middleware\SessionMiddleware;
use App\Controllers\UserController;
use App\Helpers\Flash;

SessionMiddleware::validateAdminSession();

$adminId = $_SESSION['user_id'];

$userController = new UserController($con);
$admin = $userController->getById($adminId);

?>
<link rel="stylesheet" href="<?= APP_URL ?>/public/assets/css/admin_panel.css">

<main>
    <div class="nft-dashboard-container nft-fade-slide-in">
        <h2 class="nft-text-center mb-4">Dashboard | ADMIN</h2>

        <?php Flash::render(); ?>

        <div class="nft-dashboard-grid">

            <div class="nft-card">
                <h5 class="nft-card-title">Create NFT</h5>
                <p class="nft-card-text">Upload and manage NFT artworks.</p>
                <a href="<?= APP_URL; ?>/artwork/create" class="nft-btn-light">Create</a>
            </div>

            <div class="nft-card">
                <h5 class="nft-card-title">Artworks</h5>
                <p class="nft-card-text">Edit all uploaded NFT artworks.</p>
                <a href="<?= APP_URL ?>/artwork/edit" class="nft-btn-light">Edit</a>
            </div>

            <div class="nft-card">
                <h5 class="nft-card-title">Artists</h5>
                <p class="nft-card-text">Add or update artist information.</p>
                <a href="<?= APP_URL ?>/artist" class="nft-btn-light">Manage</a>
            </div>

            <div class="nft-card">
                <h5 class="nft-card-title">Assign Roles</h5>
                <p class="nft-card-text">See and update users.</p>
                <a href="<?= APP_URL ?>/admin/update-role" class="nft-btn-light">Edit</a>
            </div>

        </div>

    </div>
</main>