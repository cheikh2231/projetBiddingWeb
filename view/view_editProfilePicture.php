<?php
 $uPic = $user->getPicturePath();
?>

<!DOCTYPE html>
<html lang="fr">

    <!-- La partie head -->
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile Picture</title>
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
        <h5 class="m-0 me-1 text-primary">Edit Profile Picture</h5>
        <i class="bi bi-cart4 fs-4 text-white"></i>
    </div>
</header>

    <!--centre de la page-->
<div class="container mb-5">
    <!--1er rectangle-->
    <div class="card-header-custom">Current Profile Picture</div>
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
    <form id="formulaire" action="user/edit_profile_picture" method="POST">
    <div class="card-custom mb-4 ">
        <div class="card-header-custom">Upload New Picture</div>
        <div class="card-body p-4">
            <div class="mb-3">
                <label class="form-label">Select Image *</label><br>
                <input type="file" name="image" accept="image/x-png, image/gif, image/jpeg, image/webp"><br>
                <small class = "text-muted">Supported formats: JPG, PNG, GIF, Webp. This will replace your current profile picture.</small><br><br>
                <button type="submit" class="btn btn-primary custom-btn-blue">Upload Picture</button>

            </div>
        </div>        
    </div>
    </form>
</div>

<!--Footer -->
<?php include('view/utilités/footer.php')?>

</body>
</html>