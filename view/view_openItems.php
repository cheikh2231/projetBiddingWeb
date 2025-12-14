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
            </div>
                    
            <div class="col-12 col-md-5 col-lg-5">
                <div class="card" style="width: 20rem;">
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
                        <div class="input-group mb-3">
                            <span class="input-group-text" id="basic-addon1">€</span>
                            <?php $valdepart = number_format($item->has_bids ? $item->max_bid : $item->starting_bid );?>
                            <input type="number" id = "price" class="form-control" min = "<?= $valdepart?>" value="<?= $valdepart ?>" aria-describedby="basic-addon1">
                        
                        </div>
                        
                    </div> 
                </div>   
            </div>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>