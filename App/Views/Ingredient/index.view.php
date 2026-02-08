<?php
/** @var \Framework\Core\IAuthenticator $auth */
/** @var \Framework\Support\LinkGenerator $link */
/** @var Array $ingredients */
?>

<?php foreach ($ingredients as $ingredient) : ?>
    <?= $ingredient->getId() ?>
    <?= $ingredient->getName() ?>
    <?= $ingredient->getCategoryName() ?>
<?php endforeach ?>
