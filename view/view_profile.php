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
    <link rel="stylesheet" href="<?= $web_root ?>css/add-edit.css">
</head>
<!-- la partie body -->
<body class="d-flex flex-column justify-content-center align-items-center vh-100">
    <!-- Header -->
   <header class="bg-dark border-bottom border-secondary sticky-top ">
    <div class="container d-flex justify-content-center align-items-center">
        <h5 class="m-0 me-1 text-primary">Profile</h5>
        <i class="bi bi-cart4 fs-4 text-white"></i>
    </div>
    <div class="p-3">
    </div>
</header>

    <!--centre de la page-->
<div class="container mb-5">
    <!--1er rectangle-->
    <div class="card-custom mb-4">
        <div class="card-body p-4">
            <img src='upload/<?=  $user->getId() ?>/<?=  $user->getPicturePath() ?>'>
            <h2><?=  $user->getName() ?></h2>
            <h3><?=  $user->getPseudo() ?></h3>
            <h3><?=  $user->getEmail() ?></h3>
        </div>
   </div>
    <!--2e rectangle-->
    <div class="card-custom mb-4">
        <div class="card-header-custom">My activities</div>
        <div class="card-body p-4">
            <div class="card-body p-4 d-grid gap-2"> 
                <a href="#" class="btn btn-outline-dark">Sales</a>
                <a href="#" class="btn btn-outline-dark">Purchases</a>
            </div>
        </div> 
    </div>
    <!--3e rectangle-->
    <div class="card-custom mb-4">
        <div class="card-header-custom">Account settings</div>
        <div class="card-body p-4">
            <div class="card-body p-4 d-grid gap-2"> 
            <a href="#" class="btn btn-outline-dark">Edit profile</a>
            <a href="#" class="btn btn-outline-dark">Change Password</a>
            <a href="#" class="btn btn-outline-dark">Profile Picture</a>  
            <a href="<?= $web_root ?>profile/logout" class="btn btn-danger mt-2">Logout</a>
            </div>
        </div>
    </div>


</div>
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