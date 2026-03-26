<?php   
     $place = "sales"; 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>sales</title>
    <base href="<?= $web_root ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link href="css/browse-item.css" rel="stylesheet" type="text/css">
</head>

<body>

    <header class="bg-dark p-0 d-flex justify-content-center align-items-center border-bottom border-secondar sticky-top">
        <a href="user/profile"><i class="bi bi-arrow-left-short fs-3 text-primary position-absolute start-0 top-0"></i></a>
        <h5 class="m-0 me-2 text-primary">Sales</h5>
        <i class="bi bi-cart4 fs-4 text-white"></i>
    </header>

    <div class="container  pb-5">
        <h1 class="text-white mt-4">Completed Sales</h1>
        <p class = "h5 text-info-emphasis ">A snapshot of the deals you've wrapped up.</p><br> 
        <?php if(count($sold)>0):?>
        <div class="badge-container justify-content-end p-2 mt-5">
            <span class="badge badge-green"><?= count($sold)?> sales</span>
        </div>
        
        <?php endif;?> 
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="card bg-dark text-white p-2">
                    <p class="h5 text-white-50">TOTAL REVENUE</p>
                    <h1 class="mb-2 text-white">€ <?= number_format($somme, 2) ?></h1>
                    <p class=" h6 text-white-50">Across <?= count($sold) ?> sales</p>
                </div>
            </div>

            <div class="col-md-4 mb-4">
                <div class="card bg-dark text-white p-2">
                    <p class=" h5 text-white-50">AVERAGE TICKET</p>
                    <p class="h1 mb-2 text-white"> <?= count($sold)>0?number_format($somme/ count($sold),2):0 ?></p>
                    <p class="h6 text-white-50">Median buyer appetite indicator</p>
                </div>
            </div>
            <?php if($currentUser->getMyMostLoyalBidder() !== null):?>
                <div class="col-md-4 mb-4">
                    <div class="card bg-dark text-white p-2">
                        <p class="h5 text-white-50">LOYAL BIDDER</p>
                        <p class="h1 mb-2 text-white"><?= $currentUser->getMyMostLoyalBidder()->getPseudo() ?></p>
                        <p class="h5 text-white-50">Who you've bought from the most</p>
                    </div>
                </div>
            <?php endif;?>
        </div>
        <?php if (!empty($sold)) : ?>
        
            <div class="row g-4">
                <?php foreach ($sold as $item) : ?>

                    <?php $bids =$item->getBids(); ?>

                   <?php include('view/utilités/_item_card.php')?>
                    
                <?php endforeach; ?>
 
            </div>

        <?php endif; ?>
    </div>

<?php include("view/utilités/footer.php")?> 

</body>
</html>