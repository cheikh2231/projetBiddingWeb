
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Browse items</title>
    <base href="<?= $web_root ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="css/browse-item.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
</head>

<body>


<header class="bg-dark border-bottom border-secondary sticky-top ">
    <a href="login/logout " >
        <i class="bi bi-box-arrow-right me-2"></i> Sign Out
    </a> 
    <?php $place = "browse"; ?>
    <div class="container d-flex justify-content-center align-items-center">
        <h5 class="m-0 me-1 text-primary">Browse</h5>
        <i class="bi bi-cart4 fs-4 text-white"></i>

 
    </div>

</header>

<div class="container py-5">

    <?php if (!empty($itemsUser)) : ?>
        <h4 class="text-primary">Items I'm Participating In</h4>

        <div class="row g-4 mb-5">
            <?php foreach ($itemsUser as $item) : ?>

                <?php include('view/utilités/_item_card.php')?>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    <?php if (!empty($items)) : ?>
        <h4 class="text-primary">Other Available Items</h4>
        <div class="row g-4">
            <?php foreach ($items as $item) : ?>

                <?php include('view/utilités/_item_card.php')?>

            <?php endforeach; ?>
        </div>
    <?php endif; ?>
    
</div>


<?php include 'view/utilités/footer.php'; ?>


</body>
</html>