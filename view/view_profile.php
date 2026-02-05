<?php

 $userPic = $user->getPicturePath();
 $place = "profile";

?>

<!DOCTYPE html>
<html lang="fr">

    <!-- La partie head -->
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <!-- Lien bootstrap -->
     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Icones Bootstrap -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <base href="<?= $web_root ?>"/>
    <!-- CSS -->
    <link href="css/styleProfile.css" rel="stylesheet" type="text/css"/>
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

<div class="profile-container">
    
    <div class="section-box header-card text-center">
        <?= $userPic ? '<img src="'.$userPic.'" class="rounded-circle me-3" style="width:3rem;height:3rem;object-fit:cover;">' : '<i class="bi bi-person-circle fs-1 me-3 text-secondary"></i>' ?>
        <div class="profile-info">
            <h2><?= $user->getName()?></h2>
            <div class="sub">@<?= $user->getPseudo()?></div>
            <div class="sub"><?= $user->getEmail()?></div>
        </div>
    </div>

    <div class="section-box">
        <div class="section-header">My Activities</div>
        <div class="section-body">
            <a href="item/sales" class="menu-card">
                <div class="icon-box"><i class="fa-solid fa-list-check"></i></div>
                <div class="content-box">
                    <h4>Sales</h4>
                    <p>Items you have sold</p>
                </div>
            </a>
            <a href="item/purchases" class="menu-card">
                <div class="icon-box"><i class="fa-solid fa-cart-shopping"></i></div>
                <div class="content-box">
                    <h4>Purchases</h4>
                    <p>Items you have purchased</p>
                </div>
            </a>
        </div>
    </div>

    <div class="section-box">
        <div class="section-header">Account Settings</div>
        <div class="section-body">
            <a href="user/edit_profile" class="menu-card">
                <div class="icon-box"><i class="fa-solid fa-user-pen"></i></div>
                <div class="content-box">
                    <h4>Edit Profile</h4>
                    <p>Update your personal information</p>
                </div>
            </a>
            <a href="user/edit_password" class="menu-card">
                <div class="icon-box"><i class="fa-solid fa-lock"></i></div>
                <div class="content-box">
                    <h4>Change Password</h4>
                    <p>Update your account security</p>
                </div>
            </a>
            <a href="user/edit_profile_picture" class="menu-card">
                <div class="icon-box"><i class="fa-solid fa-image"></i></div>
                <div class="content-box">
                    <h4>Profile Picture</h4>
                    <p>Upload or change your profile picture</p>
                </div>
            </a>
            <a href="user/logout" class="menu-card logout-card">
                <div class="icon-box"><i class="fa-solid fa-power-off"></i></div>
                <div class="content-box">
                    <h4>Logout</h4>
                    <p>Sign out of your account</p>
                </div>
            </a>
        </div>
    </div>

</div>



<!--Footer -->
<?php include('view/utilités/footer.php')?>

</body>
</html>