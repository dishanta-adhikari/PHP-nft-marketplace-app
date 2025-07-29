<?php require_once __DIR__ . '/../Components/header.php'; ?>

<div class="container text-center my-5">
    <h1 class="display-1 text-danger">500</h1>
    <h2 class="mb-4">Internal Server Error</h2>
    <p class="lead">Oops! Something went wrong on our end. Please try again later.</p>
    <a href="<?= APP_URL ?>" class="btn btn-warning mt-3">
        <i class="bi bi-arrow-repeat"></i> Reload
    </a>
</div>

<?php require_once __DIR__ . '/../Components/footer.php'; ?>