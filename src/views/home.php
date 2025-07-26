<?php
include_once __DIR__ . "/components/header.php";
include_once __DIR__ . "/components/footer.php";

use App\Middleware\SessionMiddleware;
use App\Controllers\ArtworkController;
use App\Helpers\Flash;

SessionMiddleware::verifyAdmin();

$search = $_GET['search'] ?? '';
$page   = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit  = 6;

$artworkController = new ArtworkController($con);
$results = $artworkController->getPaginatedArtworks($search, $page, $limit);

$artworks   = $results['artworks'];
$totalPages = $results['totalPages'];
?>


<link rel="stylesheet" href="<?= APP_URL ?>/public/assets/css/index.css">

<style>
    .sold-badge {
        position: absolute;
        top: 10px;
        right: 10px;
        background-color: #dc3545;
        color: white;
        padding: 5px 10px;
        font-weight: bold;
        border-radius: 5px;
        font-size: 0.9rem;
        z-index: 10;
        user-select: none;
    }

    .card-img-top-wrapper {
        position: relative;
    }
</style>

<main class="container my-4">
    <?php Flash::render(); ?>
    <h1>NFT Marketplace | Artworks</h1>

    <form method="GET" class="input-group mb-4" role="search" aria-label="Search artworks">
        <input type="text" name="search" class="form-control" placeholder="Search by title, artist, or description" value="<?= htmlspecialchars($search) ?>" />
        <button class="btn btn-outline-primary" type="submit">Search</button>
    </form>

    <div class="row">
        <?php if (count($artworks) === 0): ?>
            <div class="col-12">
                <div class="alert alert-warning">No artworks found for your search.</div>
            </div>
        <?php endif; ?>

        <?php foreach ($artworks as $art): ?>
            <div class="col-md-4 col-sm-6 mb-4">
                <div class="card shadow-sm">
                    <div class="card-img-top-wrapper">
                        <?php if (!empty($art['IsSold'])): ?>
                            <div class="sold-badge">SOLD</div>
                        <?php endif; ?>
                        <img src="<?= APP_URL . "/public/uploads/images/" . htmlspecialchars($art['Photo']) ?>" class="card-img-top" alt="<?= htmlspecialchars($art['Title']) ?>" />
                    </div>
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($art['Title']) ?></h5>
                        <p class="card-text"><?= htmlspecialchars($art['Description']) ?></p>
                        <p><strong>Artist:</strong> <?= htmlspecialchars($art['ArtistName']) ?></p>
                        <p><strong>Price:</strong> $<?= number_format($art['Price'], 2) ?></p>

                        <?php if (!empty($art['IsSold'])): ?>
                            <button class="btn btn-secondary w-100" disabled>Sold</button>
                        <?php else: ?>
                            <a href="<?= APP_URL ?>/artwork/view?id=<?= $art['Artwork_ID'] ?>" class="btn btn-primary w-100" aria-label="View and mint NFT <?= htmlspecialchars($art['Title']) ?>">View & Mint NFT</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>

        <!-- pagination -->
        <?php if ($totalPages > 1): ?>
            <nav>
                <ul class="pagination justify-content-center">
                    <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
                        <a class="page-link" href="?search=<?= urlencode($search) ?>&page=<?= $page - 1 ?>">Previous</a>
                    </li>
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                            <a class="page-link" href="?search=<?= urlencode($search) ?>&page=<?= $i ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>
                    <li class="page-item <?= $page >= $totalPages ? 'disabled' : '' ?>">
                        <a class="page-link" href="?search=<?= urlencode($search) ?>&page=<?= $page + 1 ?>">Next</a>
                    </li>
                </ul>
            </nav>
        <?php endif; ?>

    </div>
</main>