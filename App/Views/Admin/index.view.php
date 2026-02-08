<?php
/** @var \Framework\Core\IAuthenticator $auth */
/** @var \Framework\Support\LinkGenerator $link */
?>

<div class="container py-4">
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-4">
        <div>
            <h1 class="h3 mb-1">Admin Panel</h1>
            <div class="text-muted">Choose what you want to manage.</div>
        </div>
        <a class="btn btn-outline-secondary" href="<?= $link->url('home.index') ?>">Back to home</a>
    </div>

    <div class="row g-3">
        <div class="col-12 col-md-6 col-lg-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <h2 class="h5 card-title mb-2">Users</h2>
                    <p class="card-text text-muted mb-3">View and manage application users.</p>
                    <a class="btn btn-primary" href="<?= $link->url('user.read') ?>">Open</a>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6 col-lg-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <h2 class="h5 card-title mb-2">Ingredients</h2>
                    <p class="card-text text-muted mb-3">Manage ingredient list used in recipes.</p>
                    <a class="btn btn-primary" href="<?= $link->url('ingredient.index') ?>">Open</a>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6 col-lg-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <h2 class="h5 card-title mb-2">Recipes</h2>
                    <p class="card-text text-muted mb-3">Browse and manage all recipes.</p>
                    <a class="btn btn-primary" href="<?= $link->url('recipe.owned') ?>">Open</a>
                </div>
            </div>
        </div>

        <div class="col-12 col-md-6 col-lg-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <h2 class="h5 card-title mb-2">Ingredient Categories</h2>
                    <p class="card-text text-muted mb-3">Manage categories displayed on the home page.</p>
                    <a class="btn btn-primary" href="<?= $link->url('category.index') ?>">Open</a>
                </div>
            </div>
        </div>
    </div>
</div>
