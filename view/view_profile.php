<?php
 $uPic = $user->getPicturePath();
?>

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
    <base href="<?= $web_root ?>"/>
    <!-- CSS -->
    <link href="css/add-edit.css" rel="stylesheet" type="text/css"/>
</head>

<!-- la partie body -->
<body>
    <!-- Header -->
   <header class="bg-dark border-bottom border-secondary sticky-top mb-5">
    <div class="container d-flex justify-content-center align-items-center">
        <h5 class="m-0 me-1 text-primary">Profile</h5>
        <i class="bi bi-cart4 fs-4 text-white"></i>
    </div>
</header>

    <!--centre de la page-->
<div class="container mb-5">
    <!--1er rectangle-->
    <div class="card-custom mb-4 ">
        <div class="card-body p-4 d-flex justify-content-center align-items-center">
            <?= $uPic ? '<img src="'.$uPic.'" class="rounded-circle me-3" style="width:3rem;height:3rem;object-fit:cover;">' : '<i class="bi bi-person-circle fs-1 me-3 text-secondary"></i>' ?>
        </div>
        <div class=" d-flex justify-content-center align-items-center">
            <h2><?=  $user->getName() ?></h2>
        </div>
        <div class=" d-flex justify-content-center align-items-center">
            <small class = "text-muted">@<?=  $user->getPseudo() ?></small>
        </div>
        <div class="mb-5 d-flex justify-content-center align-items-center">
            <small class = "text-muted"><?=  $user->getEmail() ?></small>
        </div>    
    </div> 
    <!--2e rectangle-->
    <div class="card-custom mb-4">
        <div class="card-header-custom">My activities</div>
        <div class="card-body p-4">
            <div class="card-body p-4 d-grid gap-2  "> 
                <a href="item/sales" class="btn btn-outline-dark text-primary"><i class="bi bi-card-list"></i>Sales</a>
                <a href="item/purchases" class="btn btn-outline-dark text-primary"><i class="bi bi-cart3"></i>Purchases</a>
            </div>
        </div> 
    </div>
    <!--3e rectangle-->
    <div class="card-custom mb-4">
        <div class="card-header-custom">Account settings</div>
        <div class="card-body p-4">
            <div class="card-body p-4 d-grid gap-2"> 
            <a href="user/edit_profile" class="btn btn-outline-dark text-primary"><i class="bi bi-person-gear"></i>Edit profile</a>
            <a href="user/edit_password" class="btn btn-outline-dark text-primary"><i class="bi bi-three-dots"></i>Change Password</a>
            <a href="#" class="btn btn-outline-dark text-primary"><i class="bi bi-person-circle"></i>Profile Picture</a>  
            <a href="user/logout" class="btn btn-danger mt-2"><i class="bi bi-box-arrow-right"></i>Logout</a>
            </div>
        </div>
    </div>
</div>

<!--Footer -->
<?php include('view/utilités/footer.php')?>

</body>
</html>