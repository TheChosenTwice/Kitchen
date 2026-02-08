<?php
/** @var \Framework\Core\IAuthenticator $auth */
/** @var \Framework\Support\LinkGenerator $link */
/** @var Array $categories */
?>

<div class="container py-4">
    <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
        <div>
            <h1 class="h3 mb-1">Ingredient Categories</h1>
            <div class="text-muted">Manage categories used to group ingredients.</div>
        </div>

        <div class="d-flex gap-2">
            <a class="btn btn-outline-secondary" href="<?= $link->url('admin.index') ?>">Back to admin</a>
            <a class="btn btn-primary" href="<?= $link->url('category.create') ?>">New category</a>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-hover mb-0 align-middle">
                    <thead class="table-light">
                    <tr>
                        <th style="width: 90px;">ID</th>
                        <th>Name</th>
                        <th class="text-end" style="width: 220px;">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($categories as $category) : ?>
                        <tr>
                            <td class="text-muted">#<?= $category->getId(); ?></td>
                            <td>
                                <div class="fw-semibold"><?= $category->getName() ?></div>
                            </td>
                            <td class="text-end">
                                <div class="btn-group btn-group-sm" role="group">
                                    <a class="btn btn-outline-primary"
                                       href="<?= $link->url('category.edit', ['id' => $category->getId()]) ?>">
                                        Edit
                                    </a>
                                    <a class="btn btn-outline-danger"
                                       href="<?= $link->url('category.delete', ['id' => $category->getId()]) ?>"
                                       onclick="return confirm('Are you sure you want to delete this category?');">
                                        Delete
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
