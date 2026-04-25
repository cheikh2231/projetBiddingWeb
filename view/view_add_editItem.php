
<?php  
    $config = parse_ini_file('config/dev.ini');
    $defautDuration  = $config['duration_default_value'];
    $isEdit = $item && $item->getId();
    $titlePage = $isEdit ? "Edit Item" : "Add Item";
    $formAction = $isEdit ? "item/edit/" . $item->getId() . ($encoded ? "/$encoded" : "") : "item/add";
    $place = !$isEdit ? "Add":"Edit";

    $title = $_POST['title'] ?? ($item ? $item->getTitle() : '');
    $description = $_POST['description'] ?? ($item? $item->getDescription() : '');
    $duration = $_POST['duration'] ??($item ? $item->getDurationDays() : $defautDuration);
    $starting =  $_POST['starting'] ?? ($item && $item->getIsAuction() ? $item->getStartingBid() : '');
    $instant_price = $_POST['instant_price'] ?? ($item?(($item->getIsAuction() && $item->getHasBuyNow()) ? $item->getBuyNowPrice() : ''):'');
    $sale_price = $_POST['sale_price'] ?? ($item ? (($item->getIsDirectSale() ? $item->getBuyNowPrice() : '')):'');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
  
    <title><?=$place?> Item</title>
    <base href="<?= $web_root ?>">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    
    <link rel="stylesheet" href="css/add-edit.css">
</head>

<body data-bs-theme="dark" class="bg-primary-subtle">
<header class="bg-dark p-1 d-flex justify-content-center align-items-center border-bottom border-secondary mb-4 sticky-top position-relative">
    <a href="<?= $isEdit ? 'item/open/'.$item->getId().'/'.$source.($encoded ? '/'.$encoded : "") : 'item/browse' ?>" class="position-absolute start-0 ms-2">
        <i class="bi bi-arrow-left-short fs-3 text-primary"></i>
    </a>

    <div class="d-flex align-items-center">
        <h5 class="m-0 me-2 text-primary"><?= $titlePage ?></h5>
        <i class="bi bi-cart4 fs-4 text-white"></i>
    </div>

    <button type="submit" form="editItemForm" class="position-absolute end-0 me-3 btn btn-link p-0">
        <i class="bi bi-floppy fs-4 text-primary"></i>
    </button>
</header>


<div class="container mb-5 py-5">
    <form id="editItemForm" action="<?= $formAction ?>" method="POST">

        <input type="hidden" name="item_id" value="<?= $item ? $item->getId() : '' ?>">

        <div class="card-custom mb-4">
            <div class="card-header-custom">Basic Information</div>
            <div class="card-body p-4">

                <div class="mb-3">
                    <label class="form-label">Item Title *</label>
                    <input type="text" name="title" id="titleInput" class="form-control" value="<?= $title ?>" placeholder="Ex: iPhone 13 Pro Max">

                    <div id="titleError" class="invalid-feedback fw-bold"></div>   
                    <?php if (isset($errors['title'])): ?>
                        <div class="text-danger small mt-1 php-error">
                            <ul>
                                <?php foreach ($errors['title'] as $i): ?> 
                                    <li><span class="text-danger"><?= $i ?></span></li>
                                <?php endforeach;?>
                            </ul> 
                        </div>
                    <?php endif; ?>
                </div>

                <div class="mb-3">
                    <label class="form-label">Description *</label>
                    <textarea class="form-control" name="description" id="descriptionInput" rows="5"><?= $description ?></textarea>

                    <div id="descriptionError" class="invalid-feedback fw-bold"></div>                     
                    <?php if (isset($errors['description'])): ?>
                        <div class="text-danger small mt-1 php-error">
                            <ul>
                                <?php foreach ($errors['description'] as $i): ?> 
                                    <li><span class="text-danger"><?= $i ?></span></li>
                                <?php endforeach;?>
                            </ul> 
                        </div>
                    <?php endif; ?>
                </div>

                <div class="mb-3">
                    <label class="form-label">Sale Duration (days) *</label>

                    <input type="number" name="duration" id="durationInput" class="form-control" value="<?= $duration ?>">
                    <div id="durationError" class="invalid-feedback fw-bold"></div>
                    <?php if (isset($errors['duration'])): ?>
                        <div class="text-danger small mt-1 php-error">
                            <ul>
                                <?php foreach ($errors['duration'] as $i): ?> 
                                    <li><span class="text-danger"><?= $i ?></span></li>
                                <?php endforeach;?>
                            </ul> 
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="card-custom mb-4">
            <div class="card-header-custom">Sale Type</div>
            <div class="card-body p-4">

                <div class="sale-option-auction mb-3">
                    <div class="d-flex align-items-center mb-3 text-primary">
                        <i class="bi bi-hammer me-2"></i>
                        <span class="fw-bold">Option 1: Auction</span>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Starting Bid</label>
                
                    <div class="input-group">
                        <input type="text" name="starting" id="startingInput" class="form-control" inputmode="decimal" value="<?= $starting ?>" placeholder="100">
                        <span class="input-group-text">€</span>
                        <div id="startingAndOrSaleError" class="invalid-feedback fw-bold"></div>
                        <?php if (isset($errors['startingAndOrSale'])): ?>
                            <div class="text-danger small mt-1 php-error">
                                <ul>
                                    <?php foreach ($errors['startingAndOrSale'] as $i): ?> 
                                        <li><span class="text-danger"><?= $i ?></span></li>
                                    <?php endforeach;?>
                                </ul> 
                            </div>
                        <?php endif; ?>
                    </div>

                        <div class="mt-3 mb-0">
                            <label class="form-label">Instant Purchase Price <span class="text-optional">(optional)</span></label>
                            <div class="input-group">
                                <input type="text" name="instant_price" id="instantPriceInput" class="form-control" value="<?= $instant_price ?>" placeholder="10">
                                <span class="input-group-text">€</span>
                            </div>

                            <div id="instantPriceError" class="invalid-feedback fw-bold"></div>
                            <?php if (isset($errors['instant_price'])): ?>
                                <div class="text-danger small mt-1 php-error">
                                    <ul>
                                        <?php foreach ($errors['instant_price'] as $i): ?> 
                                            <li><span class="text-danger"><?= $i ?></span></li>
                                        <?php endforeach;?>
                                    </ul> 
                                </div>
                            <?php endif; ?>
                        </div>                        
                    </div>
                </div>

                <div class="sale-option-direct">
                    <div class="d-flex align-items-center mb-3 text-success">
                        <i class="bi bi-cart2 me-2"></i>
                        <span class="fw-bold">Option 2: Direct Sale</span>
                    </div>
                    
                    <div class="mb-0">
                        <label class="form-label">Sale Price</label>
                
                       <div class="input-group">
                            <input type="text" name="sale_price" id="salePriceInput" class="form-control" value="<?= $sale_price ?>" placeholder="e.g., 150.00">
                            <span class="input-group-text">€</span>
                            <div id="salePriceError" class="invalid-feedback fw-bold"></div>
                            <?php if (isset($errors['startingAndOrSale'])): ?>
                                <div class="text-danger small mt-1 php-error">
                                    <ul>
                                        <?php foreach ($errors['startingAndOrSale'] as $i): ?> 
                                            <li><span class="text-danger"><?= $i ?></span></li>
                                        <?php endforeach;?>
                                    </ul> 
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </form>
</div>



<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<?php include 'view/utilités/footer.php'; ?>

<div class="modal fade" id="confirmLeaveModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content text-white">
      <div class="modal-header">
        <h5 class="modal-title">Unsaved changes</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        You have unsaved changes. Leave anyway ?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-primary" id="confirmLeaveBtn">Leave</button>
      </div>
    </div>
  </div>
</div>

<script src="js/add-edit-item.js"></script>

</body>
</html>

</body>
</html>