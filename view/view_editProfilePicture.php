

<!DOCTYPE html>
<html lang="en">

    <!-- La partie head -->
<head>
    <meta charset="UTF-8">
  
    <title>Edit Profile Picture</title>
      <base href="<?= $web_root ?>">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Lien bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <!-- Icones Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
  
    <!-- CSS -->
    <link href="css/add-edit.css" rel="stylesheet" type="text/css">
</head>

<!-- la partie body -->
<body>
    <!-- Header -->
   <header class="bg-dark border-bottom border-secondary sticky-top">
        <div class="container d-flex justify-content-center align-items-center">
            <h5 class="m-0 me-1 text-primary">Edit Profile Picture</h5>
            <i class="bi bi-cart4 fs-4 text-white"></i>
            <a href="user/profile"><i class="bi bi-arrow-left-short fs-3 text-primary position-absolute start-0 top-0"></i></a>
        </div>
    </header>

    <!--centre de la page-->
<div class="container mb-5 py-5 px-5">
    <!--1er rectangle-->
    <div class="card-header-custom">Current Profile Picture</div>
    <div class="card-custom mb-4 ">
        <div class="card-body p-4 d-flex justify-content-center align-items-center">    
            <?= $userPic ? '<img src="'.$userPic.'" class="rounded-circle me-3" alt = "userProfile" style="width:3rem;height:3rem;object-fit:cover;"> ' : '<i class="bi bi-person-circle fs-1 me-3 text-secondary"></i>' ?>
        </div>
        <div class=" d-flex justify-content-center align-items-center">
            <h2><?=  $currentUser->getName() ?></h2>
        </div>
        <div class=" d-flex justify-content-center align-items-center">
            <small class = "text-white-50">@<?=  $currentUser->getPseudo() ?></small>
        </div>
        <div class="mb-5 d-flex justify-content-center align-items-center">
            <small class = "text-white-50"><?=  $currentUser->getEmail() ?></small>
        </div>    
    </div> 
    <!--2e rectangle-->
    <form id="formulaire" action="user/edit_profile_picture" method="POST" enctype="multipart/form-data">
    <div class="card-custom mb-4 ">
        <div class="card-header-custom">Upload New Picture</div>
        <div class="card-body p-4">
            <div class="mb-3">
                <label class="form-label">Select Image *</label><br>
                <input type="file" name="image" accept=".png, .gif, .jpeg, .jpg, .webp" class="form-control custom-input">
                <?php if(isset($errors['picture']))  :?>
                    <small class= "text-danger"><?=  $errors['picture'] ?></small><br>
                <?php endif?> 
                <small class = "text-white-50">Supported formats: JPG, PNG, GIF, Webp. This will replace your current profile picture.</small><br><br>
                <button type="submit" class="btn btn-primary custom-btn-blue">Upload Picture</button>

            </div>
        </div>        
    </div>
    </form>

    <!-- 3e rectangle -->
    <?php if($userPic):?>
    <form id="delete" action="user/delete_profile_picture" method="POST" enctype="multipart/form-data">
    <div class="card-custom mb-4 ">
        <div class="card-header-custom">Remove Current Picture</div>
        <div class="card-body p-4">
            <div class="mb-3">
                <p class="h5 text-warning">This will permanently delete your current profile picture. You can upload a new one anytime.</p>
                <button type="submit" class="btn btn-danger ">Delete Picture</button>
            </div>
        </div>        
    </div>
    </form>
    <?php endif ?>



</div>

<!--Footer -->
<?php include('view/utilités/footer.php')?>

</body>
</html>