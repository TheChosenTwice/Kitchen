<?php
/** @var \Framework\Support\LinkGenerator $link */
/** @var Array $recipes */
?>

<h1>My recipes</h1>

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
                    <div style="margin-top: 8px;">
                        <a href="<?= $link->url('recipe.update', ['id' => $recipe->getId()]) ?>" class="btn btn-primary" style="margin-right: 8px;">Edit</a>
                        <form method="post" action="<?= $link->url('recipe.delete', ['id' => $recipe->getId()]) ?>" onsubmit="return confirm('Are you sure you want to delete this recipe?');" style="display:inline;">
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                    </div>
                </div>
            </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>
