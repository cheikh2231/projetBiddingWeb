<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Item</title>
    <base href="<?= $web_root ?>"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="css/add-edit.css">
</head>
<body>

<header class="bg-dark p-1 d-flex justify-content-center align-items-center border-bottom border-secondary mb-4 sticky-top position-relative">
  
    <a href="item/open/<?= $item->id ?>/from-<?= $source=="list"?"items":$source ?>" class="position-absolute start-0 ms-2">
        <i class="bi bi-arrow-left-short fs-3 text-primary"></i>
    </a>

    <div class="d-flex align-items-center">
        <h5 class="m-0 me-2 text-primary">Add/Edit</h5>
        <i class="bi bi-cart4 fs-4 text-white"></i>
    </div>

<button type="submit" form="addItemForm" class="position-absolute end-0 me-3 btn btn-link p-0">
    <i class="bi bi-floppy fs-4 text-primary"></i>
</button>

</header>

<div class="container mb-5">
    <form id="addItemForm" action="item/edit/<?= $item->id ?>" method="POST">

        <div class="card-custom">
            <div class="card-header-custom">Basic Information</div>
            <div class="card-body p-4">

             <div class="mb-2">
                <label class="form-label">Item Title *</label>
                <input type="text"
                    name="title"
                    class="form-control"
                    value="<?= htmlspecialchars($item->title ?? '') ?>"
                    placeholder="Ex: iPhone 13 Pro Max 256GB"
                    >
                <?php if (!empty($errors['title'])): ?>
                    <div class='errors text-danger '>
                        <ul>
                            <?php foreach ($errors['title'] as $error): ?>
                                <li><?= $error ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
             </div>

            <div class="mb-4">
                <label class="form-label">Description</label>
                <textarea class="form-control" name="description" rows="5" placeholder="Describe your item in detail..."><?= htmlspecialchars($_POST['description'] ?? $item->description ?? '') ?></textarea>
                <?php if (!empty($errors['description'])): ?>
                    <div class='errors text-danger '>
                        <ul>
                            <?php foreach ($errors['description'] as $error): ?>
                                <li><?= $error ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>            
            </div>

            <div class="mb-3">
                <label class="form-label">Sale Duration (days) *</label>
                <input type="number"
                    name="duration"
                    class="form-control "
                    value="<?= htmlspecialchars( $item->duration_days ?? 7) ?>">
                <?php if (!empty($errors['duration'])): ?>
                    <div class='errors text-danger '>
                        <ul>
                            <?php foreach ($errors['duration'] as $error): ?>
                                <li><?= $error ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif;?>    
            </div>

            <div class="sale-option-auction mb-4">
                <label class="form-label">Starting Bid</label>
                <div class="input-group">
                    <input type="text"
                        name="starting"
                        class="form-control <?= isset($errors['starting']) ? 'is-invalid' : '' ?>"
                        value="<?= htmlspecialchars($_POST['starting'] ?? $item->starting ?? '') ?>"
                        placeholder="e.g., 50.00">
                    <span class="input-group-text">€</span>
                </div>
             
            </div>

            <div class="sale-option-direct mb-4">
                <label class="form-label">Sale Price</label>
                <div class="input-group">
                    <input type="text"
                        name="sale_price"
                        class="form-control <?= isset($errors['sale_price']) ? 'is-invalid' : '' ?>"
                        value="<?= htmlspecialchars($_POST['sale_price'] ?? $item->sale_price ?? '') ?>"
                        placeholder="e.g., 150.00">
                    <span class="input-group-text">€</span>
                </div>

            </div>

    </form>
       
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
