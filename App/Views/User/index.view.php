<?php
/** @var \Framework\Core\IAuthenticator $auth */
/** @var \Framework\Support\LinkGenerator $link */
/** @var \App\Models\User $user */
?>

<div class="container">
    <div class="row">
        <div class="col-12 col-md-8 col-lg-6 mx-auto">
            <div class="card my-4">
                <div class="card-body">
                    <h4 class="card-title mb-3">My account</h4>

                    <dl class="row mb-4">
                        <dt class="col-sm-4">Username</dt>
                        <dd class="col-sm-8"><?= $user->getName() ?></dd>

                        <dt class="col-sm-4">User ID</dt>
                        <dd class="col-sm-8"><?= $user->getId() ?></dd>

                        <dt class="col-sm-4">Created</dt>
                        <dd class="col-sm-8"><?= $user->getCreatedAt() ?></dd>
                    </dl>

                    <a class="btn btn-primary" href="<?= $link->url('user.password') ?>">Change password</a>
                </div>
            </div>
        </div>
    </div>
</div>
