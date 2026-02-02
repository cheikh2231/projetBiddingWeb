<!DOCTYPE html>
<html lang="fr">

    <!-- La partie head -->

    <!-- La partie head -->
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <!-- Lien bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <!-- Icones Bootstrap -->
    <!-- Lien bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <!-- Icones Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <!-- CSS -->
    <link rel="stylesheet" href="<?= $web_root ?>css/styles.css">
</head>


<!-- la partie body -->
<body class="d-flex flex-column justify-content-center align-items-center vh-100">

    <!-- Titre EPayFc -->
    <h1 class="mb-4 fw-bold text-white fs-2"><i class="bi bi-cart4"></i>EPayFC</h1>
    <!-- Partie bloc de la page -->
    <div class="logContainer text-center shadow-lg">
        <h2 class="mb-3 fs-3">Sign in</h2>
        <hr class="mb-4" style="border-color: #444; opacity: 1;">
        <!-- Partie formulaire -->
        <form action="<?= $web_root ?>login/login" method="post">
            <div class="input-group mb-3">
                <span class="input-group-text custom-input-icon"><i class="bi bi-person"></i></span>
                <input type="text" name="email" class="form-control custom-input" placeholder="Mail" aria-label="Email">
              
            </div>

            <div class="input-group mb-3">
                <span class="input-group-text custom-input-icon"><i class="bi bi-key"></i></span>
                <input type="password" name="password" class="form-control custom-input" placeholder="*******" aria-label="Password">
         
            </div>
            
            <div class="d-grid gap-2 mb-3">
                <button type="submit" class="btn btn-primary custom-btn-blue">Login</button>
                <button type="button" class="btn btn-secondary custom-btn-grey">Continue as guest</button>
   
            </div>
        </form>
        <div class="mb-3">
            <a href="<?= $web_root ?>signup" class="link-primary text-decoration-none small">New here ? Click here to subscribe !</a>
        </div>

        <div class="mt-4 pt-2 border-top border-secondary">
            <h3 class="colorYellow fs-5 mb-3">For Debug Purpose</h3>
            
            <div class="d-flex flex-column gap-1 mb-3 small-text">
                <!-- impossible de mettre tout l'email à cause du htaccess -->
                <div>Login as <a href="<?= $web_root ?>login/logForDebug/boverhaegen" class="email fw-bold">boverhaegen@epfc.eu</a></div>
                <div>Login as <a href="<?= $web_root ?>login/logForDebug/mamichel" class="email fw-bold">mamichel@epfc.eu</a></div>
                <div>Login as <a href="<?= $web_root ?>login/logForDebug/quhouben" class="email fw-bold">quhouben@epfc.eu</a></div>
                <div>Login as <a href="<?= $web_root ?>login/logForDebug/xapigeolet" class="email fw-bold">xapigeolet@epfc.eu</a></div>
            </div>

            <div class="d-flex flex-column gap-1 small-text">
                <a href="<?= $web_root ?>setup/install" class="colorGreen fw-bold">Restore original data</a>
                <a href="<?= $web_root ?>setup/export" class="colorYellow fw-bold">Backup personal data</a>
                <a href="<?= $web_root ?>setup/restore" class="colorYellow fw-bold">Restore personal data</a>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <?php if(count($errors) != 0):?>
        <div class='errors'>
               <?php foreach ($errors as $error): ?>
                    <?= $error?> 
                <?php endforeach; ?>  
            </ul>
        </div>
    <?php endif; ?>                        
</body>
</html>