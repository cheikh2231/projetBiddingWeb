<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>My Items</title>
    <base href="<?= $web_root ?>" />
    <link href="css/browse-item.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
</head>

<body>

<header class="bg-dark border-bottom border-secondary sticky-top">
    <?php $place = "items"; ?>
    <div class="container d-flex justify-content-center align-items-center">
        <h5 class="m-0 me-1 text-primary">My Items</h5>
        <i class="bi bi-cart4 fs-4 text-white"></i>
    </div>
</header>

<div class="container py-5 ">

    <?php if (!empty($itemsUser)) : ?>
        <h4 class="text-primary">Active Items</h4>
        <div class="row g-4 mb-5">
            <?php foreach ($itemsUser as $item) : ?>

                <?php include('view/utilités/_item_card.php')?>

            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($unsold)) : ?>
        <h4 class="text-primary">Unsold Items</h4>
        <div class="row g-4 mb-5">
            <?php foreach ($unsold as $item) : ?>

                <?php include('view/utilités/_item_card.php')?>

            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <?php if (!empty($sold)) : ?>
        <h4 class="text-primary">Sold Items</h4>
        <div class="row g-4">
            <?php foreach ($sold as $item) : ?>
                <?php include('view/utilités/_item_card.php')?>

            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
<?php include 'view/utilités/footer.php'; ?>

<?php include 'view/utilités/footer.php'; ?>

</body>
</html>