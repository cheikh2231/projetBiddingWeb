<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>sales</title>
    <base href="<?= $web_root ?>"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="css/browse-item.css" rel="stylesheet" type="text/css"/>
</head>

<body>

<header class="bg-dark p-0 d-flex justify-content-center align-items-center border-bottom border-secondar sticky-top">
    <a href="item/open/<?= $item->getId()?>/from-items"><i class="bi bi-arrow-left-short fs-3 text-primary position-absolute start-0 top-0"></i></a>
    <h5 class="m-0 me-2 text-primary">Manage images</h5>
    <i class="bi bi-cart4 fs-4 text-white"></i>
</header>
</body>
</html>