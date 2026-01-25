<?php
/** @var \Framework\Support\LinkGenerator $link */
/** @var Array $recipes */
?>

<h1>Recipe Results</h1>

<?php if (empty($recipes)) : ?>
    <p class="no-results">No recipes found.</p>
<?php else: ?>
    <ul class="recipes-grid">
        <?php foreach ($recipes as $recipe) : ?>
            <li class="recipe-card">
                <div class="img">
                    <img src="/images/<?= $recipe['image'] ?>" alt="<?= $recipe['title'] ?>">
                </div>
                <div class="recipe-body">
                    <a href="<?= $link->url("recipe.recipeDetail", ["id" => $recipe['id']]) ?>">
                        <?= $recipe['title'] ?>
                    </a>
                    <div class="meta"><strong>Cooking Time:</strong> <?= $recipe['cooking_time'] ?> minutes</div>
                    <?php $missing = $recipe['num_of_ingredients'] - $recipe['num_of_ingredients_met'] ?>
                    <div class="meta">
                        <?php if ($missing === 0) : ?>
                            <span class="badge">No missing ingredients!</span>
                        <?php else: ?>
                            <span class="badge"><?= $missing ?> missing ingredients</span>
                        <?php endif; ?>
                        <a class="btn" href="#">Log in to bookmark!</a>
                    </div>
                </div>
            </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>
