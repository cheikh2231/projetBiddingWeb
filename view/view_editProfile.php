<!DOCTYPE html>
<html lang="en">

    <!-- La partie head -->
<head>
    <meta charset="UTF-8">
    
    <title>Edit Profile</title>
       <base href="<?= $web_root ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

 
    <!-- CSS -->
    <link rel="stylesheet" href="css/add-edit.css">
</head>

<!-- la partie body -->
<body>
<!-- Header -->
   <header class="bg-dark border-bottom border-secondary sticky-top ">
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
<div class="container mb-5 py-5">
    <form id="formulaire" action="user/edit_profile" method="POST">
        <!-- bloc 1 -->
        <div class="card-custom mb-4">
            <div class="card-header-custom">Personal Information</div>
            <div class="card-body p-4">
                <div class="mb-3">
                    <label class="form-label">Full Name *</label><br>
                    <input name="fullname" type="text" value="<?=  $user->getName() ?>" placeholder="Your name" class="form-control custom-input"><br>
                    <small class = "text-white-50">Your complete name as it should appear on your profile.</small>
                </div>
                 <?php if(isset($errors['fullName']))  :?>
                    <small class= "text-danger"><?=  $errors['fullName'] ?></small>
                <?php endif?>   
                <div class="mb-3">
                    <label class="form-label">Username *</label><br>
                    <input name="pseudo" type="text" value="<?=  $user->getPseudo() ?>" placeholder="Pseudo" class="form-control custom-input"><br>
                    <small class = "text-white-50">This will be your public display on the platform.</small>
                </div>
                 <?php if(isset($errors['pseudo']))  :?>
                    <small class= "text-danger"><?=  $errors['pseudo'] ?></small>
                <?php endif?>   
            </div>
        </div>
        <!-- bloc 2 -->
        <div class="card-custom mb-4">
            <div class="card-header-custom">Contact & Payment</div>
            <div class="card-body p-4">
                <div class="mb-3">
                    <label class="form-label">Email Address *</label><br>
                    <input name="email" type="text" value="<?=  $user->getEmail() ?> " placeholder="Email" class="form-control custom-input"><br>
                    <small class = "text-white-50">We will use this email to send you notifications about your sales and purchases.</small>
                </div>
                 <?php if(isset($errors['email']))  :?>
                    <small class= "text-danger"><?=  $errors['email'] ?></small>
                <?php endif?>   
                <div class="mb-3">
                    <label class="form-label">IBAN&nbsp;</label><label class="form-label text-secondary">(Optional)</label><br>
                    <input name="iban" type="text" value="<?=  $user->getIban() ?>" class="form-control custom-input" placeholder="BE99 9999 9999 9999"><br>
                    <small class = "text-white-50">For receiving payments from sales. Leave empty if you don't want to receive payment directly.</small>
                </div>
                 <?php if(isset($errors['iban']))  :?>
                    <small class= "text-danger"><?=  $errors['iban'] ?></small>
                <?php endif?>   
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