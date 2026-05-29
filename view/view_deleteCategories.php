<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete category</title>
    <base href="<?= $web_root ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
<header class="bg-dark border-bottom border-secondary sticky-top">
    <div class="container d-flex justify-content-center align-items-center">
        <h5 class="m-0 me-1 text-primary">Categories</h5>
    </div>
</header>

<div class="container py-5 d-flex justify-content-center">
    <div class="card bg-dark border-secondary text-white p-4" style="max-width: 500px; width: 100%;">
        <h5 class="mb-3">Confirm deletion</h5>
        <p>Are you sure you want to delete the category <strong><?= $cat->getName() ?></strong> ?</p>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger">
                <?php foreach ($errors as $e): ?>
                    <p class="mb-0"><?= $e ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="category/delete/<?= $cat->getId() ?>" class="d-flex gap-2 mt-3">
            <button type="submit" name="delete" class="btn btn-danger">Yes, delete</button>
            <a href="category/manage" class="btn btn-outline-secondary">Cancel</a>
        </form>
    </div>
</div>

</body>
</html>