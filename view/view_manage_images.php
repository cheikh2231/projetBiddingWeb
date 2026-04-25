<?php $pics = $item->getPictures(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>manage_images</title>
    <base href="<?= $web_root ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="css/manage-images.css" rel="stylesheet" type="text/css">
</head>

<body>

    <header class="bg-dark p-0 d-flex justify-content-center align-items-center border-bottom border-secondar sticky-top">
        <a href="item/open/<?= $item->getId()?>/from-items<?=($encoded ? '/'.$encoded : "") ?>"><i class="bi bi-arrow-left-short fs-3 text-primary position-absolute start-0 top-0"></i></a>
        <h5 class="m-0 me-2 text-primary">Manage images</h5>
        <i class="bi bi-cart4 fs-4 text-white"></i>
    </header>

<div class="container mt-5">
    <div class="card  text-white">
        <div class="card-header ">Add New Images</div>
        <div class="card-body bg-dark ">
            <form method="POST" action="item/manage_images/<?=$item->getId()?><?=($encoded ? '/'.$encoded : "")?>" enctype="multipart/form-data">
                
                <label for="imageInput">Select Images</label>
                <input type="file" id="imageInput" name="image" multiple accept="image/*">
                
                <p class="hint mt-2">You can select multiple images (JPG, PNG, GIF, WebP). Images will be added to the end of your current list.</p>
                
                <button type="submit" class="btn-upload">Upload Images</button>
            
            </form> 
            </div>
    </div>

    <div class="card text-white">
        <div class="card-header">Current Images</div>
        <div class="card-body bg-dark" id="previewContainer">
            <?php if (!empty($pics)) : ?>
                <div class="d-flex flex-wrap mb-3" id="sortable-images" itemId="<?= $item->getId() ?>">
                    <?php for ($i=0; $i<count($pics) ;$i++) : ?>
                       
                        <div class="cas me-3 mb-3 sortable-item" dataOrder="<?= $i+1 ?>" style="width:180px;">
                            <img src="<?= $web_root . $pics[$i] ?>" class = "img-thumbnail" alt = "itamImage">

                            <form method = "POST" action = "item/manage_images/<?= $item->getId() ?><?=($encoded ? '/'.$encoded : "")?>" class = "mt-1 d-flex justify-content-between"  >


                                <input type="hidden" name="image_index" value="<?= $i+1?>">
                                
                                <button type="submit" name="left" class="btn btn-outline-secondary border-2">
                                        <i class="bi bi-arrow-left-short"></i>
                                </button>
                              
                                <button type="submit" name="right" class="btn btn-outline-secondary border-2">
                                    <i class="bi bi-arrow-right-short"></i>
                                </button>
                                
                                <button type="button" class="btn-delete btn btn-outline-danger bg-danger text-white border-2" > 
                                    <i class="bi bi-x"></i>
                                </button>
                            </form>
                        </div>
                    <?php endfor; ?>
                </div>
            <?php endif; ?> 
        </div>
    </div>
</div>

<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true" data-bs-theme="dark">
  <div class="modal-dialog">
    <div class="modal-content"> 
        <div class="modal-header">
            <h5 class="modal-title">Delete image</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
      <div class="modal-body">
        Are you sure you want to delete this image?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-danger" id="btnConfirmDelete">Delete</button>
      </div>
    </div>
  </div>
</div>

<?php include 'view/utilités/footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui-touch-punch/0.2.3/jquery.ui.touch-punch.min.js"></script>
<script src="js/swapImage.js"></script>

</body>
</html>