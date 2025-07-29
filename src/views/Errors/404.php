    <?php require_once __DIR__ . '/../Components/header.php'; ?>

    <div class="container text-center my-5">
        <h1 class="display-1 text-danger">404</h1>
        <h2 class="mb-4">Page Not Found</h2>
        <p class="lead">Sorry, the page you're looking for doesn't exist.</p>
        <a href="<?= APP_URL ?>" class="btn btn-primary mt-3">
            <i class="bi bi-house-door"></i> Go to Homepage
        </a>
    </div>

    <?php require_once __DIR__ . '/../Components/footer.php'; ?>