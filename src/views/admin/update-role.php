<?php
require_once __DIR__ . '/../components/header.php';
require_once __DIR__ . '/../components/footer.php';

use App\Middleware\SessionMiddleware;
use App\Controllers\UserController;
use App\Helpers\Flash;

SessionMiddleware::validateAdminSession();

$controller = new UserController($con);
$users = $controller->manageUsers();
?>

<link rel="stylesheet" href="<?= APP_URL ?>/public/assets/css/manage_users.css">
<div class="container my-5">

    <?php Flash::render(); ?>

    <h2 class="mb-4 text-center fw-bold" style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">
        Manage Users
    </h2>

    <div class="mb-4 d-flex justify-content-center">
        <input id="searchInput" type="search" class="form-control form-control-lg w-50 shadow-sm" placeholder="Search users by name or email..." aria-label="Search Users">
    </div>

    <?php if (count($users) === 0): ?>
        <div class="alert alert-info text-center fs-5">No other users found.</div>
    <?php else: ?>
        <div id="usersContainer" class="row g-4 justify-content-center">
            <?php foreach ($users as $user): ?>
                <div class="col-12 col-md-6 col-lg-4 user-card-wrapper" data-name="<?= strtolower(htmlspecialchars($user['name'])) ?>" data-email="<?= strtolower(htmlspecialchars($user['email'])) ?>">
                    <div class="card user-card glass-card shadow rounded-4 overflow-hidden p-3">
                        <div class="card-body d-flex flex-column justify-content-between h-100">
                            <div>
                                <h5 class="card-title text-gradient fw-bold mb-2">
                                    <i class="bi bi-person-circle me-2"></i> <?= htmlspecialchars($user['name']) ?>
                                </h5>
                                <p class="card-text text-muted mb-1">
                                    <i class="bi bi-envelope-fill me-1"></i> <?= htmlspecialchars($user['email']) ?>
                                </p>
                                <p class="card-text mb-3">
                                    Role:
                                    <span class="badge role-badge <?= $user['role'] === 'admin' ? 'admin-role' : 'user-role' ?>">
                                        <?= htmlspecialchars(ucfirst($user['role'])) ?>
                                    </span>
                                </p>
                            </div>

                            <form method="post" class="d-flex gap-2 align-items-center role-form" onsubmit="return confirmRoleChange(this);">
                                <input type="hidden" name="user_id" value="<?= $user['user_id'] ?>">
                                <select name="new_role" class="form-select form-select-sm flex-grow-1" required>
                                    <option value="user" <?= $user['role'] === 'user' ? 'selected' : '' ?>>User</option>
                                    <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
                                </select>
                                <button type="submit" class="btn btn-sm btn-outline-success" title="Update Role">
                                    <i class="bi bi-check-lg"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<script>
    function confirmRoleChange(form) {
        const select = form.querySelector('select[name="new_role"]');
        const role = select.options[select.selectedIndex].text;
        return confirm(`Are you sure you want to change the role to "${role}"?`);
    }

    document.getElementById('searchInput').addEventListener('input', function() {
        const query = this.value.trim().toLowerCase();
        const users = document.querySelectorAll('.user-card-wrapper');
        users.forEach(user => {
            const name = user.getAttribute('data-name');
            const email = user.getAttribute('data-email');
            user.style.display = name.includes(query) || email.includes(query) ? '' : 'none';
        });
    });
</script>