<?php
require_once __DIR__ . '/../components/header.php';
require_once __DIR__ . '/../components/footer.php';

use App\Middleware\SessionMiddleware;
use App\Controllers\ArtworkController;
use App\Helpers\Flash;

SessionMiddleware::validateAdminSession();

$controller = new ArtworkController($con);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['toggle_status'])) {
        $controller->updateStatus($_POST);
        $_SESSION['message'] = "Status updated.";
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }

    if (isset($_POST['edit_artwork'])) {
        if ($controller->updateArtwork($_POST, $_FILES)) {
            $_SESSION['message'] = "Artwork updated successfully.";
        }
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    }
}

$searchTerm = $_GET['search'] ?? '';
$statusFilter = $_GET['status'] ?? 'all';
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;

$viewData = $controller->listArtworks($searchTerm, $statusFilter, $page);
extract($viewData); // $artworks, $totalItems, $totalPages, $artists
?>

<link rel="stylesheet" href="<?= APP_URL ?>/public/assets/css/view_artworks.css">

<div class="container my-5" style="color: var(--color-text-light);">
    <h2 class="mb-4 text-center" style="color: var(--color-secondary); font-weight: 700;">Explore NFT Artworks</h2>

    <?php Flash::render(); ?>

    <div class="row mb-4 g-2">
        <div class="col-md-6">
            <form method="GET" class="d-flex">
                <input type="text" name="search" class="form-control me-2" placeholder="Search title, description, or artist"
                    value="<?= htmlspecialchars($searchTerm) ?>"
                    style="border-color: var(--color-secondary); color: var(--color-text-light); background: var(--color-card-bg);">
                <button class="btn" type="submit"
                    style="background-color: var(--color-secondary); color: var(--color-text-light); font-weight: 600; border:none;">Search</button>
            </form>
        </div>

        <div class="col-md-4">
            <form method="GET" id="statusForm">
                <input type="hidden" name="search" value="<?= htmlspecialchars($searchTerm) ?>">
                <select name="status" class="form-select" onchange="document.getElementById('statusForm').submit()"
                    style="border-color: var(--color-secondary); background: var(--color-card-bg); color: var(--color-text-light); font-weight: 600;">
                    <option value="all" <?= $statusFilter === 'all' ? 'selected' : '' ?>>All Statuses</option>
                    <option value="active" <?= $statusFilter === 'active' ? 'selected' : '' ?>>Enabled</option>
                    <option value="disabled" <?= $statusFilter === 'disabled' ? 'selected' : '' ?>>Disabled</option>
                </select>
            </form>
        </div>
    </div>

    <?php if (count($artworks) === 0): ?>
        <div class="alert alert-info" style="background-color: var(--color-card-bg); color: var(--color-text-muted); border-radius: 8px;">
            No artworks found.
        </div>
    <?php endif; ?>

    <div class="row g-4">
        <?php foreach ($artworks as $art): ?>
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm" style="background-color: var(--color-card-bg); color: var(--color-text-light); border-radius: 12px;">
                    <img src="<?= APP_URL . "/public/uploads/images/" . htmlspecialchars($art['Photo']) ?>" class="card-img-top"
                        alt="<?= htmlspecialchars($art['Title']) ?>" style="height: 250px; object-fit: cover; border-radius: 12px 12px 0 0;">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title" style="color: var(--color-secondary); font-weight: 700;"><?= htmlspecialchars($art['Title']) ?></h5>
                        <p class="card-text text-muted" style="color: var(--color-text-muted);">By: <?= htmlspecialchars($art['ArtistName']) ?></p>
                        <p class="card-text flex-grow-1"><?= htmlspecialchars($art['Description']) ?></p>
                        <p class="fw-bold text-success" style="color: var(--color-primary);">$<?= number_format($art['Price'], 2) ?></p>

                        <p>
                            <?php if (!empty($art['Is_Paid']) && $art['Is_Paid'] == 1): ?>
                                <span class="badge bg-danger"
                                    style=" font-weight: 600; font-size: 0.9rem; padding: 0.4em 0.7em;">
                                    Paid
                                </span>
                            <?php else: ?>
                                <span class="badge <?= $art['Status'] === 'active' ? 'bg-success' : 'bg-secondary' ?>"
                                    style="font-weight: 600; font-size: 0.9rem; padding: 0.4em 0.7em;">
                                    <?= ucfirst($art['Status']) ?>
                                </span>
                            <?php endif; ?>
                        </p>

                        <form method="POST" class="mb-3">
                            <input type="hidden" name="artwork_id" value="<?= $art['Artwork_ID'] ?>">
                            <input type="hidden" name="current_status" value="<?= $art['Status'] ?>">
                            <button type="submit" name="toggle_status" class="btn btn-sm <?= $art['Status'] === 'active' ? 'btn-warning' : 'btn-success' ?>"
                                style="font-weight: 600; width: 100%;">
                                <?= $art['Status'] === 'active' ? 'Disable' : 'Enable' ?>
                            </button>
                        </form>

                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editModal<?= $art['Artwork_ID'] ?>" style="font-weight: 600; width: 100%;">
                            Edit Details
                        </button>
                    </div>
                </div>
            </div>



            <!-- Edit Modal -->
            <div class="modal fade" id="editModal<?= $art['Artwork_ID'] ?>" tabindex="-1" aria-labelledby="editModalLabel<?= $art['Artwork_ID'] ?>" aria-hidden="true">
                <div class="modal-dialog">
                    <form method="POST" enctype="multipart/form-data" class="modal-content" style="background-color: var(--color-card-bg); color: var(--color-text-light); border-radius: 12px;">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editModalLabel<?= $art['Artwork_ID'] ?>" style="color: var(--color-secondary); font-weight: 700;">Edit Artwork</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="artwork_id" value="<?= $art['Artwork_ID'] ?>">
                            <div class="mb-3">
                                <label for="title<?= $art['Artwork_ID'] ?>" class="form-label">Title</label>
                                <input type="text" name="title" id="title<?= $art['Artwork_ID'] ?>" class="form-control" value="<?= htmlspecialchars($art['Title']) ?>" required style="background: var(--color-card-bg); color: var(--color-text-light); border-color: var(--color-secondary);">
                            </div>
                            <div class="mb-3">
                                <label for="description<?= $art['Artwork_ID'] ?>" class="form-label">Description</label>
                                <textarea name="description" id="description<?= $art['Artwork_ID'] ?>" class="form-control" required style="background: var(--color-card-bg); color: var(--color-text-light); border-color: var(--color-secondary);"><?= htmlspecialchars($art['Description']) ?></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="price<?= $art['Artwork_ID'] ?>" class="form-label">Price</label>
                                <input type="number" name="price" id="price<?= $art['Artwork_ID'] ?>" class="form-control" value="<?= htmlspecialchars($art['Price']) ?>" step="0.01" required style="background: var(--color-card-bg); color: var(--color-text-light); border-color: var(--color-secondary);">
                            </div>
                            <div class="mb-3">
                                <label for="artist<?= $art['Artwork_ID'] ?>" class="form-label">Artist</label>
                                <select name="artist_id" id="artist<?= $art['Artwork_ID'] ?>" class="form-select" required style="background: var(--color-card-bg); color: var(--color-text-light); border-color: var(--color-secondary);">
                                    <?php foreach ($artists as $artist): ?>
                                        <option value="<?= $artist['Artist_ID'] ?>" <?= $artist['Artist_ID'] == $art['Artist_ID'] ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($artist['Name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="photo<?= $art['Artwork_ID'] ?>" class="form-label">Change Photo</label>
                                <input type="file" name="photo" id="photo<?= $art['Artwork_ID'] ?>" class="form-control" accept="image/*" style="background: var(--color-card-bg); color: var(--color-text-light); border-color: var(--color-secondary);">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" name="edit_artwork" class="btn btn-success" style="font-weight: 600;">Save Changes</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" style="font-weight: 600;">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>

        <?php if ($totalPages > 1): ?>
            <nav class="mt-4">
                <ul class="pagination justify-content-center">
                    <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
                        <a class="page-link" href="?<?= http_build_query(['search' => $searchTerm, 'status' => $statusFilter, 'page' => $page - 1]) ?>">Previous</a>
                    </li>

                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                            <a class="page-link" href="?<?= http_build_query(['search' => $searchTerm, 'status' => $statusFilter, 'page' => $i]) ?>">
                                <?= $i ?>
                            </a>
                        </li>
                    <?php endfor; ?>

                    <li class="page-item <?= $page >= $totalPages ? 'disabled' : '' ?>">
                        <a class="page-link" href="?<?= http_build_query(['search' => $searchTerm, 'status' => $statusFilter, 'page' => $page + 1]) ?>">Next</a>
                    </li>
                </ul>
            </nav>
        <?php endif; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>