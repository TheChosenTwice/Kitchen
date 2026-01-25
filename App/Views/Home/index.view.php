<?php
/** @var \Framework\Core\IAuthenticator $auth */
/** @var \Framework\Support\LinkGenerator $link */
/** @var Array $categories */
/** @var Array $ingredients */
?>

<div class="container mt-5">
    <div class="row">
        <!-- Left side: Existing content -->
        <div class="col-md-6">
            <h1 class="text-center">What's in your fridge?</h1>
            <div class="text-start ms-3">
                <?php foreach ($categories as $category) : ?>
                    <h2><?= $category->getName() ?></h2>

                    <?php foreach ($ingredients as $ingredient) : ?>
                        <?php if ($ingredient->getCategoryId() === $category->getId()) : ?>
                            <button class="btn btn-outline-primary btn-sm ingredient-btn" data-ingredient="<?= $ingredient->getName() ?>" data-ingredient-id="<?= $ingredient->getId() ?>">
                                <?= $ingredient->getName() ?>
                            </button>
                        <?php endif; ?>
                    <?php endforeach ?>
                <?php endforeach ?>
            </div>
        </div>
        <!-- Right side: Selected ingredients -->
        <div class="col-md-6">
            <h2>Selected Ingredients <button id="clear-all" class="btn btn-sm btn-outline-secondary ms-2">Clear all</button></h2>
            <ul id="selected-ingredients" class="list-group"></ul>
            <a id="find-recipes" href="<?= $link->url('recipe.index', ['ingredients' => '']) ?>" class="btn btn-primary">Find Recipes</a>
        </div>
    </div>
</div>

<script src="js/script.js"></script>
