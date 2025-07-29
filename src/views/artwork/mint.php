<?php
require_once __DIR__ . '/../components/header.php';
require_once __DIR__ . '/../components/footer.php';

use App\Middleware\SessionMiddleware;
use App\Controllers\NFTController;

SessionMiddleware::validateUserSession();

$nftController = new NftController($con);
$nftController->mint();
