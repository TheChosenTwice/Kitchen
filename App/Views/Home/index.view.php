<?php
/** @var \Framework\Support\LinkGenerator $link */
/** @var Array $categories */
?>



<div class="text-center mt-5">
    <h1>What's in your fridge?</h1>
    <?php foreach ($categories as $cat) : ?>
        <li><?= $cat->getName() ?></li>
    <?php endforeach ?>
</div>






