<?php

/** @var string $contentHTML */
/** @var AppUser $user */
/** @var LinkGenerator $link */

use Framework\Auth\AppUser;
use Framework\Support\LinkGenerator;

?>
<!DOCTYPE html>
<html lang="sk">
<head>
    <title><?= App\Configuration::APP_NAME ?></title>
    <!-- Favicons -->
    <link rel="apple-touch-icon" sizes="180x180" href="<?= $link->asset('favicons/apple-touch-icon.png') ?>">
    <link rel="icon" type="image/png" sizes="32x32" href="<?= $link->asset('favicons/favicon-32x32.png') ?>">
    <link rel="icon" type="image/png" sizes="16x16" href="<?= $link->asset('favicons/favicon-16x16.png') ?>">
    <link rel="manifest" href="<?= $link->asset('favicons/site.webmanifest') ?>">
    <link rel="shortcut icon" href="<?= $link->asset('favicons/favicon.ico') ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
            crossorigin="anonymous"></script>
    <link rel="stylesheet" href="<?= $link->asset('css/styl.css') ?>">
    <script src="<?= $link->asset('js/script.js') ?>"></script>
</head>
<body>
<nav class="navbar navbar-expand-sm bg-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="<?= $link->url('home.index') ?>">
            <img src="<?= $link->asset('images/vaiicko_logo.png') ?>" title="<?= App\Configuration::APP_NAME ?>"
                 alt="Framework Logo">
        </a>
        <?php if ($user->isLoggedIn()) { ?>
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <div class="container-fluid">
                    <!-- Hamburger button -->
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                            data-bs-target="#navbarContent"
                            aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <!-- Collapsable content -->
                    <div class="collapse navbar-collapse" id="navbarContent">
                        <span class="navbar-text me-auto">
                            Logged in user: <b><?= $user->getName() ?></b>
                        </span>
                        <ul class="navbar-nav ms-auto">
                            <li class="nav-item">
                                <a class="nav-link" href="<?= $link->url('recipe.favorites') ?>">Bookmarks</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="<?= $link->url('recipe.create') ?>">New Recipe</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="<?= $link->url('recipe.owned') ?>">My Recipes</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="<?= $link->url('auth.logout') ?>">Log out</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
        <?php } else { ?>
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="<?= App\Configuration::LOGIN_URL ?>">Log in</a>
                </li>
            </ul>
        <?php } ?>
    </div>
</nav>
<div class="container-fluid mt-3">
    <div class="web-content">
        <?= $contentHTML ?>
    </div>
</div>
</body>
</html>
