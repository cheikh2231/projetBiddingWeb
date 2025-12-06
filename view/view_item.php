<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Browse items</title>
    <base href="<?= $web_root ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="css/styles.css" rel="stylesheet" type="text/css" />
    
    <style>
        /* Force la hauteur des images à 200px et assure qu'elles remplissent l'espace */
        .card-img-custom {
            height: 200px; 
            object-fit: cover; /* Assure que l'image couvre la zone sans être déformée */
            width: 100%;
        }
        /* Assure que le titre est visible sur fond blanc */
        .card-custom .card-title {
            color: #212529 !important;
        }
    </style>
</head>

<body class="bg-white text-black">

    <header class="bg-dark p-3 d-flex justify-content-center align-items-center border-bottom border-secondary mb-4 sticky-top">
        <h3 class="m-0 me-2 text-primary">Browse</h3>
        <i class="bi bi-cart-fill fs-4 text-primary"></i>
    </header>

    <div class="container mt-5">
        <?php if (!empty($itemsUser)) :?>
        <h2 class="text-primary mb-4">Items I'm Participating In</h2>
        <div class="row g-4">

            <?php foreach ($itemsUser as $item) : ?>
                <?php $pictures = $item->getPictures(); ?>
                
                <div class="col-12 col-sm-6 col-md-4 col-lg-3 d-flex">
                    <div class="card card-custom position-relative w-100 h-100"> 
                        
<div class="position-absolute top-0 end-0 p-2 d-flex flex-row-reverse" style="z-index: 10;">
    
    <?php if ($item->has_buy_now) : ?>
        <span class="rounded-pill bg-primary text-white fs-6 py-1 px-4">Buy Now</span>
    <?php endif; ?>

    <?php if ($item->is_auction) : ?> 
        <?php 
            $is_buy_now_active = $item->has_buy_now;
            $margin_style = $is_buy_now_active ? 'style="margin-left: -15px; position: relative;"' : '';
        ?>
        <span class="rounded-pill bg-warning text-dark fs-6 py-1 px-4" <?= $margin_style ?>>Auction</span>
    <?php endif; ?>
    
   

        </div>

            <?php if (!empty($pictures)) : ?>
                <div class="position-relative">
                    <img src="<?= $web_root . $pictures[0] ?>" 
                        class="card-img-top card-img-custom" 
                        alt="<?= htmlspecialchars($item->title) ?>">

                    <?php if (count($pictures) > 1) : ?>
                        <span class="badge bg-secondary position-absolute bottom-0 end-0 m-2">
                            <?= count($pictures) ?> images
                        </span>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

<div class="card-body d-flex flex-column"> 
    <h5 class="card-title fw-bold fs-6"><?= htmlspecialchars($item->title) ?></h5>
    <p class="text-muted mb-2 mt-auto"><small>by <?= $item->owner ?></small></p>
    
    <div class="d-flex justify-content-between my-2">
        
        <div class="d-flex flex-column">
            <span class="text-warning fw-bold">€ <?= number_format($item->has_buy_now ? $item->buy_now_price : $item->starting_bid ?? 0, 2) ?></span>
        </div>

        <?php if ($item->is_auction):?>
        <div class="d-flex flex-column align-items-end">
            <span class="text-muted fw-normal" style="font-size: 0.85rem;">Current Bid</span>
            <span class="text-success fw-bold">€ <?= number_format($item->has_bids ? $item->max_bid : $item->starting_bid ?? 0, 2) ?></span>
        </div>
        <?php endif;?>

        </div>

             <p class="text-info m-0"><?= $item->time_left_formatted ?? ($item->duration_days . ' days left') ?></p>
        </div>
                    </div>
                </div>
            <?php endforeach; ?>
            <?php endif; ?>

            <?php foreach ($items as $item) : ?>
                <?php $pictures = $item->getPictures(); ?>

                <div class="col-12 col-sm-6 col-md-4 col-lg-3 d-flex">
                    <div class="card card-custom position-relative w-100 h-100">

            <div class="position-absolute top-0 end-0 p-2 d-flex flex-row-reverse" style="z-index: 10;">

            <?php if ($item->has_buy_now) : ?>
                <span class="rounded-pill bg-primary text-white fs-6 py-1 px-4">Buy Now</span>
            <?php endif; ?>

            <?php if ($item->is_auction) : ?> 
                <?php 
                    $is_buy_now_active = $item->has_buy_now; 
                    $margin_style = $is_buy_now_active ? 'style="margin-left: -15px; position: relative;"' : '';
                ?>
                <span class="rounded-pill bg-warning text-dark fs-6 py-1 px-4" <?= $margin_style ?>>Auction</span>
            <?php endif; ?>

            </div>

            <?php if (!empty($pictures)) : ?>
                <div class="position-relative">
                    <img src="<?= $web_root . $pictures[0] ?>" 
                        class="card-img-top card-img-custom" 
                        alt="<?= htmlspecialchars($item->title) ?>">

                    <?php if (count($pictures) > 1) : ?>
                        <span class="badge bg-secondary position-absolute bottom-0 end-0 m-2">
                            <?= count($pictures) ?> images
                        </span>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

    <div class="card-body d-flex flex-column"> 
        <h5 class="card-title fw-bold fs-6"><?= htmlspecialchars($item->title) ?></h5>
        <p class="text-muted mb-2 mt-auto"><small>by <?= $item->owner ?></small></p>

        <div class="d-flex justify-content-between my-2">
            
            <div class="d-flex flex-column">
                <span class="text-warning fw-bold">€ <?= number_format($item->has_buy_now ? $item->buy_now_price : $item->starting_bid ?? 0, 2) ?></span>
            </div>
            <?php if ($item->is_auction):?>
            <div class="d-flex flex-column align-items-end">
                <span class="text-muted fw-normal" style="font-size: 0.85rem;">Current Bid</span>
                <span class="text-success fw-bold">€ <?= number_format($item->has_bids ? $item->max_bid : $item->starting_bid ?? 0, 2)?></span>
            </div>
            <?php endif;?>

        </div>

    <p class="text-info m-0"><?= $item->time_left_formatted ?? ($item->duration_days . ' days left') ?></p>
    </div>
                    </div>
                </div>
            <?php endforeach; ?>

        </div> 
    </div> 
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>