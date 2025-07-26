<?php
require_once __DIR__ . '/../components/header.php';
require_once __DIR__ . '/../components/footer.php';

use App\Middleware\SessionMiddleware;
use App\Controllers\RegisterController;
use App\Helpers\Flash;

SessionMiddleware::verifyUser();
SessionMiddleware::verifyAdmin();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $user = new RegisterController($con);
    $user->register($_POST);
}

?>


<link rel="stylesheet" href="<?= APP_URL ?>/public/assets/css/login.css">

<main>
    <div id="register-page" class="container tubelight-box side-light mt-5" style="max-width: 480px;">
        <h2 class="mb-4 text-center">Create Account</h2>

        <?php Flash::render(); ?>

        <form method="post" action="register" novalidate>
            <div class="mb-3">
                <label for="register-name" class="form-label">Name *</label>
                <input type="text" id="register-name" name="name" class="form-control" required
                    value="<?= htmlspecialchars($_POST['name'] ?? '') ?>" autocomplete="name">
            </div>
            <div class="mb-3">
                <label for="register-email" class="form-label">Email *</label>
                <input type="email" id="register-email" name="email" class="form-control" required
                    value="<?= htmlspecialchars($_POST['email'] ?? '') ?>" autocomplete="email">
            </div>
            <div class="mb-3">
                <label for="register-dob" class="form-label">Date of Birth *</label>
                <input type="date" id="register-dob" name="dob" class="form-control" required
                    value="<?= htmlspecialchars($_POST['dob'] ?? '') ?>">
            </div>
            <div class="mb-3">
                <label for="register-password" class="form-label">Password *</label>
                <input type="password" id="register-password" name="password" class="form-control" required autocomplete="new-password">
            </div>
            <div class="mb-3">
                <label for="register-confirm_password" class="form-label">Confirm Password *</label>
                <input type="password" id="register-confirm_password" name="confirm_password" class="form-control" required autocomplete="new-password">
            </div>

            <button type="submit" name="submit" class="btn btn-primary w-100">Register</button>

            <div class="mt-3 text-center">
                <a href="<?= APP_URL ?>/login" class="btn btn-link p-0">Already have an account? Login</a>
            </div>
        </form>
    </div>
</main>