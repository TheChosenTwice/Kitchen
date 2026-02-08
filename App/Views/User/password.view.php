<?php
/** @var string|null $message */
/** @var bool $success */
/** @var \Framework\Support\LinkGenerator $link */
?>

<div class="container">
    <div class="row">
        <div class="col-12 col-md-8 col-lg-6 mx-auto">
            <div class="card my-4">
                <div class="card-body">
                    <h4 class="card-title mb-3">Change password</h4>

                    <?php if (!empty($message)) { ?>
                        <div class="alert <?= $success ? 'alert-success' : 'alert-danger' ?>" role="alert">
                            <?= $message ?>
                        </div>
                    <?php } ?>

                    <form method="post" action="<?= $link->url('user.password') ?>">
                        <div class="mb-3">
                            <label for="old_password" class="form-label">Old password</label>
                            <input type="password" class="form-control" id="old_password" name="old_password" required>
                        </div>

                        <div class="mb-3">
                            <label for="new_password" class="form-label">New password</label>
                            <input type="password" class="form-control" id="new_password" name="new_password" required>
                        </div>

                        <div class="mb-3">
                            <label for="new_password_confirm" class="form-label">Confirm new password</label>
                            <input type="password" class="form-control" id="new_password_confirm" name="new_password_confirm" required>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary" name="submit">Save</button>
                            <a class="btn btn-outline-secondary" href="<?= $link->url('user.index') ?>">Back</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

