<?php $pics = $item->getPictures(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>sales</title>
    <base href="<?= $web_root ?>"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="css/manage-images.css" rel="stylesheet" type="text/css"/>
</head>

<body>

    <header class="bg-dark p-0 d-flex justify-content-center align-items-center border-bottom border-secondar sticky-top">
        <a href="item/open/<?= $item->getId()?>/from-items"><i class="bi bi-arrow-left-short fs-3 text-primary position-absolute start-0 top-0"></i></a>
        <h5 class="m-0 me-2 text-primary">Manage images</h5>
        <i class="bi bi-cart4 fs-4 text-white"></i>
    </header>

<div class="container mt-5">
    <div class="card  text-white">
        <div class="card-header ">Add New Images</div>
        <div class="card-body bg-dark ">
            <form method="POST" action="item/manage_images/<?=$item->getId()?>" enctype="multipart/form-data">
                
                <label for="imageInput">Select Images</label>
                <input type="file" id="imageInput" name="images[]" multiple accept="image/*">
                
                <p class="hint mt-2">You can select multiple images (JPG, PNG, GIF, WebP). Images will be added to the end of your current list.</p>
                
                <button type="submit" class="btn-upload">Upload Images</button>
            
            </form> 
            </div>
    </div>

    <div class="card text-white">
        <div class="card-header">Current Images</div>
        <div class="card-body" id="previewContainer">
            <?php if (!empty($pics)) : ?>
                <div class="d-flex flex-wrap mb-3">
                    <?php for ($i=0; $i<count($pics) ;$i++) : ?>
                       
                        <div class="cas me-3" style=width:180px;>
                            <img src="<?= $web_root . $pics[$i] ?>" class = "img-thumbnail">
                            
                        </div>
                    <?php endfor; ?>


                </div>
            <?php endif; ?> 
        </div>
    </div>
</div>


</body>
</html>