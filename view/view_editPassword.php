<!DOCTYPE html>
<html lang="en">

    <!-- La partie head -->
<head>
    <meta charset="UTF-8">
    
    <title>Edit Password</title>
    <base href="<?= $web_root ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Lien bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <!-- Icones Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <!-- CSS -->
    <link rel="stylesheet" href="css/add-edit.css">
</head>

<!-- la partie body -->
<body>
<!-- Header -->
   <header class="bg-dark border-bottom border-secondary sticky-top mb-5">
    <div class="container d-flex justify-content-center align-items-center">
        <h5 class="m-0 me-1 text-primary">Edit Password</h5>
        <a href="user/profile"><i class="bi bi-arrow-left-short fs-3 text-primary position-absolute start-0 top-0"></i></a>
        <i class="bi bi-cart4 fs-4 text-white"></i>
        <button type="submit" form="formulaire" class="position-absolute end-0 me-3 btn btn-link p-0">
        <i class="bi bi-floppy fs-4 text-primary"></i>
    </button>
    </div>
</header>

<!--centre de la page-->
<div class="container mb-5">
    <form id="formulaire" action="user/edit_password" method="POST">
        <!-- bloc 1 -->
        <div class="card-custom mb-4">
            <div class="card-header-custom">Current Password</div>
            <div class="card-body p-4">
                <div class="mb-3">
                    <label class="form-label">Current Password *</label><br>
                    <input name="currentPassword" type="password" placeholder="Enter your current password" class="form-control custom-input">
                    <?php if(isset($errors['currentPassword']))  :?>
                        <small class= "text-danger"><?=  $errors['currentPassword'] ?></small><br>
                    <?php endif?>
                    <small class = "text-white-50">Enter your current password to verify your identity.</small>
                </div>
                  
            </div>
        </div>
        <!-- bloc 2 -->
        <div class="card-custom mb-4">
            <div class="card-header-custom">New Password</div>
            <div class="card-body p-4">
                <div class="mb-3">
                    <label class="form-label">New Password *</label><br>
                    <input name="newPassword" type="password" placeholder="Enter your new password" class="form-control custom-input">
                    <?php if(isset($errors['newPassword']))  :?>
                        <small class= "text-danger"><?=  $errors['newPassword'] ?></small><br>
                    <?php endif?>
                    <small class = "text-white-50">Password must be 8-16 characters with uppercase, number and punctuation.</small>
                </div>
              
                <div class="mb-3">
                    <label class="form-label">Confirm New Password *</label><label class="form-label text-secondary">(Optional)</label><br>
                    <input name="confirmNewPassword" type="password" placeholder="Confirm your new password" class="form-control custom-input">
                    <?php if(isset($errors['confirmNewPassword']))  :?>
                    <small class= "text-danger"><?=  $errors['confirmNewPassword'] ?></small><br>
                <?php endif?>
                    <small class = "text-white-50">Re-enter your new password to confirm it matches.</small>
                </div>
                
            </div>
        </div>

    </form>
</div>        
<?php if(isset($errors['champs']))  :?>
    <small class= "text-danger"><?=  $errors['champs'] ?></small>
<?php endif?>



<!--Footer -->
<?php include('view/utilités/footer.php')?>

</body>
</html>