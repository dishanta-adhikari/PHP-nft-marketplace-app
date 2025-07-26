<?php
require_once __DIR__ . "/../../_init_.php";

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <?php if (isset($_SESSION['user_name']) && isset($_SESSION['role'])): ?>

        <?php if ($_SESSION['role'] !== 'admin'): ?>
            <title>User | <?= $_SESSION['user_name'] ?? '' ?></title>
        <?php else: ?>
            <title>Admin | <?= $_SESSION['user_name'] ?? '' ?></title>
        <?php endif; ?>

    <?php else: ?>
        <title>NFT Marketplace | Home</title>
    <?php endif; ?>

    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Orbitron&family=Poppins&display=swap" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #121212;
            color: #e0e0e0;
        }

        .navbar-dark.bg-dark {
            background: linear-gradient(45deg, #4b0082, #8a2be2);
            box-shadow: 0 4px 12px rgba(138, 43, 226, 0.6);
        }

        .navbar-brand {
            font-family: 'Orbitron', sans-serif;
            font-weight: 900;
            font-size: 1.8rem;
            letter-spacing: 2px;
            color: #e91e63 !important;
            text-shadow: 0 0 6px #e91e63;
        }

        .nav-link {
            color: #ddd !important;
            transition: color 0.3s ease;
        }

        .nav-link:hover,
        .nav-link:focus {
            color: #e91e63 !important;
            text-shadow: 0 0 6px #e91e63;
        }
    </style>
</head>

<header>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">

            <?php
            $brandLink = APP_URL;
            if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') :
                $brandLink = APP_URL . "/admin/dashboard";
            endif;
            ?>

            <a class="navbar-brand fw-bold" href="<?= htmlspecialchars($brandLink) ?>">NFT Marketplace</a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>


            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <?php if (isset($_SESSION['user_name']) && isset($_SESSION['role'])): ?>

                        <?php if ($_SESSION['role'] !== 'admin'): ?>

                            <li class="nav-item">
                                <a class="nav-link fw-bold" href="<?= APP_URL; ?>">Home</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link fw-bold" href="<?= APP_URL; ?>/user/dashboard">Dashboard</a>
                            </li>

                        <?php else: ?>
                            <li class="nav-item">
                                <a class="nav-link fw-bold" href="<?= APP_URL; ?>/admin/dashboard">Dashboard</a>
                            </li>
                        <?php endif; ?>

                        <li class="nav-item">
                            <a class="nav-link fw-bold" href="<?= APP_URL; ?>/logout">Logout (<?= htmlspecialchars($_SESSION['user_name']) ?>)</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item"><a class="nav-link fw-bold" href="<?= APP_URL ?>">Home</a></li>
                        <li class="nav-item"><a class="nav-link fw-bold" href="<?= APP_URL ?>/login">Log In</a></li>
                        <li class="nav-item"><a class="nav-link fw-bold" href="<?= APP_URL ?>/register">Register</a></li>
                    <?php endif; ?>

                </ul>
            </div>
        </div>
    </nav>
</header>

<body>