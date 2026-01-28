<?php 
require_once 'model/Bids.php';
require_once 'model/User.php';




$bids = Bids::getBids($item->getId());


$isOwner = $currentUser && ($currentUser->getId() === (int)$item->getOwner());

$isWinner = $item->getBuyNowReached() && !empty($bids) && $currentUser && ((int)$bids[0]->owner === $currentUser->getId());
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
    <?php 
        $backRoute = (strpos($source, 'items') !== false) ? "item/list" : "item/browse";
    ?>
    <a href="<?= $backRoute ?>">
        <i class="bi bi-arrow-left-short fs-3 text-primary position-absolute start-0 top-0"></i>
    </a>
    <h5 class="m-0 me-2 text-primary">Item open</h5>
    <i class="bi bi-cart4 fs-4 text-white"></i>
</header>

<div class="container pb-5" style="margin-bottom: 80px;">
    <div class="row ">

        <div class="col-12 col-md-6 col-lg-7">
            <div class="card mb-3">
                <div class="ratio ratio-16x9">
                    <?php $pic = $item->getPictures(); ?>
                    <?php if (!empty($pic)) : ?>
                        <img src="<?= $web_root . $pic[$idImg > 0 ? $idImg - 1 : 0] ?>" class="card-img-top" alt="<?= htmlspecialchars($item->getTitle()) ?>"/>
                    <?php else:?>
                         <img src="https://clipart-library.com/2023/disappearance-clipart-33563200-MISSING-red-Rubber-Stamp-over-a-white-background--Stock-Photo.jpg" class="card-img-top" alt=""/>
                    <?php endif; ?>
                </div>
                <div class="info-item p-2">
                    <div class="title mt-1 fw-bold"><?= htmlspecialchars($item->getTitle()) ?></div>
                    <p class="text-muted"><small><?= htmlspecialchars($item->getDescription()) ?></small></p>
                    <p class="text-muted">
                        <small>Start: <?= $item->getCreatedAt() ?></small><br>
                        <small>Ends: <?= $item->getEndAt() ?></small>
                    </p>
                </div>
            </div>

            <?php if (!empty($pic)) : ?>
                <div class="d-flex flex-wrap mb-3">
                    <?php for ($i = 0; $i < count($pic); $i++) : 
                        $isSelected = ($i === ($idImg > 0 ? $idImg - 1 : 0));
                    ?>
                        <a href="item/open/<?= $item->getId() ?>/<?= $source ?>/<?= $i + 1 ?>">
                            <div class="cas me-2 <?= $isSelected ? 'border border-primary border-2' : '' ?>" style="width:80px;">
                                <img src="<?= $pic[$i] ?>" class="img-thumbnail">
                            </div>
                        </a>
                    <?php endfor; ?>
                </div>
            <?php endif; ?>
            <?php if(!empty($bids)) : ?>

            <div class="bid-panel-dark mt-3 p-3 bg-dark rounded border border-secondary">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="fw-bold"><?= !$item->getIsDirectSale() ? "Bid":" Purchase"?> History</span>
                        <span class="badge bg-secondary"><?= !empty($bids) ? count($bids) : 0 ?> entries</span>
                    </div>
                    <?php foreach ($bids as $bid) : $u = User::getUserById($bid->owner); ?>

                        <div class="d-flex justify-content-between align-items-center border-bottom border-secondary py-2">
                            <div>
                                <div class="fw-bold text-light small"><?= $u ? htmlspecialchars($u->getPseudo()) : "Unknown" ?></div>
                                <div class="text-muted" style="font-size: 0.75rem;"><?= htmlspecialchars($bid->created_at) ?></div>
                            </div>
                            <div class="text-success fw-bold">€ <?= number_format($bid->amount, 2) ?></div>
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

                    <?php if ($isWinner): ?>
                        <div class="alert alert-success bg-transparent border-0 text-center p-0 mb-0">
                            <p class="mb-0 fw-bold text-success">Congratulations! You purchased this item for € <?= number_format($item->getMaxBid(), 2) ?>.</p>
                        </div>

                    <?php elseif ($isOwner): ?>
                        <div class="d-grid gap-2">
                            <?php if ($item->getIsAuction()): 
                                $current = $item->getHasBids() ? $item->getMaxBid() : $item->getStartingBid(); ?>
                                <div class="input-group mb-0">
                                    <span class="input-group-text bg-secondary border-secondary text-white">€</span>
                                    <input type="number" class="form-control bg-dark text-white border-secondary" value="<?= $current ?>" readonly>
                                </div>
                                <button class="btn btn-lg btn-success fs-5 opacity-50 fw-bold" disabled>Place Bid</button>
                            <?php endif; ?>

                            <?php if ($item->getHasBuyNow()): ?>
                                <button class="btn btn-lg btn-success fs-5 opacity-50 fw-bold" disabled>
                                    <?= !$item->getIsDirectSale() ? '<i class="bi bi-cart4 me-2"></i>' : '' ?>Buy now
                                </button>
                            <?php endif; ?>

                            <small class="text-center text-muted" style="font-size: 0.75rem;">You cannot bid on your own listing.</small>
                        </div>
                                             
                    <?php else: ?>
                        <form method="post" action="item/open/<?= $item->getId() ?>/<?= $source ?>">
                            <?php if ($item->getIsAuction()): ?>
                                <?php $minBid = ($item->getHasBids() ? $item->getMaxBid() : $item->getStartingBid()) + 1; ?>
                                <div class="input-group mb-3">
                                    <span class="input-group-text bg-secondary border-secondary text-white">€</span>
                                    <input type="number" name="amount" class="form-control bg-dark text-white border-secondary" min="<?= $minBid ?>" value="<?= $minBid ?>" required>
                                </div>
                                <div class="d-grid gap-2 mb-2">
                                    <button class="btn btn-lg btn-success fs-5 fw-bold" type="submit">Place Bid</button>
                                </div>
                            <?php endif; ?>

                            <?php if ($item->getHasBuyNow()): ?>
                                <div class="d-grid">
                                    <button type="submit" name="buy_now" value="1" class="btn <?= $item->getIsAuction() ? 'btn-outline-primary border-2' : 'btn-lg btn-success fw-bold' ?>">
                                        <i class="bi bi-cart4 me-2"></i>Buy Now <?= $item->getIsAuction() ? "at € " . number_format($item->getBuyNowPrice(), 2) : "" ?>
                                    </button>
                                </div>
                            <?php endif; ?>
                        </form>
                    <?php endif; ?>
                </div>
            </div>

            <div class="card mb-3 w-100 bg-dark">
                <div class="card-body">
                    <small class="text-muted text-uppercase fw-bold" style="font-size: 0.7rem;">Seller Information</small>
                    <div class="d-flex align-items-center mt-2">
                        <?php $userPic = Item::getUserPic($item->getOwner()); ?>
                        <?php if ($userPic) : ?>
                            <img src="<?= $userPic ?>" class="rounded-circle me-3" style="width:3rem;height:3rem; object-fit: cover;">
                        <?php else : ?>
                            <i class="bi bi-person-circle fs-1 me-3 text-secondary"></i>
                        <?php endif; ?>
                        <div>
                            <div class="fw-bold text-light"><?= htmlspecialchars(Item::itemUserName($item->getOwner())) ?></div>
                            <div class="text-muted small">
                                <?= Item::getUserRole($item->getOwner()) === "user" ? "Member" : "Admin" ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <?php if ($isOwner) : ?>
                <?php if (!$item->isClosed() && !$item->getHasBids()): ?>
                    <div class="card w-100 bg-dark">
                        <div class="card-body">
                            <small class="text-muted fw-bold" style="font-size: 0.9rem;">Manage Your Item</small>
                            <div class="d-grid gap-2 mt-3">
                                <a href="item/edit/<?= $item->getId() ?>" class="btn btn-outline-light d-flex align-items-center justify-content-center py-2">
                                    <i class="bi bi-pencil me-2"></i> Edit Item Details
                                </a>
                                <a href="manage_images.php?id=<?= $item->getId() ?>" class="btn btn-outline-info d-flex align-items-center justify-content-center py-2">
                                    <i class="bi bi-image me-2"></i> Manage Images
                                </a>
                                <a href="item/delete/<?= $item->getId() ?>" class="btn btn-outline-danger d-flex align-items-center justify-content-center py-2">
                                    <i class="bi bi-trash me-2"></i> Delete Item
                                </a>
                            </div>
                        </div>
                    </div> 
                <?php elseif ($item->isClosed()) : ?> 
                    <div class="alert alert-warning mt-3 mb-0" style="font-size: 0.85rem;">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        This item cannot be edited or deleted because it is closed.
                    </div>
                <?php else : ?>
                    <div class="alert alert-warning mt-3 mb-0" style="font-size: 0.85rem;">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        This item cannot be edited or deleted because it already has active bids.
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </div> 
    </div> 
</div> 


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<?php include 'view/utilités/footer.php'; ?>
</body>
</html>