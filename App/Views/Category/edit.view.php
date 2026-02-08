<?php
/** @var \Framework\Core\IAuthenticator $auth */
/** @var \Framework\Support\LinkGenerator $link */
/** @var string|null $message */
/** @var \App\Models\Category $category */
?>

<div class="container">
    <div class="row">
        <div class="col-sm-9 col-md-7 col-lg-5 mx-auto">
            <div class="card card-signin my-5">
                <div class="card-body">
                    <h5 class="card-title text-center">Create new category</h5>

                    <div class="text-center text-danger mb-3">
                        <?= @$message ?>
                    </div>

                    <form class="form-signin" method="post" action="<?= $link->url('category.edit', ['id' => $category->getId()]) ?>">
                        <div class="form-label-group mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input name="name" type="text" id="name" class="form-control" maxlength="100" placeholder="Name"
                                   value="<?= $category->getName() ?>" required autofocus>
                        </div>

                        <div class="text-center">
                            <button class="btn btn-primary" type="submit" name="submit">Save</button>
                        </div>
                    </form>

                    <div class="text-center mt-3">
                        <a href="<?= $link->url("category.index") ?>" class="btn btn-link">Go back</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
