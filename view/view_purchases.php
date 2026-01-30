<?php   
    $somme = 0;
    foreach ($purchases as $i) {
       $somme = $somme + $i->getFinalBuyPrice(); 
    }
     $place = "purchases"; 

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>purchases</title>
    <base href="<?= $web_root ?>"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"/>
    <link href="css/browse-item.css" rel="stylesheet" type="text/css"/>
</head>

<body>

    <header class="bg-dark p-0 d-flex justify-content-center align-items-center border-bottom border-secondar sticky-top">
        <a href="user/profile"><i class="bi bi-arrow-left-short fs-3 text-primary position-absolute start-0 top-0"></i></a>
        <h5 class="m-0 me-2 text-primary">Purchases</h5>
        <i class="bi bi-cart4 fs-4 text-white"></i>
    </header>

    <div class="container  pb-5">
        <h1 class="text-white mt-4">My purchases<h1>
        <h5 class = "text-info-emphasis ">Track the gear you've succefuly secured</h5><br> 
        <?php if(count($purchases)>0):?>
        <div class="badge-container justify-content-end p-2 mt-5">
            <span class="badge badge-blue"><?= count($purchases)?> purchases</span>
        </div>
        
        <?php endif;?> 
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="card bg-dark text-white p-2">
                    <h5 class="text-white-50">TOTAL SPENT</h5>
                    <h1 class="mb-2 text-white">€ <?= number_format($somme, 2) ?></h1>
                    <h6 class="text-white-50">Across <?= count($purchases) ?> purchases</h6>
                </div>
            </div>

            <div class="col-md-4 mb-4">
                <div class="card bg-dark text-white p-2">
                    <h5 class="text-white-50">AVERAGE TICKET</h5>
                    <h1 class="mb-2 text-white"> <?= number_format($somme/ count($purchases),2) ?></h1>
                    <h6 class="text-white-50">helps you plan future bids</h6>
                </div>
            </div>

            <div class="col-md-4 mb-4">
                <div class="card bg-dark text-white p-2">
                    <h5 class="text-white-50">TOP SELLER</h5>
                    <h1 class="mb-2 text-white"><?= $currentUser->getMyItemsTopSeller()->getPseudo() ?></h1>
                    <h5 class="text-white-50">Most recuring winning bidder</h5>
                </div>
            </div>
        </div>
        <?php if (!empty($purchases)) : ?>
        
            <div class="row g-4">
                <?php foreach ($purchases as $item) : ?>

                    <?php $bids =$item->getBids(); ?>

                   <?php include('view/utilités/_item_card.php')?>
                    
                <?php endforeach; ?>

            </div>

        <?php endif; ?>
    </div>

<?php include("view/utilités/footer.php")?> 

</body>
</html>