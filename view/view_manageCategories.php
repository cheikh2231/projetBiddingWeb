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
    <div class="container d-flex justify-content-center align-items-center">
        <h5 class="m-0 me-1 text-primary">Categories</h5>
        <i class="bi bi-cart4 fs-4 text-white"></i>
    </div>
</header>

<div class="container py-5 px-2">
    <div class="card-manage-categories" style="--nb-categories: <?=count($allCategories)?>;">
        <div class ="p-2">
            <?php foreach ($allCategories as $cat) :?>
                <div class="input-group mb-1" style ="width: 1000px;">
                    <input type="text" class="form-control" placeholder=<?=$cat->getName()?> aria-label=<?=$cat->getName()?>>
                </div>
            <?php endforeach;?>    
        </div>
    </div>
</div>


<?php include 'view/utilités/footer.php'; ?>
    <script src="js/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>