<!DOCTYPE html>
<html lang="fr">
<!-- La partie head -->
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup</title>
    <!-- Lien bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <!-- Icones Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <!-- CSS -->
    <link rel="stylesheet" href="<?= $web_root ?>css/styles.css">
</head>

<!-- la partie body -->
<body class="d-flex flex-column justify-content-center align-items-center vh-100">
    <!-- Partie bloc de la page -->
    <div class="logContainer text-center shadow-lg">
        <h1 class="mb-3 fs-3">Sign up</h1>
        <hr class="mb-4" style="border-color: #444; opacity: 1;">

        <!-- début du formulaire -->
        <form action="<?= $web_root ?>user/signup" method="post">
            <div class="input-group mb-3">
                <span class="input-group-text custom-input-icon"><i class="bi bi-envelope"></i></span>
                <input type="text" name="email" value="<?= $email??'' ?>" class="form-control custom-input" placeholder="Email" aria-label="Email">
            </div>
                <?php if(isset($errors['email']))  :?>
                    <small class= "text-danger"><?=  $errors['email'] ?></small>
                <?php endif?>

            <div class="input-group mb-3">
                <span class="input-group-text custom-input-icon"><i class="bi bi-person"></i></span>
                <input type="text" name="fullname" value="<?= $fullname??'' ?>" class="form-control custom-input" placeholder="Full Name" aria-label="FullName">
            </div>
                <?php if(isset($errors['fullName']))  :?>
                    <small class= "text-danger"><?=  $errors['fullName'] ?></small>
                <?php endif?>

            <div class="input-group mb-3">
                <span class="input-group-text custom-input-icon"><i class="bi bi-person-circle"></i></span>
                <input type="text" name="pseudo" value="<?= $pseudo??'' ?>" class="form-control custom-input" placeholder="Pseudo" aria-label="Pseudo">
            </div>
                
                <?php if(isset($errors['pseudo']))  :?>
                    <small class= "text-danger"><?=  $errors['pseudo'] ?></small>
                <?php endif?>

            <div class="input-group mb-3">
                <span class="input-group-text custom-input-icon"><i class="bi bi-key"></i></span>
                <input type="password" name="password" class="form-control custom-input" placeholder="Password" aria-label="Password">
            </div>
                <?php if(isset($errors['password']))  :?>
                    <small class= "text-danger"><?=  $errors['password'] ?></small>
                <?php endif?>

            <div class="input-group mb-3">
                <span class="input-group-text custom-input-icon"><i class="bi bi-key"></i></span>
                <input type="password" name="confirmPassword" class="form-control custom-input" placeholder="Confirm your password" aria-label="ConfirmPassword">
            </div>
                <?php if(isset($errors['confirmPassword']))  :?>
                    <small class= "text-danger"><?=  $errors['confirmPassword'] ?></small>
                <?php endif?>
            
            <div class="d-grid gap-2 mb-3">
                <button type="submit" class="btn btn-primary custom-btn-blue">Sign up</button>
                <a href="login" class="btn btn-outline-danger">Cancel</a>     
            </div>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    
   <?php if(isset($errors['champs']))  :?>
        <small class= "text-danger"><?=  $errors['champs'] ?></small>
    <?php endif?>
</body>
</html>