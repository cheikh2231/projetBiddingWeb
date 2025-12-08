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
<body class="
-flex justify-content-center align-items-center">

    <h1 class="mb-4"><i class="bi bi-cart4"></i>EPayFC</h1>
    <div class="logContainer row-cols-1 text-center">
        <h2 class="mb-4">Sign in</h2>
        <hr>
        <form action="dashboard.php" method="post">
            <table>
                <td><i class="bi bi-person"></i></td>
                <td><input id="pseudo" name="pseudo" type="text" value="mail"></td>
                <td><i class="bi bi-key"></i></td>
                <td><input id="pseudo" name="pseudo" type="text" value="mail"></td>


            </table>
            
            <input type="submit" value="Login">
            <input type="submit" value="Continue as guest">
        </form>
        <a href ="">New here ? Click here to subscribe !</a>
        <hr>
        <h3>For Debug Purpose</h3>
        <h4>Login as </h4><a href="">boverhaegen@epfc.eu</a>
        <h4>Login as </h4><a href="">mamichel@epfc.eu</a>
        <h4>Login as </h4><a href="">quhouben@epfc.eu</a>
        <h4>Login as </h4><a href="">xapigeolet@epfc.eu</a>
        <a href="">Restore original data</a>
        <a href="">Backup personal data</a>
        <a href="">Restore personal data</a>
    

    </div>
     <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>