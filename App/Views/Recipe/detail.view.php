<?php
/** @var \Framework\Core\IAuthenticator $auth */
/** @var \Framework\Support\LinkGenerator $link */
/** @var Array $recipe */
/** @var Array $ingredients */
/** @var bool $isBookmarked */
?>

<style>
.recipe-detail { max-width: 760px; margin: 0 auto; font-family: Arial, Helvetica, sans-serif; }
.recipe-title { text-align: center; font-size: 1.6rem; margin: 18px 0; }
.stats { display: grid; grid-template-columns: repeat(3,1fr); gap: 0; background:#f88a3a; color:#fff; padding:12px; border-radius:4px; text-align:center; }
.stat { padding:8px 6px; }
.stat small { display:block; font-size:0.75rem; opacity:0.95; }
.recipe-image { display:block; margin:18px auto; max-width:320px; width:100%; border-radius:4px; }
.btn-bookmark { display:block; margin:12px auto; background:#f88a3a; color:#fff; border:1px solid #f88a3a; padding:8px 18px; border-radius:4px; font-weight:700; cursor:pointer; transition:background 0.2s,color 0.2s; text-align:center; width:max-content; text-decoration:none; }
.btn-bookmark:hover { background:#fff; color:#f88a3a; text-decoration:none; }
.section { margin:18px 0; }
.section h3 { margin:0 0 8px 0; font-size:0.95rem; }
.ingredients { padding-left:18px; }
.instructions { white-space:pre-wrap; }
</style>

<div class="recipe-detail">
    <h1 class="recipe-title"><?= $recipe['title'] ?? '' ?></h1>

    <div class="stats">
        <div class="stat">
            <small>Cook Time</small>
            <strong><?= $recipe['cooking_time'] ?? '-' ?></strong>
        </div>
        <div class="stat">
            <small>Category</small>
            <strong><?= $recipe['category'] ?? '-' ?></strong>
        </div>
        <div class="stat">
            <small>Serving Size</small>
            <strong><?= $recipe['serving_size'] ?? '-' ?></strong>
        </div>
    </div>

    <img class="recipe-image" src="/images/<?= $recipe['image'] ?? 'no-image.png' ?>" alt="<?= $recipe['title'] ?? '' ?>">

    <div style="display:flex; justify-content:center;">
        <a class="btn-bookmark"
           href="<?= $link->url("recipe.bookmark", ["id" => $recipe['id']]) ?>">
            <?= $isBookmarked ? 'Remove Bookmark' : 'Bookmark Recipe' ?>
        </a>
    </div>
    <div class="section">
        <h3>Ingredients</h3>
        <?php if (!empty($ingredients)) : ?>
            <ul class="ingredients">
                <?php foreach ($ingredients as $ingredient) : ?>
                    <li><?= $ingredient['name'] ?? '' ?></li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <div class="no-results">No ingredients listed.</div>
        <?php endif; ?>
    </div>

    <div class="section">
        <h3>Instructions</h3>
        <div class="instructions"><?= $recipe['instructions'] ?? '' ?></div>
    </div>
</div>
