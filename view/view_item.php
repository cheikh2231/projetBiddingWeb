
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Browse items</title>
    <base href="<?= $web_root ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="css/browse-item.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>

<body>


<header class="bg-dark border-bottom border-secondary sticky-top ">
    <?php $place = "browse"; ?>
    <div class="container d-flex justify-content-center align-items-center">
        <h5 class="m-0 me-1 text-primary">Browse</h5>
        <i class="bi bi-cart4 fs-4 text-white"></i>

 
    </div>

</header>

<div class="container py-5 px-2">

    <div id="browse-wrapper" data-place="<?= htmlspecialchars($place) ?>">
        
        <?php if (!empty($itemsUser)) : ?>
            <h4 class="text-primary" id="title-participating">Items I'm Participating In</h4>
            <div class="row g-4 items-container mb-5" id="items-participating">
                <?php foreach ($itemsUser as $item) : ?>
                    <?php include('view/utilités/_item_card.php') ?>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($items)) : ?>
            <h4 class="text-primary" id="title-other">Other Available Items</h4>
            <div class="row g-4 items-container" id="items-other">
                <?php foreach ($items as $item) : ?>
                    <?php include('view/utilités/_item_card.php') ?>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

    </div>

</div>


<?php include 'view/utilités/footer.php'; ?>

    <script src="js/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/main.js"></script>
</body>
</html>