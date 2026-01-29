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

<body>
 <a href="login/logout" class="btn btn-danger w-100 d-flex align-items-center justify-content-center">
        <i class="bi bi-box-arrow-right me-2"></i> Sign Out
    </a>
<header class="bg-dark border-bottom border-secondary sticky-top ">
    <?php $place = "browse"; ?>
    <div class="container d-flex justify-content-center align-items-center">
        <h5 class="m-0 me-1 text-primary">Browse</h5>
        <i class="bi bi-cart4 fs-4 text-white"></i>
    </div>
    <div class="p-3">
   
</div>

</header>

<div class="container py-5">

    <?php if (!empty($itemsUser)) : ?>
        <h4 class="text-white">Items I'm Participating In</h4>

        <div class="row g-4 mb-5">
            <?php foreach ($itemsUser as $item) : ?>
                <?php
                    $pictures = $item->getPictures();
                    $username = Item::itemUserName($item->getOwner());
                ?>

                <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                    <a href="Item/open/<?= $item->getId() ?>/from-<?= $place ?>"
                       class="item-card d-block position-relative h-100">

                        <div class="badge-container">
                            <div>
                                <?php if ($item->max_bidder() != null) : ?>
                                    <?php if ($item->max_bidder() === $currentUser->getId()) : ?>
                                        <span class="badge badge-green">Highest Bidder</span>
                                    <?php else : ?>
                                        <span class="badge badge-dark">Bidder</span>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>

                            <div class="d-flex flex-column align-items-end gap-1">
                                <?php if ($item->getIsAuction()) : ?>
                                    <span class="badge badge-auction">Auction</span>
                                <?php endif; ?>
                                <?php if ($item->getHasBuyNow()) : ?>
                                    <span class="badge badge-buy">Buy Now</span>
                                <?php endif; ?>
                            </div>
                        </div>

                        <?php if (!empty($pictures)) : ?>
                            <img src="<?= $pictures[0] ?>" alt="<?= htmlspecialchars($item->getTitle()) ?>">
                        <?php endif; ?>

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

                            <?php
                                $current = new DateTime(AppTime::get_current_datetime());
                                $diff = $current->diff(new DateTime($item->getEndAt()));
                                if ($diff->days > 0) :?> 
                                    <div class="time-left">
                                      <i class="bi bi-clock-history"></i>
                                      <?= "{$diff->days}d {$diff->h}h left"; ?>
                                    </div>
                                <?php else :?>
                                    <div class="time-inf1day">
                                      <i class="bi bi-clock-history"></i>
                                      <?= "{$diff->h}h {$diff->i}min left"; ?>
                                    </div>
                                <?php endif;?> 
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($items)) : ?>
        <h4 class="text-white">Other Available Items</h4>

        <div class="row g-4">
            <?php foreach ($items as $item) : ?>
                <?php
                    $pictures = $item->getPictures();
                    $username = Item::itemUserName($item->getOwner());
                ?>

                <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                    <a href="Item/open/<?= $item->getId() ?>/from-<?= $place ?>"
                       class="item-card d-block position-relative h-100">

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

                        <?php if (!empty($pictures)) : ?>
                            <img src="<?= $pictures[0] ?>" alt="<?= htmlspecialchars($item->getTitle()) ?>">
                        <?php endif; ?>

                        <div class="item-body">
                            <div class="item-title"><?= htmlspecialchars($item->getTitle()) ?></div>
                            <div class="item-author">by <?= $username ?></div>

                            <div class="price mt-2">
                                € <?= number_format(
                                    $item->getHasBuyNow() ? $item->getBuyNowPrice() : ($item->getStartingBid() ?? 0),
                                    2
                                ) ?>
                            </div>

                            <?php
                                $current = new DateTime(AppTime::get_current_datetime());
                                $diff = $current->diff(new DateTime($item->getEndAt()));
                                if ($diff->days > 0) :?> 
                                    <div class="time-left">
                                      <i class="bi bi-clock-history"></i>
                                      <?= "{$diff->days}d {$diff->h}h left"; ?>
                                    </div>
                                <?php else :?>
                                    <div class="time-inf1day">
                                      <i class="bi bi-clock-history"></i>
                                      <?= "{$diff->h}h {$diff->i}min left"; ?>
                                    </div>
                                <?php endif;?> 
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    
</div>

<footer class="footer fixed-bottom bg-dark border-top border-secondary p-0">
    <div class="container">
        <div class="row text-center ">
            <div class="col">
                <a href="item/browse" class="text-decoration-none <?= ($source === 'browse') ? 'text-primary' : 'text-white' ?>">
                    <i class="bi bi-search fs-4 d-block"></i>
                    <span style="font-size: 0.65rem;" class="text-uppercase fw-bold">Browse</span>
                </a>
            </div>
            <div class="col">
                <a href="item/list" class="text-decoration-none <?= ($source === 'items') ? 'text-primary' : 'text-white' ?>">
                    <i class="bi bi-house-door fs-4 d-block"></i>
                    <span style="font-size: 0.65rem;" class="text-uppercase fw-bold">My Items</span>
                </a>
            </div>
            <div class="col">
                <a href="item/add" class="text-decoration-none text-white">
                    <i class="bi bi-plus-lg fs-4 d-block"></i>
                    <span style="font-size: 0.65rem;" class="text-uppercase fw-bold">Add Offer</span>
                </a>
            </div>
            <div class="col">
                <a href="<?= $web_root ?>profile" class="text-decoration-none text-white">
                    <i class="bi bi-gear fs-4 d-block"></i>
                    <span style="font-size: 0.65rem;" class="text-uppercase fw-bold">Profile</span>
                </a>
            </div>
        </div>
    </div>
</footer>

</body>
</html>