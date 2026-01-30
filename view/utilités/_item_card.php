<?php
    $pictures = $item->getPictures();
    $itemOwner = $item->getOwner();
    $username = $itemOwner->getPseudo();
    $isNotOwner = false;
    $isMaxBidder = false;
    if($currentUser!=null){
        $isNotOwner = $itemOwner!=$currentUser;
        $isMaxBidder = $item->getMaxBidder()!=null ?  $item->getMaxBidder()==$currentUser:false;
    }
?>
<div class="col-12 col-sm-6 col-md-4 col-lg-3">
    <a href="Item/open/<?= $item->getId() ?>/from-<?= $place ?>"
        class="item-card d-block position-relative flex-grow-1">

        <div class="badge-container">
            <div>
                <?php if($isNotOwner && $currentUser->hasBidIn($item)) :?>
                        <?php if ($isMaxBidder): ?>
                            <span class="badge badge-green">Highest Bidder</span>
                        <?php else : ?>
                            <span class="badge badge-dark">Bidder</span>
                        <?php endif; ?>
                 <?php endif;?>
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

            <?php else: ?>    
                <img src="https://clipart-library.com/2023/disappearance-clipart-33563200-MISSING-red-Rubber-Stamp-over-a-white-background--Stock-Photo.jpg"  alt=""/>
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
            <?php if($item->isClosed()) :?>
                  <div class="time-left">
                    <i class="bi bi-clock-history text-white-50"> closed</i>
                </div>   
            <?php else :
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
            <?php endif;?> 
        </div>
        
    </a>
    
    <?php if ($item->getWinner() != null):?>
    
            <div class="item-footer-sold mt-2 pb-0 text-secondary">
                    <div class="d-flex align-items-center gap-2">
                        <?php if($place == "sales"): ?>
                            <i class="bi bi-currency-euro"></i>
                        <?php else:?>
                            <i class="bi bi-tag-fill"></i>
                        <?php endif;?>
                        <span><?= ($place == "sales") ? " Final price":" Paid" ?> € <?= number_format($item->getMaxBid(), 2) ?></span>
                    </div>
                    <?php if($place == "sales"): ?>
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-trophy"></i>
                            <span><?= htmlspecialchars($item->getWinner()->getPseudo()) ?></span>
                        </div>
                    <?php endif;?>
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-clock"></i>
                        <span>Closed on <?= (new DateTime($item->getBids()[0]->getCreatedAt()))->format('d/m/Y H:i:s') ?></span>
                    </div>
            </div>
       
    <?php endif;?>
    
</div>
