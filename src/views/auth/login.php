<?php
require_once __DIR__ . '/../components/header.php';
require_once __DIR__ . '/../components/footer.php';

use App\Middleware\SessionMiddleware;
use App\Controllers\LoginController;
use App\Helpers\Flash;

SessionMiddleware::verifyAdmin();
SessionMiddleware::verifyUser();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $user = new LoginController($con);
    $user->login($_POST);
}

?>

<link rel="stylesheet" href="<?= APP_URL ?>/public/assets/css/login.css">

<main>
    <div id="login-page" class="container tubelight-box side-light mt-5" style="max-width: 480px;">

        <h2 class="mb-4 text-center">Log In</h2>

        <?php Flash::render(); ?>

        <form method="POST" action="">
            <div class="mb-3">
                <label for="login-email" class="form-label">Email *</label>
                <input type="email" id="login-email" name="email" class="form-control" required
                    value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" autocomplete="username">
            </div>
            <div class="mb-3">
                <label for="login-password" class="form-label">Password *</label>
                <input type="password" id="login-password" name="password" class="form-control" required autocomplete="current-password">
            </div>
            <div class="form-check mb-4">
                <input type="checkbox" class="form-check-input" id="login-is_admin" name="is_admin" <?= isset($_POST['is_admin']) ? 'checked' : '' ?>>
                <label class="form-check-label" for="login-is_admin">Login as Admin</label>
            </div>
            <button type="submit" name="submit" class="btn btn-primary w-100">Login</button>
            <div class="mt-3 text-center">
                <a href="<?= APP_URL ?>/register" class="btn btn-link p-0">Don't have an account? Register</a>
            </div>

        </form>
    </div>
</main>