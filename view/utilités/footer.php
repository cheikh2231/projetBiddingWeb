

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Browse items</title>
    <base href="<?= $web_root ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="css/browse-item.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
</head>
<style>
.footer{
    background: linear-gradient(180deg, #0b1324, #0e1a33);
     color: #ffffff;
}
</style>
    <body>
        <footer class="footer fixed-bottom  border-top border-secondary p-2 mb-4">
            <div class="container">
                <div class="row text-center ">
                    <div class="col">
                        <a href="item/browse" class="text-decoration-none <?= ($place === 'browse') ? 'text-primary' : 'text-white' ?>">
                            <i class="bi bi-search fs-4 d-block"></i>
                            <span style="font-size: 0.65rem;" class="text-uppercase fw-bold">Browse</span>
                        </a>
                    </div>
                    <?php if($currentUser!=null):?>
                    <div class="col">
                        <a href="item/list" class="text-decoration-none <?= ($place === 'items') ? 'text-primary' : 'text-white' ?>">
                            <i class="bi bi-house-door fs-4 d-block"></i>
                            <span style="font-size: 0.65rem;" class="text-uppercase fw-bold">My Items</span>
                        </a>
                    </div>
                    <div class="col">
                        <a href="item/add" class="text-decoration-none <?= ($place === 'add') ? 'text-primary' : 'text-white' ?>">
                            <i class="bi bi-plus-lg fs-4 d-block"></i>
                            <span style="font-size: 0.65rem;" class="text-uppercase fw-bold">Add Offer</span>
                        </a>
                    </div>
                    <div class="col">
                        <a href="user/profile" class="text-decoration-none  <?= ($place === 'profile') ? 'text-primary' : 'text-white' ?>">
                            <i class="bi bi-gear fs-4 d-block"></i>
                            <span style="font-size: 0.65rem;" class="text-uppercase fw-bold">Profile</span>
                        </a>
                    </div>

                    <?php else:?>
                        <div class="col">
                            <a href="user/signup" class="text-decoration-none text-white">
                                <i class="bi bi-balloon-fill fs-4 d-block"></i>
                                <span style="font-size: 0.65rem;" class="text-uppercase fw-bold">Join us</span>
                            </a>
                        </div>
                     <?php endif;?>
                    <?php include("view/view_time.php") ?>
                   
                </div>
            
            </div>
        </footer>
    </body>   
</html>