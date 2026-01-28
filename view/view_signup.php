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
    <link rel="stylesheet" href="<?= $web_root ?>css/LoginStyle.css">
</head>

<!-- la partie body -->
<body class="d-flex flex-column justify-content-center align-items-center vh-100">
    <!-- Partie bloc de la page -->
    <div class="logContainer text-center shadow-lg">
        <h1 class="mb-3 fs-3">Sign in</h1>
        <hr class="mb-4" style="border-color: #444; opacity: 1;">
        <!-- début du formulaire -->
        <form action="login/login" method="post">
            <div class="input-group mb-3">
                <span class="input-group-text custom-input-icon"><i class="bi bi-envelope"></i></span>
                <input type="text" name="email" class="form-control custom-input" placeholder="Email" aria-label="Email">
            </div>

            <div class="input-group mb-3">
                <span class="input-group-text custom-input-icon"><i class="bi bi-person"></i></span>
                <input type="text" name="fullName" class="form-control custom-input" placeholder="Full Name" aria-label="FullName">
            </div>

            <div class="input-group mb-3">
                <span class="input-group-text custom-input-icon"><i class="bi bi-person-circle"></i></span>
                <input type="text" name="pseudo" class="form-control custom-input" placeholder="Pseudo" aria-label="Pseudo">
            </div>

            <div class="input-group mb-3">
                <span class="input-group-text custom-input-icon"><i class="bi bi-key"></i></span>
                <input type="password" name="password" class="form-control custom-input" placeholder="Password" aria-label="Password">
            </div>

            <div class="input-group mb-3">
                <span class="input-group-text custom-input-icon"><i class="bi bi-key"></i></span>
                <input type="password" name="confirmPassword" class="form-control custom-input" placeholder="Confirm your password" aria-label="ConfirmPassword">
            </div>
            
            <div class="d-grid gap-2 mb-3">
                <button type="submit" class="btn btn-primary custom-btn-blue">Sign up</button>
                <a href="login" class="btn btn-outline-danger">Cancel</a>     
            </div>
        </form>
    </div>
    

</body>
</html>