<?php 
require_once 'model/Bids.php';
require_once 'model/User.php';


$bids = $item->getBids();
$pics = $item->getPictures();
$ownerId = (int)$item->getOwner();


$isOwner = $currentUser && ($currentUser->getId() === $ownerId);

$currentPrice = $item->getMaxBid() ?? ($item->getStartingBid() > 0 ? $item->getStartingBid() : $item->getBuyNowPrice());
$nameWithoutFrom = (strpos($source, 'sales') !== false) ? "sales" : "browse";
$backRoute = (strpos($source, 'items') !== false) ? "item/list" : "item/$nameWithoutFrom";
$displayImg = !empty($pics) ? $web_root . $pics[max(0, $idImg - 1)] : "https://clipart-library.com/2023/disappearance-clipart-33563200-MISSING-red-Rubber-Stamp-over-a-white-background--Stock-Photo.jpg";

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($item->getTitle()) ?></title>
    <base href="<?= $web_root ?>"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"/>
    <link href="css/open.css" rel="stylesheet" type="text/css"/>
</head>

<body data-bs-theme="dark" class="bg-primary-subtle">

<header class="bg-dark p-0 d-flex justify-content-center align-items-center border-bottom border-secondary mb-4 sticky-top">

    <a href="<?= $backRoute ?>"><i class="bi bi-arrow-left-short fs-3 text-primary position-absolute start-0 top-0"></i></a>
    <h5 class="m-0 me-2 text-primary">Item open</h5>
    <i class="bi bi-cart4 fs-4 text-white"></i>
</header>


<div class="container pb-5" style="margin-bottom: 80px;">
    <div class="row">
        <div class="col-12 col-md-6 col-lg-7">
            <div class="card mb-3">
                <div class="ratio ratio-16x9">
                    <img src="<?= $displayImg ?>" class="card-img-top" alt="Item image"/>
                </div>
                <div class="p-2">
                    <div class="mt-1 fw-bold"><?= htmlspecialchars($item->getTitle()) ?></div>
                    <p class="text-muted mb-1"><small><?= htmlspecialchars($item->getDescription()) ?></small></p>
                    <p class="text-muted small mb-0">Start: <?= $item->getCreatedAt() ?><br>Ends: <?= $item->getEndAt() ?></p>
                </div>
            </div>

            <?php if (!empty($pics)) : ?>
                <div class="d-flex flex-wrap mb-3">
                    <?php foreach ($pics as $index => $p) : ?>
                        <a href="item/open/<?= $item->getId() ?>/<?= $source ?>/<?= $index + 1 ?>">
                            <div class="cas me-2 <?= ($index === (max(0, $idImg - 1))) ? 'border border-primary border-2' : '' ?>" style="width:80px;">
                                <img src="<?= $web_root . $p ?>" class="img-thumbnail">
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <?php if(!empty($bids)) : ?>
                <div class="bid-panel-dark mt-3 p-3 bg-dark rounded border border-secondary">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="fw-bold"><?= $item->getIsDirectSale() ? "Purchase" : "Bid" ?> History</span>
                        <span class="badge bg-secondary"><?= count($bids) ?> entries</span>
                    </div>
                    <?php foreach ($bids as $bid) : $u = User::getUserById($bid->getOwner()); ?>
                        <div class="d-flex justify-content-between align-items-center border-bottom border-secondary py-2">
                            <div>
                                <div class="fw-bold text-light small"><?= $u ? htmlspecialchars($u->getPseudo()) : "Unknown" ?></div>
                                <div class="text-muted small" style="font-size: 0.75rem;"><?= $bid->getCreatedAt() ?></div>
                            </div>
                            <div class="text-success fw-bold">€ <?= number_format($bid->getAmount(), 2) ?></div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="col-12 col-md-6 col-lg-5">
            <div class="card mb-3 w-100 bg-dark">
                <div class="card-body">
                    <small class="text-muted text-uppercase fw-bold" style="font-size: 0.7rem;">Pricing</small>
                    <div class="mt-2">
                        <?php if ($item->getIsAuction()): ?>
                            <div class="d-flex justify-content-between mb-1">
                                <span>Current Bid</span>
                                <span class="text-success fw-bold">€ <?= number_format($item->getMaxBid() ?? $item->getStartingBid(), 2) ?></span>
                            </div>
                        <?php endif; ?>
                        <?php if ($item->getHasBuyNow()): ?>
                            <div class="d-flex justify-content-between">
                                <span><?= $item->getIsAuction() ? 'Buy Now' : 'Price' ?></span>
                                <span class="fw-bold text-white">€ <?= number_format($item->getBuyNowPrice(), 2) ?></span>
                            </div>
                        <?php endif; ?>
                    </div>

                    <hr class="my-3 border-secondary opacity-50">

                     <?php if($item->isClosed() && $isOwner) : ?>
                           <?php if($item->getWinner()!== null) :?> 
                                <strong class="text-center text-muted" ><?=$item->getWinner()->getPseudo() ?> won this item for € <?= number_format($item->getMaxBid(), 2) ?>. </strong>
                            <?php else :?>
                                <small class="text-center text-muted" style="font-size: 0.75rem;">this listing ended without a buyer.</small>    
                            <?php endif; ?>    
                    <?php elseif($item->isClosed()): ?>
                        <div class="alert alert-dark bg-transparent border-0 text-center p-0 mb-0">
                            <p class="mb-0 fw-bold">
                                <?php if (!$item->getHasBids()): ?>
                                    <span class="text-muted">No bids were placed. Item closed.</span>
                                <?php else: ?>
                                    <span class="text-success">
                                        <?= ($item->getWinner()==$currentUser) ? "Congratulations! You purchased this item" : "This item was won" ?> 
                                        for € <?= number_format($item->getMaxBid(), 2) ?>.
                                    </span>
                                <?php endif; ?>
                            </p>

                        </div>

                    <?php elseif ($isOwner): ?>
                        <div class="d-grid gap-2">

                            <?php if ($item->getIsAuction()): ?>
                                <div class="input-group"><span class="input-group-text bg-secondary text-white">€</span>
                                    <input type="text" class="form-control bg-dark text-white border-secondary" value="<?= number_format($currentPrice, 2) ?>" readonly>
                                </div>
                                <button class="btn btn-lg btn-success opacity-50 fw-bold" disabled>Place Bid</button>
                            <?php endif; ?>
                            <?php if ($item->getHasBuyNow()): ?>
                                <button class="btn btn-lg btn-success opacity-50 fw-bold" disabled>Buy now</button>
                            <?php endif; ?>
                            <small class="text-center text-muted" style="font-size: 0.75rem;">You cannot bid on your own listing.</small>
                        </div>
                    <?php else: ?>
                        <form method="post" action="item/open/<?= $item->getId() ?>/<?= $source ?>">
                            <?php if ($item->getIsAuction()): $minBid = ($item->getHasBids() ? $item->getMaxBid() : $item->getStartingBid()) + 1; ?>
                                <div class="input-group mb-3"><span class="input-group-text bg-secondary text-white">€</span>
                                    <input type="number" name="amount" class="form-control bg-dark text-white border-secondary" min="<?= $minBid ?>" value="<?= $minBid ?>" required>
                                </div>
                                <div class="d-grid gap-2 mb-2"><button class="btn btn-lg btn-success fw-bold" type="submit">Place Bid</button></div>
                            <?php endif; ?>
                            <?php if ($item->getHasBuyNow()): ?>
                                <div class="d-grid"><button type="submit" name="buy_now" value="1" class="btn <?= $item->getIsAuction() ? 'btn-outline-primary border-2' : 'btn-lg btn-success fw-bold' ?>">
                                    <i class="bi bi-cart4 me-2"></i>Buy Now <?= $item->getIsAuction() ? "at € ".number_format($item->getBuyNowPrice(), 2) : "" ?>
                                </button></div>

                            <?php endif; ?>
                        </form>
                    <?php endif; ?>
                </div>
            </div>


            <div class="card mb-3 bg-dark">
                <div class="card-body d-flex align-items-center">
                    <?php $uPic = Item::getUserPic($ownerId); ?>
                    <?= $uPic ? '<img src="'.$uPic.'" class="rounded-circle me-3" style="width:3rem;height:3rem;object-fit:cover;">' : '<i class="bi bi-person-circle fs-1 me-3 text-secondary"></i>' ?>
                    <div>
                        <div class="fw-bold text-light"><?= htmlspecialchars(Item::itemUserName($ownerId)) ?></div>
                        <div class="text-muted small"><?= Item::getUserRole($ownerId) === "user" ? "Member" : "Admin" ?></div>
                    </div>
                </div>
            </div>
            
            <?php if ($isOwner) : ?>

                <div class="card bg-dark p-3">
                    <?php if (!$item->isClosed() && !$item->getHasBids()): ?>
                        <div class="d-grid gap-2">
                            <a href="item/edit/<?= $item->getId() ?>" class="btn btn-outline-light btn-sm"><i class="bi bi-pencil me-2"></i> Edit Details</a>
                            <a href="manage_images.php?id=<?= $item->getId() ?>" class="btn btn-outline-info btn-sm"><i class="bi bi-image me-2"></i> Manage Images</a>
                            <a href="item/delete/<?= $item->getId() ?>" class="btn btn-outline-danger btn-sm"><i class="bi bi-trash me-2"></i> Delete Item</a>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-warning mb-0 small">
                            <i class="bi bi-exclamation-triangle-fill me-2"></i>
                            This item cannot be edited or deleted (<?= $item->isClosed() ? 'Closed' : 'Has Bids' ?>).
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div> 
    </div> 
</div> 

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<?php include 'view/utilités/footer.php'; ?>
</body>
</html>