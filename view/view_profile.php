<!DOCTYPE html>
<html lang="fr">

    <!-- La partie head -->
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <!-- Lien bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <!-- Icones Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <!-- CSS -->
    <link rel="stylesheet" href="<?= $web_root ?>css/styles.css">
</head>
<!-- la partie body -->
<body class="d-flex flex-column justify-content-center align-items-center vh-100">
    <!-- Header -->
    <header class="bg-dark p-1 d-flex justify-content-center align-items-center border-bottom border-secondary mb-4 sticky-top position-relative">
    <a href="<?= $web_root ?>" class="position-absolute start-0 ms-2">
        <i class="bi bi-arrow-left-short fs-3 text-primary"></i>
    </a>

    <div class="d-flex align-items-center">
        <h5 class="m-0 me-2 text-primary"><?= $titlePage ?></h5>
        <i class="bi bi-cart4 fs-4 text-white"></i>
    </div>

    <button type="submit" form="editItemForm" class="position-absolute end-0 me-3 btn btn-link p-0">
        <i class="bi bi-floppy fs-4 text-primary"></i>
    </button>
</header>



<!--Footer -->
<footer class="footer fixed-bottom bg-dark border-top border-secondary p-0">
    <div class="container">
        <div class="row text-center ">
            <div class="col">
                <a href="item/browse" class="text-decoration-none <?= ($source === 'browse') ? 'text-primary' : 'text-white' ?>">
                    <i class="bi bi-search fs-4 d-block"></i>
                    <span style="font-size: 0.65rem;" class="text-uppercase fw-bold">Browse</span>
                </a>
            </div>
            <div class="col">
                <a href="item/list" class="text-decoration-none <?= ($source === 'items') ? 'text-primary' : 'text-white' ?>">
                    <i class="bi bi-house-door fs-4 d-block"></i>
                    <span style="font-size: 0.65rem;" class="text-uppercase fw-bold">My Items</span>
                </a>
            </div>
            <div class="col">
                <a href="item/add" class="text-decoration-none text-white">
                    <i class="bi bi-plus-lg fs-4 d-block"></i>
                    <span style="font-size: 0.65rem;" class="text-uppercase fw-bold">Add Offer</span>
                </a>
            </div>
            <div class="col">
                <a href="<?= $web_root ?>profile" class="text-decoration-none text-white">
                    <i class="bi bi-gear fs-4 d-block"></i>
                    <span style="font-size: 0.65rem;" class="text-uppercase fw-bold">Profile</span>
                </a>
            </div>
        </div>
    </div>
</footer>

</body>
</html>