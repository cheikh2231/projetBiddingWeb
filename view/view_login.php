<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="<?= $web_root ?>css/LoginStyle.css">
</head>
<body class="d-flex flex-column justify-content-center align-items-center vh-100">
    <h1 class="mb-4 fw-bold text-white fs-2"><i class="bi bi-cart4"></i> EPayFC</h1>
    
    <div class="logContainer text-center shadow-lg p-4 bg-white rounded">
        <h2 class="mb-3 fs-3">Sign in</h2>
        <hr class="mb-4">
        
        <form action="login/login" method="post">
            <div class="input-group mb-3">
                <span class="input-group-text"><i class="bi bi-person"></i></span>
                <input type="text" name="email" class="form-control" placeholder="Mail" required>
            </div>

            <div class="input-group mb-3">
                <span class="input-group-text"><i class="bi bi-key"></i></span>
                <input type="password" name="password" class="form-control" placeholder="*******" required>
            </div>
            
            <div class="d-grid gap-2 mb-3">
                <button type="submit" class="btn btn-primary">Login</button>
            </div>
        </form>

        <?php if(isset($errors) && count($errors) > 0): ?>
            <div class="alert alert-danger mt-3 py-2">
                <ul class="list-unstyled mb-0 small">
                    <?php foreach ($errors as $error): ?>
                        <li><i class="bi bi-exclamation-triangle"></i> <?= htmlspecialchars($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>