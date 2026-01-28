<?php   
    $somme = 0;
    foreach ($sold as $i) {
       $somme = $somme + $i->getFinalBuyPrice(); 
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>sales</title>
    <base href="<?= $web_root ?>"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"/>
    <link href="css/browse-item.css" rel="stylesheet" type="text/css"/>
</head>

<body>

    <header class="bg-dark p-0 d-flex justify-content-center align-items-center border-bottom border-secondar sticky-top">
        <a href="user/profile"><i class="bi bi-arrow-left-short fs-3 text-primary position-absolute start-0 top-0"></i></a>
        <h5 class="m-0 me-2 text-primary">Sales</h5>
        <i class="bi bi-cart4 fs-4 text-white"></i>
    </header>

    <div class="container  pb-5">
        <h1 class="text-white">Completed Sales<h1>
        <h5 class = "text-info-emphasis ">A snapshot of the deals you've wrapped up.</h5><br>   
        <div class="card mb-4" style="width: 30rem; ">
            <div class="card bg-dark text-white p-2 ">
                <h5 class="text-white-50">TOTAL REVENUE</h5>
                <h1 class=" mb-2 text-white">€ <?= number_format($somme,2) ?></h1>
                <h5 class = "text-white-50 ">Accross <?= count($sold)?> sales</h5>
            </div>
        </div>
        <?php if (!empty($sold)) : ?>
        
            <div class="row g-4">
                <?php foreach ($sold as $item) : ?>

                    <?php
                        $bids = Bids::getBids($item->getId());
                        $pictures = $item->getPictures();
                        $username = Item::itemUserName($item->getOwner());
                    ?>
                    <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                        <a href="item/open/<?= $item->getId() ?>/from-<?= $source ?>"
                        class="item-card d-block position-relative mb-4">

                            <div class="badge-container justify-content-end">
                                <div class="d-flex flex-column align-items-end gap-1">
                                    <?php if ($item->getIsAuction()) : ?>
                                        <span class="badge badge-auction">Auction</span>
                                    <?php endif; ?>
                                    <?php if ($item->getHasBuyNow()) : ?>
                                        <span class="badge badge-buy">Buy Now</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="position-relative">
                                <?php if (!empty($pictures)) : ?>
                                    <img src="<?= $pictures[0] ?>" class="img-fluid" alt="<?= htmlspecialchars($item->getTitle()) ?>">
                                    <?php if (count($pictures)>1) :?>
                                    <div class="position-absolute bottom-0 end-0 m-2 px-2 py-1 bg-dark bg-opacity-75 text-white rounded-2 d-flex align-items-center" style="font-size: 0.75rem;">
                                        <i class="bi bi-images me-1"></i>
                                            <?= count($pictures) ?> images
                                    </div>
                                    <?php endif;?>
                                <?php else: ?>    
                                <img src="https://clipart-library.com/2023/disappearance-clipart-33563200-MISSING-red-Rubber-Stamp-over-a-white-background--Stock-Photo.jpg"  alt=""/>
                                <?php endif; ?>
                            </div>
                        

                            <div class="item-body">
                                <div class="item-title"><?= htmlspecialchars($item->getTitle()) ?></div>
                                <div class="item-author">by <?= $username ?></div>

                                <div class="d-flex justify-content-between align-items-end mt-2">
                                    <div class="price">
                                        € <?= number_format(
                                            $item->getHasBuyNow() ? $item->getBuyNowPrice() : ($item->getStartingBid() ?? 0),
                                            2
                                        ) ?>
                                    </div>

                                    <?php if ($item->getIsAuction()) : ?>
                                        <div class="text-end">
                                            <div class="text-secondary" style="font-size: 0.875rem;">Current bid</div>
                                            <div class="current-bid">
                                                € <?= number_format(
                                                    $item->getHasBids() ? $item->getMaxBid() : ($item->getStartingBid() ?? 0),
                                                    2
                                                ) ?>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                    
                                </div>
                                <div class="time-expired text-danger mt-2">
                                        <i class="bi bi-patch-check"></i> closed
                                </div>
                              
                            </div>
                           
                        </a>
                            <div class="mb-1">
                                <i class="bi bi-currency-dollar"></i> Final price: <strong>€ <?= number_format($item->getFinalBuyPrice(),2) ?></strong>
                            </div>
                            <div class="mb-1">
                                <i class="bi bi-trophy-fill"></i> <?= $item->getWinner()->getPseudo() ?>
                            </div>
                            <div class="mb-1 ">
                                <i class="bi bi-patch-check"></i> Closed on <?= $bids[0]->getCreatedAt() ?>
                            </div>
                    </div>

                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
<?php include("view/utilités/footer.php")?>    
</body>
</html>