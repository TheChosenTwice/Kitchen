<?php
/** @var \Framework\Support\LinkGenerator $link */
/** @var Array $categories */
/** @var Array $ingredients */
?>

<div class="mt-5">
    <h1 class="text-center">What's in your fridge?</h1>
    <div class="text-start ms-3">
        <?php foreach ($categories as $category) : ?>
            <h2><?= $category->getName() ?></h2>

            <?php foreach ($ingredients as $ingredient) : ?>
                <?php if ($ingredient->getCategoryId() === $category->getId()) : ?>
                    <button class="btn btn-outline-primary btn-sm ingredient-btn">
                        <?= $ingredient->getName() ?>
                    </button>
                <?php endif; ?>
            <?php endforeach ?>
        <?php endforeach ?>
    </div>
</div>
