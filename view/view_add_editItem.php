
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add/Edit Item</title>
    <base href="<?= $web_root ?>"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="css/add-edit.css">
</head>

<body data-bs-theme="dark" class="bg-primary-subtle">
    <?php
        $isEdit = $item && $item->getId();
        $titlePage = $isEdit ? "Edit Item" : "Add Item";
        $formAction = $isEdit ? "item/edit/" . $item->getId() . "/from-$source" : "item/add";

    ?>
<header class="bg-dark p-1 d-flex justify-content-center align-items-center border-bottom border-secondary mb-4 sticky-top position-relative">
    <a href="<?= $isEdit ? 'item/open/'.$item->getId().'/'.$source : 'item/browse' ?>" class="position-absolute start-0 ms-2">
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

        <div class="card-custom mb-4">
            <div class="card-header-custom">Basic Information</div>
            <div class="card-body p-4">

                <div class="mb-3">
                    <label class="form-label">Item Title *</label>
                    <input type="text" name="title" class="form-control" 
                           value="<?= htmlspecialchars($_POST['title'] ?? ($item ? $item->getTitle() : '')) ?>" 
                           placeholder="Ex: iPhone 13 Pro Max">
                    <?php if (!empty($errors['title'])): ?>
                        <div class="text-danger small mt-1">
                            <?php foreach ($errors['title'] as $error): ?>
                                <div><i class="bi bi-exclamation-circle"></i> <?= $error ?></div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="mb-3">
                    <label class="form-label">Description *</label>
                    <textarea class="form-control" name="description" rows="5"><?= htmlspecialchars($_POST['description'] ?? ($item ? $item->getDescription() : '')) ?></textarea>
                    <?php if (!empty($errors['description'])): ?>
                        <div class="text-danger small mt-1">
                            <?php foreach ($errors['description'] as $error): ?>
                                <div><i class="bi bi-exclamation-circle"></i> <?= $error ?></div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="mb-3">
                    <label class="form-label">Sale Duration (days) *</label>
                    <input type="number" name="duration" class="form-control" 
                           value="<?= htmlspecialchars($_POST['duration'] ??($item ? $item->getDurationDays() : 7)) ?>">
                    <?php if (!empty($errors['duration'])): ?>
                        <div class="text-danger small mt-1">
                            <?php foreach ($errors['duration'] as $error): ?>
                                <div><i class="bi bi-exclamation-circle"></i> <?= $error ?></div>
                            <?php endforeach; ?>
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
                            <input type="text" 
                                name="starting" 
                                class="form-control" 
                                inputmode="decimal" 
                                pattern="[0-9]*[.,]?[0-9]{0,2}" 
                                value="<?= $_POST['starting'] ?? ($item? $item->getStartingBid() : '') ?>" 
                                placeholder="100">
                            <span class="input-group-text">€</span>
                        </div>
                        
                        <?php if (!empty($errors['coupleStartingAndDirect'])): ?>
                            <div class="text-danger small mt-1">
                                <?php foreach ($errors['coupleStartingAndDirect'] as $error): ?>
                                    <div><i class="bi bi-exclamation-triangle"></i> <?= $error ?></div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>

                        <div class="mt-3 mb-0">
                            <label class="form-label">Instant Purchase Price <span class="text-optional">(optional)</span></label>
                            <div class="input-group">
                                <input type="text" name="instant_price" class="form-control" 
                                    value="<?= htmlspecialchars($_POST['instant_price'] ?? ($item?(($item->getIsAuction() && $item->getHasBuyNow()) ? $item->getBuyNowPrice() : ''):'')) ?>" 
                                    placeholder="10">
                                <span class="input-group-text">€</span>
                            </div>
                            <?php if (!empty($errors['instant'])): ?>
                                <div class="text-danger small mt-1">
                                    <?php foreach ($errors['instant'] as $error): ?>
                                        <div><i class="bi bi-exclamation-triangle"></i> <?= $error ?></div>
                                    <?php endforeach; ?>
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
                            <input type="text" name="sale_price" class="form-control" 
                                value="<?= htmlspecialchars($_POST['sale_price'] ?? ($item ? (($item->getIsDirectSale() ? $item->getBuyNowPrice() : '')):'')) ?>" 
                                placeholder="e.g., 150.00">
                            <span class="input-group-text">€</span>
                        </div>
                        <?php if (!empty($errors['coupleStartingAndDirect'])): ?>
                            <div class="text-danger small mt-1">
                                <?php foreach ($errors['coupleStartingAndDirect'] as $error): ?>
                                    <div><i class="bi bi-exclamation-triangle"></i> <?= $error ?></div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

            </div>
        </div>
    </form>
</div>



<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<?php include 'view/utilités/footer.php'; ?>

</body>
</html>