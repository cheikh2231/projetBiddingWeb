<?php 
require_once 'model/Bids.php';
require_once 'model/User.php';


$currentUser = 1; 
$isOwner = $currentUser === (int)$item->owner;
$bids = Bids::getBids($item->id);

$isWinner = $item->buy_now_reached && !empty($bids) && (int)$bids[0]->owner === $currentUser;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($item->title) ?></title>
    <base href="<?= $web_root ?>"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"/>
    <link href="css/open.css" rel="stylesheet" type="text/css"/>
</head>

<body data-bs-theme="dark" class="bg-primary-subtle">

<header class="bg-dark p-0 d-flex justify-content-center align-items-center border-bottom border-secondary mb-4 sticky-top">
    <?php 
        $backRoute = (strpos($source, 'items') !== false) ? "item/list" : "item/browse";
    ?>
    <a href="<?= $backRoute ?>">
        <i class="bi bi-arrow-left-short fs-3 text-primary position-absolute start-0 top-0"></i>
    </a>
    <h5 class="m-0 me-2 text-primary">Item open</h5>
    <i class="bi bi-cart4 fs-4 text-white"></i>
</header>

<div class="container">
    <div class="row">

        <div class="col-12 col-md-6 col-lg-7">
            <div class="card mb-3">
                <div class="ratio ratio-16x9">
                    <?php $pic = $item->getPictures(); ?>
                    <?php if (!empty($pic)) : ?>
                        <img src="<?= $web_root . $pic[$idImg > 0 ? $idImg - 1 : 0] ?>" class="card-img-top" alt="<?= htmlspecialchars($item->title) ?>"/>
                    <?php endif; ?>
                </div>
                <div class="info-item p-2">
                    <div class="title mt-1 fw-bold"><?= htmlspecialchars($item->title) ?></div>
                    <p class="text-muted"><small><?= htmlspecialchars($item->description) ?></small></p>
                    <p class="text-muted">
                        <small>Start: <?= $item->created_at ?></small><br>
                        <small>Ends: <?= $item->end_at ?></small>
                    </p>
                </div>
            </div>

            <?php if (!empty($pic)) : ?>
                <div class="d-flex flex-wrap mb-3">
                    <?php for ($i = 0; $i < count($pic); $i++) : 
                        $isSelected = ($i === ($idImg > 0 ? $idImg - 1 : 0));
                    ?>
                        <a href="item/open/<?= $item->id ?>/<?= $source ?>/<?= $i + 1 ?>">
                            <div class="cas me-2 <?= $isSelected ? 'border border-primary border-2' : '' ?>" style="width:80px;">
                                <img src="<?= $pic[$i] ?>" class="img-thumbnail">
                            </div>
                        </a>
                    <?php endfor; ?>
                </div>
            <?php endif; ?>

            <?php $bids = Bids::getBids($item->id); ?>
            <div class="bid-panel-dark mt-3 p-3 bg-dark rounded border border-secondary">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="fw-bold">Bid History</span>
                    <span class="badge bg-secondary"><?= !empty($bids) ? count($bids) : 0 ?> entries</span>
                </div>
                <?php if(!empty($bids)) : ?>
                    <?php foreach ($bids as $bid) : $u = User::getUserById($bid->owner); ?>
                        <div class="d-flex justify-content-between align-items-center border-bottom border-secondary py-2">
                            <div>
                                <div class="fw-bold text-light small"><?= $u ? htmlspecialchars($u->pseudo) : "Unknown" ?></div>
                                <div class="text-muted" style="font-size: 0.75rem;"><?= htmlspecialchars($bid->created_at) ?></div>
                            </div>
                            <div class="text-success fw-bold">€ <?= number_format($bid->amount, 2) ?></div>
                        </div>
                    <?php endforeach; ?>
                <?php else : ?>
                    <div class="text-muted small py-2">No bids yet.</div>
                <?php endif; ?>
            </div>
        </div>

        <div class="col-12 col-md-6 col-lg-5">

            <div class="card mb-3 w-100 bg-dark">
                <div class="card-body">
                    <small class="text-muted text-uppercase fw-bold" style="font-size: 0.7rem;">Pricing</small>
                    
                    <div class="mt-2">
                        <?php if ($item->is_auction): ?>
                            <div class="d-flex justify-content-between mb-1">
                                <span>Current Bid</span>
                                <span class="text-success fw-bold">€ <?= number_format($item->max_bid ?? $item->starting_bid, 2) ?></span>
                            </div>
                        <?php endif; ?>

                        <?php if ($item->has_buy_now): ?>
                            <div class="d-flex justify-content-between">
                                <span><?= $item->is_auction ? 'Buy Now' : 'Price' ?></span>
                                <span class="fw-bold text-success">€ <?= number_format($item->buy_now_price, 2) ?></span>
                            </div>
                        <?php endif; ?>
                    </div>

                    <hr class="my-3 border-secondary opacity-50">

                    <?php if ($isWinner): ?>
                        <div class="alert alert-success bg-transparent border-0 text-center p-0 mb-0">
                            <p class="mb-0 fw-bold text-success">Congratulations! You purchased this item for € <?= number_format($item->buy_now_price, 2) ?>.</p>
                        </div>
                    <?php elseif ($isOwner): ?>
                        <div class="d-grid gap-2">
                            <button class="btn btn-lg btn-success fs-5 opacity-50 fw-bold" disabled>
                                <i class="bi bi-cart4 me-2"></i>Buy now
                            </button>
                            <small class="text-center text-muted" style="font-size: 0.75rem;">You cannot bid on your own listing.</small>
                        </div>
                    <?php else: ?>
                        <form method="post" action="item/open/<?= $item->id ?>/<?= $source ?>">
                            <?php if ($item->is_auction): ?>
                                <?php $minBid = ($item->has_bids ? $item->max_bid : $item->starting_bid) + 1; ?>
                                <div class="input-group mb-3">
                                    <span class="input-group-text bg-secondary border-secondary text-white">€</span>
                                    <input type="number" name="amount" class="form-control bg-dark text-white border-secondary" min="<?= $minBid ?>" value="<?= $minBid ?>" required>
                                </div>
                                <div class="d-grid gap-2 mb-2">
                                    <button class="btn btn-lg btn-success fs-5 fw-bold" type="submit">Place Bid</button>
                                </div>
                            <?php endif; ?>

                            <?php if ($item->has_buy_now): ?>
                                <div class="d-grid">
                                    <button type="submit" name="buy_now" value="1" class="btn <?= $item->is_auction ? 'btn-outline-primary border-2' : 'btn-lg btn-success fw-bold' ?>">
                                        <i class="bi bi-cart4 me-2"></i>Buy Now <?= $item->is_auction ? "at € " . number_format($item->buy_now_price, 2) : "" ?>
                                    </button>
                                </div>
                            <?php endif; ?>
                        </form>
                    <?php endif; ?>
                </div>
            </div>

            <div class="card w-100 bg-dark">
                <div class="card-body">
                    <small class="text-muted text-uppercase fw-bold" style="font-size: 0.7rem;">Seller Information</small>
                    <div class="d-flex align-items-center mt-2">
                        <?php $userPic = Item::getUserPic($item->owner); ?>
                        <?php if ($userPic) : ?>
                            <img src="<?= $userPic ?>" class="rounded-circle me-3" style="width:3rem;height:3rem; object-fit: cover;">
                        <?php else : ?>
                            <i class="bi bi-person-circle fs-1 me-3 text-secondary"></i>
                        <?php endif; ?>
                        <div>
                            <div class="fw-bold text-light"><?= htmlspecialchars(Item::itemUserName($item->owner)) ?></div>
                            <div class="text-muted small">
                                <?= Item::getUserRole($item->owner) === "user" ? "Member" : "Admin" ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div> 
    </div> 
</div> 

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>