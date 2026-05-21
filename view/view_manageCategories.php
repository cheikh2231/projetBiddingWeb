<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>manage categories</title>
    <base href="<?= $web_root ?>" >
    <link href="css/browse-item.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>

<body>
<header class="bg-dark border-bottom border-secondary sticky-top">
    <div class="container d-flex justify-content-center align-items-center py-2">
        <h5 class="m-0 me-1 text-primary">Categories</h5>
        <i class="bi bi-cart4 fs-4 text-white"></i>
    </div>
</header>

<div class="container py-4">
    <div style="max-width: 800px; margin: auto;">

        <?php foreach ($allCategories as $cat) :?>
            <div class="mb-3"> <form method="POST" action="category/manage/<?= $cat->getId() ?>" 
                      class="category-row d-flex align-items-center p-2 border border-secondary rounded bg-dark">
                    
                    <input type="text" name="catName"
                        class="form-control border-0 bg-transparent text-white flex-grow-1"
                        value="<?= htmlspecialchars($values[$cat->getId()] ?? $cat->getName()) ?>">

                    <div class="btn-group-custom d-flex flex-shrink-0 ms-2">
                        <button type="submit" name="up" class="btn btn-outline-secondary btn-sm me-1" <?= $cat->isFirst() ? 'disabled' : '' ?>>Up</button>
                        
                        <button type="submit" name="down" class="btn btn-outline-secondary btn-sm me-1" <?= $cat->isLast() ? 'disabled' : '' ?>>Down</button>

                        <button type="submit" name="save" class="btn btn-outline-success btn-sm me-1">Save</button>

                        <a href="category/delete/<?= $cat->getId() ?>" 
                           class="btn btn-outline-danger btn-sm <?= $cat->isNotUsed() ? '' : 'disabled' ?>">Delete</a>
                    </div>
                </form>

                <?php if (!empty($errors[$cat->getId()])): ?>
                    <div class="ms-2 mt-1">
                        <?php foreach ($errors[$cat->getId()] as $e): ?>
                            <small class="text-danger d-block"><?= $e ?></small>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        <?php endforeach;?>

        <hr class="border-secondary my-4" style="border-style: dashed;">

        <form method="POST" action="category/manage" 
            class="category-row d-flex align-items-center p-2 border border-secondary rounded bg-dark">
            <input type="text" name="catName"
                class="form-control border-0 bg-transparent text-white flex-grow-1"
                placeholder="Category name" 
                value="<?= htmlspecialchars($values['new'] ?? 'Category name') ?>"> <button type="submit" name="add" class="btn btn-primary ms-2">
                <i class="bi bi-plus-lg"></i>
            </button>
        </form>

        <?php if (!empty($errors['new'])): ?>
            <div class="ms-2 mt-1">
                <?php foreach ($errors['new'] as $e): ?>
                    <small class="text-danger d-block"><?= $e ?></small>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

    </div>
</div>
<?php include 'view/utilités/footer.php'; ?>
<script src="js/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>