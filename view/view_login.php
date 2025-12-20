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
<body class="d-flex justify-content-center align-items-center">
    <h1 class="mb-4"><i class="bi bi-cart4"></i>EPayFC</h1>
    <div class="logContainer text-center">
        <h2 class="mb-4">Sign in</h2>
        <hr>
        <form action="dashboard.php" method="post">
            <table class="d-flex justify-content-center align-items-center">
                <tr>
                <td><i class="bi bi-person"></i></td>
                <td><input id="pseudo" name="pseudo" type="text" placeholder="Mail"></td>
                </tr>
                <tr>
                <td><i class="bi bi-key"></i></td>
                <td><input id="pseudo" name="pseudo" type="password"></td>
                </tr>
            </table>
            
            <div class="mb-3"><input type="submit" value="Login"></div>
            <div class="mb-3"><input type="submit" value="Continue as guest"></div>
        </form>
        <a href ="">New here ? Click here to subscribe !</a>
        <hr>
        <h3 class="colorYellow">For Debug Purpose</h3>
        <div class="mb-3"><label>Login as&nbsp;</label><b><a href="" class="email">boverhaegen@epfc.eu</a></b></div>
        <div class="mb-3"><label>Login as&nbsp;</label><b><a href="" class="email">mamichel@epfc.eu</a></b></div>
        <div class="mb-3"><label>Login as&nbsp;</label><b><a href="" class="email">quhouben@epfc.eu</a></b></div>
        <div class="mb-3"><label>Login as&nbsp;</label><b><a href="" class="email">xapigeolet@epfc.eu</a></b></div>
        <div class="mb-3"><a href="" class="colorYellow">Restore original data</a></div>
        <div class="mb-3"><a href="" class="colorYellow">Backup personal data</a></div>
        <div class="mb-3"><a href="" class="colorGreen">Restore personal data</a></div>
    

    </div>
     <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>