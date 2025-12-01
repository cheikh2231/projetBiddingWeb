<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Browse items</title>
    <base href="<?= $web_root ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="css/styles.css" rel="stylesheet" type="text/css" />
</head>

<body>
    <div>
        <h3>Browse</h3>
        <h2>Item quentin</h2>

        <div class="cards-container">
            <?php foreach ($itemsUser as $item) : ?>
            
                <?php $pictures = $item->getPictures(); ?>
                <div class="card">
                    <?php if (!empty($pictures)) : ?>
                        <div class="image-wrapper">
                            <img src="<?= $web_root . $pictures[0] ?>" alt="<?= htmlspecialchars($item->title) ?>" />
                            <?php if(count($pictures)>1):?>
                            <div class="image-count"><?= count($pictures)." images"  ?></div>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                    <div class="card-content">
                        <h3 class="title"><?= htmlspecialchars($item->title) ?></h3>
                        <p class="seller">by Boris</p>

                        <div class="prices">
                            <div class="buy-now-price">€ <?= number_format($item->buy_now_price ?? 0, 2) ?></div>
                            <div class="current-bid">Current bid € <?= number_format($item->starting_bid ?? 0, 2) ?></div>
                        </div>

                        <div class="duration"><?=$item->duration_days?></div>

                        <div class="buttons">
                            <button class="btn bidder">Bidder</button>
                            <button class="btn auction">Auction</button>
                            <button class="btn buy-now">Buy Now</button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
            <?= "==========================================" ?>
             <?php foreach ($items as $item) : ?>
            
                <?php $pictures = $item->getPictures(); ?>
                <div class="card">
                    <?php if (!empty($pictures)) : ?>
                        <div class="image-wrapper">
                            <img src="<?= $web_root . $pictures[0] ?>" alt="<?= htmlspecialchars($item->title) ?>" />
                            <?php if(count($pictures)>1):?>
                            <div class="image-count"><?= count($pictures)." images"  ?></div>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                    <div class="card-content">
                        <h3 class="title"><?= htmlspecialchars($item->title) ?></h3>
                        <p class="seller">by Boris</p>

                        <div class="prices">
                            <div class="buy-now-price">€ <?= number_format($item->buy_now_price ?? 0, 2) ?></div>
                            <div class="current-bid">Current bid € <?= number_format($item->starting_bid ?? 0, 2) ?></div>
                        </div>

                        <div class="duration"><?=$item->duration_days?></div>

                        <div class="buttons">
                            <button class="btn bidder">Bidder</button>
                            <button class="btn auction">Auction</button>
                            <button class="btn buy-now">Buy Now</button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>

</html>
