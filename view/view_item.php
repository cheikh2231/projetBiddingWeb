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

<header class="bg-dark  border-bottom border-secondary sticky-top ">
    <?php $place = "browse"; ?>
    <div class="container d-flex justify-content-center align-items-center">
        <h5 class="m-0 me-1 text-primary">Browse</h5>
        <i class="bi bi-cart4 fs-4 text-white"></i>
    </div>
</header>

<div class="container py-5">


    <?php if (!empty($itemsUser)) : ?>
        <h4 class = "text-white">Items I'm Participating In</h4>

        <div class="row g-4 mb-5">
            <?php foreach ($itemsUser as $item) : ?>
                <?php
                    $pictures = $item->getPictures();
                    $username = Item::itemUserName($item->owner);
                ?>

                <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                    <a href="Item/open/<?= $item->id ?>/from-<?= $place ?>"
                       class="item-card d-block position-relative h-100">

                        <div class="badge-container">
                            <div>
                                <?php if (!empty(Item::max_bidder($item->id))) : ?>
                                    <?php if (Item::max_bidder($item->id) === 1) : ?>
                                        <span class="badge badge-green">Highest Bidder</span>
                                    <?php else : ?>
                                        <span class="badge badge-dark">Bidder</span>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>

                            <div class="d-flex flex-column align-items-end gap-1">
                                <?php if ($item->is_auction) : ?>
                                    <span class="badge badge-auction">Auction</span>
                                <?php endif; ?>
                                <?php if ($item->has_buy_now) : ?>
                                    <span class="badge badge-buy">Buy Now</span>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- IMAGE -->
                        <?php if (!empty($pictures)) : ?>
                            <img src="<?= $pictures[0] ?>" alt="<?= htmlspecialchars($item->title) ?>">
                        <?php endif; ?>

                        <!-- BODY -->
                        <div class="item-body">
                            <div class="item-title"><?= htmlspecialchars($item->title) ?></div>
                            <div class="item-author">by <?= $username ?></div>

                            <div class="d-flex justify-content-between align-items-end mt-2">
                                <div class="price">
                                    € <?= number_format(
                                        $item->has_buy_now ? $item->buy_now_price : $item->starting_bid ?? 0,
                                        2
                                    ) ?>
                                </div>

                                <?php if ($item->is_auction) : ?>
                                    <div class="text-end">
                                        <div class="text-secondary" style="font-size: 0.875rem;">Current bid</div>
                                        <div class="current-bid">
                                            € <?= number_format(
                                                $item->has_bids ? $item->max_bid : $item->starting_bid ?? 0,
                                                2
                                            ) ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <?php
                                $current = new DateTime(AppTime::get_current_datetime());
                                $diff = $current->diff(new DateTime($item->end_at));
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

    <!-- ==== -->
    <?php if (!empty($items)) : ?>
        <h4 class = "text-white">Other Available Items</h4>

        <div class="row g-4">
            <?php foreach ($items as $item) : ?>
                <?php
                    $pictures = $item->getPictures();
                    $username = Item::itemUserName($item->owner);
                ?>

                <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                    <a href="Item/open/<?= $item->id ?>/from-<?= $place ?>"
                       class="item-card d-block position-relative h-100">

                        <div class="badge-container justify-content-end">
                            <div class="d-flex flex-column align-items-end gap-1">
                                <?php if ($item->is_auction) : ?>
                                    <span class="badge badge-auction">Auction</span>
                                <?php endif; ?>
                                <?php if ($item->has_buy_now) : ?>
                                    <span class="badge badge-buy">Buy Now</span>
                                <?php endif; ?>
                            </div>
                        </div>

                        <?php if (!empty($pictures)) : ?>
                            <img src="<?= $pictures[0] ?>" alt="<?= htmlspecialchars($item->title) ?>">
                        <?php endif; ?>

                        <div class="item-body">
                            <div class="item-title"><?= htmlspecialchars($item->title) ?></div>
                            <div class="item-author">by <?= $username ?></div>

                            <div class="price mt-2">
                                € <?= number_format(
                                    $item->has_buy_now ? $item->buy_now_price : $item->starting_bid ?? 0,
                                    2
                                ) ?>
                            </div>

                            <?php
                                $current = new DateTime(AppTime::get_current_datetime());
                                $diff = $current->diff(new DateTime($item->end_at));
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

</body>
</html>
