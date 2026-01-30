
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($item->getTitle()) ?></title>
    <base href="<?= $web_root ?>"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"/>
    <link href="css/browse-item.css" rel="stylesheet" type="text/css"/>
</head>
<body>
    <div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
    <div class="card bg-dark text-white text-center p-4" style="width: 25rem; border-radius: 15px;">
        <div class="card-body">
            <div class="mb-3">
                <i class="bi bi-trash3-fill text-danger" style="font-size: 4rem;"></i>
            </div>
            
            <h3 class="text-danger mb-4">Are you sure?</h3>
            
            <p class="card-text mb-4">
                Do you really want to delete item <strong>"<?= htmlspecialchars($item->getTitle()) ?>"</strong> 
                by <?= User::getUserById($item->getOwner())->getPseudo() ?> and all of its dependencies?
                <br><br>
                <span class="text-secondary">This process cannot be undone.</span>
            </p>

            <form method="POST" class="d-flex justify-content-center gap-3">
                <a href="item/open/<?= $item->getId() ?>" class="btn btn-secondary px-4">Cancel</a>
                
                <button type="submit" name="delete" class="btn btn-danger px-4">Delete</button>
            </form>
        </div>
    </div>
</div>
<?php include 'view/utilités/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>