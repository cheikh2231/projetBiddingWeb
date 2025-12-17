<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <!-- Lien bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <!-- Icones Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <!-- CSS -->
    <link rel="stylesheet" href="css/LoginStyle.css">
</head>
<body>

    <div class="login-container">
        
        <div class="logo-area">
            <i class="fas fa-shopping-cart"></i> EPayFC
        </div>

        <div class="card shadow">
            <div class="card-header">
                Sign in
            </div>
            <div class="card-body">
                
                <?php if($message): ?>
                    <div class="alert alert-info py-1 text-center" style="font-size:0.8rem">
                        <?= $message ?>
                    </div>
                <?php endif; ?>

                <form action="" method="POST">
                    <div class="input-group mb-3">
                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                        <input type="text" name="email" class="form-control" placeholder="Mail" aria-label="Mail">
                    </div>

                    <div class="input-group mb-3">
                        <span class="input-group-text"><i class="fas fa-key"></i></span>
                        <input type="password" name="password" class="form-control" placeholder="******" aria-label="Password">
                    </div>

                    <div class="d-grid gap-2 mb-2">
                        <button class="btn btn-primary" type="submit">Login</button>
                    </div>

                    <div class="d-grid gap-2 mb-3">
                        <button class="btn btn-secondary" type="button">Continue as guest</button>
                    </div>

                    <div class="text-center mb-2">
                        <a href="#" style="font-size: 0.9rem; text-decoration: none; color: #58a6ff;">New here ? Click here to subscribe !</a>
                    </div>
                </form>

                <div class="debug-section">
                    <div class="debug-title">For Debug Purpose</div>
                    
                    <div class="mb-2 text-white">
                        <div>Login as <strong>boverhaegen@epfc.eu</strong></div>
                        <div>Login as <strong>mamichel@epfc.eu</strong></div>
                        <div>Login as <strong>quhouben@epfc.eu</strong></div>
                        <div>Login as <strong>xapigeolet@epfc.eu</strong></div>
                    </div>

                    <div class="debug-links font-weight-bold">
                        <a href="#" class="text-green fw-bold">Restore original data</a>
                        <a href="#" class="text-orange fw-bold">Backup personal data</a>
                        <a href="#" class="text-orange fw-bold">Restore personal data</a>
                    </div>
                </div>

            </div>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>