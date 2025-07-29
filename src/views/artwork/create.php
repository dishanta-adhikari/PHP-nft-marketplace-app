<?php
require_once __DIR__ . '/../components/header.php';
require_once __DIR__ . '/../components/footer.php';

use App\Middleware\SessionMiddleware;
use App\Controllers\ArtistController;
use App\Controllers\ArtworkController;
use App\Helpers\Flash;

SessionMiddleware::validateAdminSession();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $artworkController = new ArtworkController($con);
    $artworkController->create(array_merge($_POST, ['photo' => $_FILES['photo']]));
}

$artistController = new ArtistController($con);
$artists = $artistController->fetchAll();
?>


<link rel="stylesheet" href="<?= APP_URL ?>/public/assets/css/add_nft.css">

<main>
    <div class="container my-5">
        <h2>Create NFT</h2>

        <?php Flash::render(); ?>

        <form method="POST" enctype="multipart/form-data" class="card shadow-sm">
            <div class="mb-3">
                <label class="form-label" for="title">Title *</label>
                <input type="text" id="title" name="title" class="form-control" required autofocus>
            </div>
            <div class="mb-3">
                <label class="form-label" for="price">Price (USD) *</label>
                <input type="number" id="price" step="0.01" name="price" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label" for="photo">Artwork Image *</label>
                <input type="file" id="photo" name="photo" accept="image/*" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label" for="description">Description</label>
                <textarea id="description" name="description" rows="3" class="form-control"></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label" for="artist_id">Select Artist *</label>
                <select id="artist_id" name="artist_id" class="form-select" required>
                    <option value="">-- Select Artist --</option>

                    <?php foreach ($artists as $artist) : ?>
                        <option value="<?= htmlspecialchars($artist['Artist_ID']) ?>">
                            <?= htmlspecialchars($artist['Name']) ?>
                        </option>
                    <?php endforeach; ?>

                </select>
            </div>
            <button type="submit" name="submit" class="btn btn-success">Add NFT</button>
        </form>
    </div>
</main>