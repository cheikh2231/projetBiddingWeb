<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title>
        <?=$item->title?> 
    </title>
      <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
      rel="stylesheet"
    />
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
    />
    <link href="css/styles.css" rel="stylesheet" type="text/css" />

</head>
<body data-bs-theme="dark" class="bg-primary-subtle ">
    <header
      class="bg-dark p-0 d-flex justify-content-center align-items-center border-bottom border-secondary mb-4 sticky-top"
    >
      <h5 class="m-0 me-2 text-primary">Item open</h5>
      <i class="bi bi-cart4 fs-4 text-white"></i>
    </header>
    <div class="card card-custom position-relative w-50 h-10 text-decoration-none data-bs-theme=dark class=bg-dark">
        
        <?php $pic = $item->getPictures();?>
        <img
            src="<?=  $web_root . $pic[0] ?>"
        
            alt="<?= htmlspecialchars($item->title) ?>"
        />
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>