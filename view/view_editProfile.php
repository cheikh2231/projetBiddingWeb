<!DOCTYPE html>
<html lang="fr">

    <!-- La partie head -->
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <!-- Lien bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <!-- Icones Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <base href="<?= $web_root ?>"/>
    <!-- CSS -->
    <link rel="stylesheet" href="css/add-edit.css">
</head>

<!-- la partie body -->
<body>
<!-- Header -->
   <header class="bg-dark border-bottom border-secondary sticky-top mb-5">
    <div class="container d-flex justify-content-center align-items-center">
        <h5 class="m-0 me-1 text-primary">Edit Profile</h5>
        <a href="user/profile"><i class="bi bi-arrow-left-short fs-3 text-primary position-absolute start-0 top-0"></i></a>
        <i class="bi bi-cart4 fs-4 text-white"></i>
        <button type="submit" form="formulaire" class="position-absolute end-0 me-3 btn btn-link p-0">
        <i class="bi bi-floppy fs-4 text-primary"></i>
    </button>
    </div>
</header>

<!--centre de la page-->
<div class="container mb-5">
    <form id="formulaire" action="user/edit_profile" method="POST">
        <!-- bloc 1 -->
        <div class="card-custom mb-4">
            <div class="card-header-custom">Personal Information</div>
            <div class="card-body p-4">
                <div class="mb-3">
                    <label class="form-label">Full Name *</label><br>
                    <input name="fullname" type="text" value="<?=  $user->getName() ?>" placeholder="Your name"><br>
                    <small class = "text-muted">Your complete name as it should appear on your profile.</small>
                </div>
                <div class="mb-3">
                    <label class="form-label">Username *</label><br>
                    <input name="pseudo" type="text" value="<?=  $user->getPseudo() ?>" placeholder="Pseudo"><br>
                    <small class = "text-muted">This will be your public display on the platform.</small>
                </div>
            </div>
        </div>
        <!-- bloc 2 -->
        <div class="card-custom mb-4">
            <div class="card-header-custom">Contact & Payment</div>
            <div class="card-body p-4">
                <div class="mb-3">
                    <label class="form-label">Email Address *</label><br>
                    <input name="email" type="text" value="<?=  $user->getEmail() ?> " placeholder="Email"><br>
                    <small class = "text-muted">We will use this email to send you notifications about your sales and purchases.</small>
                </div>
                <div class="mb-3">
                    <label class="form-label">IBAN&nbsp;</label><label class="form-label text-secondary">(Optional)</label><br>
                    <input name="iban" type="text" value="<?=  $user->getIban() ?>" placeholder="BE99 9999 9999 9999"><br>
                    <small class = "text-muted">For receiving payments from sales. Leave empty if you don't want to receive payment directly.</small>
                </div>
            </div>
        </div>
    </form>
</div>                
<!-- Partie erreurs -->
    <?php if(count($errors) != 0):?>
        <div class="alert alert-danger mt-3" role="alert">
            <h2 class="alert-heading fs-5"><i class="bi bi-exclamation-triangle-fill"></i> ERROR</h2>
            <hr>
            <p class="mb-0">
                <?php foreach ($errors as $error): ?>
                    <?= $error ?><br>
                <?php endforeach; ?> 
            </p>
        </div>
    <?php endif; ?>   



<!--Footer -->
<?php include('view/utilités/footer.php')?>

</body>
</html>