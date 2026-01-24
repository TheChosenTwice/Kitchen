<?php
/** @var \Framework\Support\LinkGenerator $link */
/** @var array $recipes */
?>

<h1>Recipe Results</h1>

<?php if (empty($recipes)) : ?>
    <p>No recipes found.</p>
<?php else: ?>
    <?php foreach ($recipes as $recipe) : ?>
        <li>
            <img src="/images/<?= $recipe['image'] ?>" style="max-width:80px; max-height:100px;" alt="Recipe Image"><br>
            <?= $recipe['title'] ?><br>
            <strong>Cooking time:</strong> <?= $recipe['cooking_time'] ?> minutes<br>
            <strong>Missing ingredients:</strong> <?= $recipe['num_of_ingredients'] - $recipe['num_of_ingredients_met'] ?><br>
            <strong>Number of ingredients:</strong> <?= $recipe['num_of_ingredients'] ?><br>
            <strong>Number of ingredients met:</strong> <?= $recipe['num_of_ingredients_met'] ?>
        </li>
    <?php endforeach; ?>
<?php endif; ?>
