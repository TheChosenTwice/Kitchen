<?php
/** @var \Framework\Support\LinkGenerator $link */
/** @var Array $recipes */
?>

<h1>Your recipes</h1>

<?php if (empty($recipes)) : ?>
    <p class="no-results">Upload your first recipe <a href="<?= $link->url('recipe.create') ?>">here!</a></p>
<?php else: ?>
    <ul class="recipes-grid">
        <?php foreach ($recipes as $recipe) : ?>
            <li class="recipe-card">
                <div class="img">
                    <img src="/images/<?= $recipe->getImage() ?>" alt="<?= $recipe->getTitle()?>">
                </div>
                <div class="recipe-body">
                    <a href="<?= $link->url("recipe.detail", ["id" => $recipe->getId()]) ?>">
                        <?= $recipe->getTitle() ?>
                    </a>
                    <div class="meta"><strong>Cooking Time:</strong> <?= $recipe->getCookingTime() ?> minutes</div>
                </div>
            </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>
