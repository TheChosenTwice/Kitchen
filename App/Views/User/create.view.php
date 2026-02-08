<?php
/** @var \Framework\Core\IAuthenticator $auth */
/** @var \Framework\Support\LinkGenerator $link */
/** @var string|null $message */
?>

<div class="container">
    <div class="row">
        <div class="col-sm-9 col-md-7 col-lg-5 mx-auto">
            <div class="card card-signin my-5">
                <div class="card-body">
                    <h5 class="card-title text-center">Create new user</h5>

                    <div class="text-center text-danger mb-3">
                        <?= @$message ?>
                    </div>

                    <form class="form-signin" method="post" action="<?= $link->url('user.create') ?>">
                        <div class="form-label-group mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input name="username" type="text" id="username" class="form-control" maxlength="64" placeholder="Username" required autofocus>
                        </div>

                        <div class="form-label-group mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input name="password" type="password" id="password" class="form-control" placeholder="Password" required>
                        </div>

                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" value="1" id="is_admin" name="is_admin">
                            <label class="form-check-label" for="is_admin">
                                Admin
                            </label>
                        </div>

                        <div class="text-center">
                            <button class="btn btn-primary" type="submit" name="submit">Create</button>
                        </div>
                    </form>

                    <div class="text-center mt-3">
                        <a href="<?= $link->url('user.read') ?>" class="btn btn-link">Go back</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
