<?php
require_once __DIR__ . '/../components/header.php';
require_once __DIR__ . '/../components/footer.php';

use App\Middleware\SessionMiddleware;
use App\Controllers\ArtistController;
use App\Helpers\Flash;

SessionMiddleware::validateAdminSession();

$controller = new ArtistController($con);
$artists = $controller->handleRequest();


?>
<link rel="stylesheet" href="<?= APP_URL ?>/public/assets/css/manage_artists.css">

<div class="artist-manager container py-5">

    <h2>Artist Manager</h2>

    <?php Flash::render(); ?>

    <!-- Create Artist Form -->
    <div class="card mb-4 bg-transparent border-0">
        <div class="card-body p-0">
            <form method="POST" class="row g-3 align-items-center justify-content-center">
                <div class="col-auto" style="min-width: 280px;">
                    <label style="color: white;" for="name" class="form-label">Create Artist</label>
                    <input type="text" name="name" id="name" class="form-control" placeholder="Enter artist name" required />
                </div>
                <div class="col-auto" style="min-width: 280px;">
                    <label style="color: white;" for="email" class="form-label">Artist Email</label>
                    <input type="email" name="email" id="email" class="form-control" placeholder="Enter unique email" required />
                </div>
                <div class="col-auto align-self-end">
                    <button type="submit" name="create_artist" class="btn btn-primary">Create</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Artists Table -->
    <div class="table-responsive shadow-sm">
        <table class="table table-borderless text-center align-middle">
            <thead>
                <tr>
                    <th>Artist Name</th>
                    <th>Email</th>
                    <th style="min-width: 240px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($artists) === 0): ?>
                    <tr>
                        <td colspan="4" class="text-muted fst-italic">No artists found.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($artists as $artist): ?>
                        <tr>
                            <td><?= htmlspecialchars($artist['Name']) ?></td>
                            <td><?= htmlspecialchars($artist['Email']) ?></td>
                            <td>
                                <!-- Edit Button triggers modal -->
                                <button type="button"
                                    class="btn btn-warning btn-sm me-2"
                                    data-bs-toggle="modal"
                                    data-bs-target="#editArtistModal"
                                    data-id="<?= $artist['Artist_ID'] ?>"
                                    data-name="<?= htmlspecialchars($artist['Name']) ?>"
                                    data-email="<?= htmlspecialchars($artist['Email']) ?>">
                                    Edit
                                </button>
                                <!-- Delete Button triggers modal -->
                                <button type="button"
                                    class="btn btn-danger btn-sm"
                                    data-bs-toggle="modal"
                                    data-bs-target="#deleteArtistModal"
                                    data-id="<?= $artist['Artist_ID'] ?>"
                                    data-name="<?= htmlspecialchars($artist['Name']) ?>">
                                    Delete
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Edit Artist Modal -->
<div class="modal fade" id="editArtistModal" tabindex="-1" aria-labelledby="editArtistLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form method="POST" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editArtistLabel">Edit Artist</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="artist_id" id="editArtistId" value="" />
                <div class="mb-3">
                    <label for="editArtistName" class="form-label">Artist Name</label>
                    <input type="text" class="form-control" id="editArtistName" name="name" required />
                </div>
                <div class="mb-3">
                    <label for="editArtistEmail" class="form-label">Artist Email</label>
                    <input type="email" class="form-control" id="editArtistEmail" name="email" required />
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" name="update_artist" class="btn btn-primary">Save Changes</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            </div>
        </form>
    </div>
</div>

<!-- Delete Artist Modal -->
<div class="modal fade" id="deleteArtistModal" tabindex="-1" aria-labelledby="deleteArtistLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form method="POST" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteArtistLabel">Delete Artist</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="artist_id" id="deleteArtistId" value="" />
                <p>Are you sure you want to delete the artist <strong id="deleteArtistName"></strong>?</p>
            </div>
            <div class="modal-footer">
                <button type="submit" name="delete_artist" class="btn btn-danger">Delete</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    const editModal = document.getElementById('editArtistModal');
    editModal.addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget;
        const id = button.getAttribute('data-id');
        const name = button.getAttribute('data-name');
        const email = button.getAttribute('data-email');

        document.getElementById('editArtistId').value = id;
        document.getElementById('editArtistName').value = name;
        document.getElementById('editArtistEmail').value = email;
    });

    const deleteModal = document.getElementById('deleteArtistModal');
    deleteModal.addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget;
        const id = button.getAttribute('data-id');
        const name = button.getAttribute('data-name');

        document.getElementById('deleteArtistId').value = id;
        document.getElementById('deleteArtistName').innerText = name;
    });
</script>