<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title>
        <?=$item->title?> 
    </title>
        <base href="<?= $web_root ?>"/>

      <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
      rel="stylesheet"
    />
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
    />
    <link href="css/open.css" rel="stylesheet" type="text/css" />

</head>
<body data-bs-theme="dark" class="bg-primary-subtle ">
    
    <header
      class="bg-dark p-0 d-flex justify-content-center align-items-center border-bottom border-secondary mb-4 sticky-top"
    >
        <a href="Item/browse">
         <i class="bi bi-arrow-left-short fs-3 text-primary position-absolute start-0 top-0"></i>
        </a>
        <h5 class="m-0 me-2 text-primary ">Item open</h5>
        <i class="bi bi-cart4 fs-4 text-white"></i>
    </header>
   <div class="container">
       <div class="row ">
            <div class="col-12 col-md-4 col-lg-7">
                <div class="card">
                    <div class="ratio ratio-16x9">
                        <?php $pic = $item->getPictures();
                         if(!empty($pic)) :?>
                        
                            <img
                                
                                src="<?= $web_root. $pic[$idImg>0?$idImg-1:$idImg] ?>"
                                class="card-img-top "
                                alt="<?= htmlspecialchars($item->title) ?>"
                            />
                            
                        <?php endif; ?>
                    </div>   
                    <div class="info-item p-2">
                        <div class = "title mt-1">
                            <?= htmlspecialchars($item->title)  ?>
                        </div>
                        <p class="text-muted ">
                            <small><?= $item->description ?></small>
                        </p>
                        <p class="text-muted ">
                            <small>Start: <?=$item->created_at?></small><br>
                            <small>Ends: <?= $item->end_at ?></small>
                        </p>
                    </div>  
                </div>
                <p class="text-muted mt-2">
                    <small>Additional Images</small>
                </p>
                <?php if (!empty($pic)): ?>
                   
                   <div class="d-flex flex-wrap ">  
                    
                         <?php for ($i=0; $i < count($pic); $i++) { ?>
                            
                            <?php $numberPic = $i+1 ;
                               $isSelected = ($i === ($idImg > 0 ? $idImg - 1 : 0));
                            ?>
                   
                            <a href = "item/open/<?=$item->id?>/<?=$source?>/<?=$numberPic ?> " >
                            
                                <div class = "cas me-2 <?= $isSelected ? 'selected' : '' ?>" style="width: 150px;" >
                                    
                                <img src="<?=$pic[$i]?>" class="img-thumbnail " >
                                
                                </div>
                            </a>
                        <?php } ?>
                    </div>
                <?php endif; ?>
                <style type="text/css">
                .bid-panel-dark {
                    background-color: #0b111a !important; /* Le noir bleuté profond de l'image */
                    border-radius: 8px;
                    padding: 20px;
                    color: #ffffff !important;
                    font-family: sans-serif;
                    margin-top: 20px;
                    border: 1px solid #1e293b;
                }

                .bid-header-row {
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    margin-bottom: 20px;
                }

                .bid-title-text {
                    color: #94a3b8 !important; /* Gris bleuté pour le titre */
                    font-size: 14px;
                    font-weight: bold;
                    text-transform: uppercase;
                }

                .bid-badge {
                    background-color: #ffffff !important;
                    color: #000000 !important;
                    padding: 2px 12px;
                    border-radius: 15px;
                    font-size: 12px;
                    font-weight: bold;
                }

                .bid-row-item {
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    padding: 12px 0;
                    border-bottom: 1px solid #1e293b;
                }

                .bid-row-item:last-child {
                    border-bottom: none;
                }

                .bidder-name-text {
                    color: #ffffff !important;
                    font-weight: bold;
                    font-size: 16px;
                    margin-bottom: 4px;
                }

                .bid-date-text {
                    color: #64748b !important;
                    font-size: 13px;
                }

                .bid-price-green {
                    color: #10b981 !important; /* Le vert émeraude vif de la photo */
                    font-weight: bold;
                    font-size: 18px;
                }
            </style>

            <div class="bid-panel-dark">
                <div class="bid-header-row">
                    <span class="bid-title-text">Bid History</span>
                    <span class="bid-badge">3 entries</span>
                </div>

                <div class="bid-row-item">
                    <div>
                        <div class="bidder-name-text">Xavier</div>
                        <div class="bid-date-text">16/11/2025 03:27:14</div>
                    </div>
                    <div class="bid-price-green">€ 162,00</div>
                </div>

                <div class="bid-row-item">
                    <div>
                        <div class="bidder-name-text">Quentin</div>
                        <div class="bid-date-text">15/11/2025 01:30:10</div>
                    </div>
                    <div class="bid-price-green">€ 152,00</div>
                </div>

                <div class="bid-row-item">
                    <div>
                        <div class="bidder-name-text">Quentin</div>
                        <div class="bid-date-text">14/11/2025 05:01:54</div>
                    </div>
                    <div class="bid-price-green">€ 140,00</div>
                </div>
            </div>
            </div>
            <div class="col-12 col-md-6 col-lg-5 ">
                <div class="card mb-2" style="width: 20rem; border: 2px;">
                    <div class="card-body">
                     
                        <small  class="text-muted mt-1 ">Pricing</small><br>
                        <div class="d-flex justify-content-between me">
                            <span>Current Bid</span>
                            <span class = "text-success fw-bold">€ <?=number_format($item->has_bids ? $item->max_bid : $item->starting_bid ?? 0, 2)?></span>                        
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Buy Now</span>
                            <span class = " fw-bold">€ <?= number_format($item->has_buy_now ? $item->buy_now_price : $item->starting_bid ?? 0, 2) ?></span>                        
                        </div><br>
                        <div class="input-group mb-2">
                            <span class="input-group-text" id="basic-addon1">€</span>
                            <?php $valdepart = number_format($item->has_bids ? $item->max_bid : $item->starting_bid );?>
                            <input type="number" id = "price" class="form-control" min = "<?= $valdepart?>" value="<?= $valdepart ?>" aria-describedby="basic-addon1">
                        
                        </div>
                        <div class="d-grid gap-2">
                            <?php if($item->has_buy_now) : ?>
                                <button class="btn btn-lg btn-success fs-5" type="submit">
                                    Place Bid
                                </button>

                                <?php if ($item->has_buy_now) : ?>
                                <a
                                    href="item/open/<?=$item->id?>"  class="btn btn-lg btn-outline-primary fs-6"
                                    role="button"
                                >
                                    Buy Now at € <?= number_format($item->buy_now_price, 2) ?>
                                   
                                </a>
                                <?php endif; ?>
                            <?php else:?>
                                <small  class="text-muted mt-1">Congratulations you purchased the item for € <?= number_format($item->has_buy_now ? $item->buy_now_price : $item->starting_bid ?? 0, 2) ?></small><br>
                            <?php endif; ?>
                        </div>

                      
                    </div>
                   
                </div>  
                 <div class="card" style="width: 20rem; border: 25px;">
                    <div class="card-body">
                        <small  class="text-muted mt-1">Seller Information</small><br>
                        <div class="d-flex align-items-center mt-1">
                            <?php if(Item::getUserPic($item->owner) != null) :?>
                              <img src="<?= Item::getUserPic($item->owner) ?>" alt="Boris" class="rounded-circle me-3" style="width: 3rem; height: 3rem;">
                            <?php else:?> 
                                <i class="bi bi-person-circle fs-2" style="width: 3rem; height: 3rem;"></i>
                            <?php endif; ?>
                            
                            <div>
                                <div class="fw-bold"><?= htmlspecialchars(Item::itemUserName($item->owner))?></div>
                                <div class="text-muted" style="font-size: 0.875rem;"><?=Item::getUserRole($item->owner)=="user"?"Member":"Admin"?></div>
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