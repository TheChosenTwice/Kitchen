<?php
/** @var LinkGenerator $link */
/** @var IAuthenticator $auth */
/** @var string|null $message */
/** @var Array $categories */
/** @var Array $ingredients */
/** @var Array $recipe */
/** @var Array $recipeIngredients */

use Framework\Core\IAuthenticator;
use Framework\Support\LinkGenerator;

?>
<div class="container">
    <div class="row">
        <div class="col-sm-9 col-md-7 col-lg-5 mx-auto">
            <div class="card card-signin my-5">
                <div class="card-body">
                    <h5 class="card-title text-center">Post recipe</h5>

                    <div class="text-center text-danger mb-3">
                        <?= @$message ?>
                    </div>

                    <form class="form-signin" method="post" action="<?= $link->url("saveUpdate", ["id" => $recipe->getId()]) ?>" enctype="multipart/form-data">
                        <div class="form-label-group mb-3">
                            <label for="title" class="form-label">Recipe title *</label>
                            <input name="title" type="text" id="title" class="form-control" placeholder="Title" maxlength="255"
                                   value="<?= $recipe->getTitle() ?>" required
                                   autofocus>
                        </div>

                        <div class="form-label-group mb-3">
                            <label for="cooking_time" class="form-label">Cooking time</label>
                            <input name="cooking_time" type="number" id="cooking_time" class="form-control" min="0" max="10000"
                                   placeholder="Minutes" value="<?= $recipe->getCookingTime() ?>">
                        </div>

                        <div class="form-label-group mb-3">
                            <label for="category" class="form-label">Category</label>
                            <input name="category" type="text" id="category" class="form-control" maxlength="64"
                                   placeholder="breakfast, lunch, dinner, dessert, snack ..." value="<?= $recipe->getCategory() ?>">
                        </div>

                        <div class="form-label-group mb-3">
                            <label for="serving_size" class="form-label">Serving size *</label>
                            <input name="serving_size" type="number" id="serving_size" class="form-control" min="1" max="1000"
                                   placeholder="Number of servings" value="<?= $recipe->getServingSize() ?>" required>
                        </div>

                        <!-- Collapse button-->
                        <button class="btn btn-primary" type="button" data-bs-toggle="collapse" data-bs-target="#ingredientsCollapse" aria-expanded="false" aria-controls="ingredientsCollapse">
                            Ingredients
                        </button>
                        <div class="collapse mt-2" id="ingredientsCollapse">
                            <div class="card card-body">
                                <?php foreach ($categories as $category) : ?>
                                    <h6 class="text-success mt-3"><?= $category->getName() ?></h6>

                                    <?php foreach ($ingredients as $ingredient) : ?>
                                        <?php if ($ingredient->getCategoryId() === $category->getId()) : ?>
                                            <label><input type="checkbox" name="ingredients[]" value="<?= $ingredient->getId() ?>"
                                                    <?= in_array($ingredient->getId(), $recipeIngredients, true) ? 'checked' : '' ?>><?= $ingredient->getName() ?></label><br>
                                        <?php endif; ?>
                                    <?php endforeach ?>
                                <?php endforeach ?>
                            </div>
                        </div>

                        <div class="form-label-group mb-3">
                            <label for="instructions" class="form-label">Instructions *</label>
                            <textarea name="instructions" id="instructions" class="form-control"
                                      placeholder="Instructions" required><?= $recipe->getInstructions() ?></textarea>
                        </div>

                        <div class="form-label-group mb-3">
                            <label for="image" class="form-label">Image</label>
                            <input name="image" type="file" id="image" class="form-control" accept="image/*">
                        </div>
                        <input name="recipe_id" type="hidden" value="<?= $recipe->getId() ?>">
                        <div class="text-center">
                            <button class="btn btn-primary" type="submit" name="submit">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>



