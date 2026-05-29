<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Categories</title>
    <base href="<?= $web_root ?>" >
    <link href="css/browse-item.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        .js-only { display: none !important; }
        .js-active .js-only { display: flex !important; }
        .js-active div.js-only.add-container { display: block !important; }
        .js-active .no-js { display: none !important; }
        .cursor-grab { cursor: grab; }
        .cursor-grab:active { cursor: grabbing; }
        .drag-over-top { border-top: solid 2px #0d6efd !important; }
        .drag-over-bottom { border-bottom: solid 2px #0d6efd !important; }
    </style>
</head>

<body>
<header class="bg-dark border-bottom border-secondary sticky-top">
    <div class="container d-flex justify-content-center align-items-center py-2">
        <h5 class="m-0 me-1 text-primary">Categories</h5>
        <i class="bi bi-cart4 fs-4 text-white"></i>
    </div>
</header>

<div class="container py-4">
    <?php 
        $minChar = Configuration::get('min_char_category');
        $maxChar = Configuration::get('max_char_category');
    ?>
    <div style="max-width: 800px; margin: auto;" id="categories-wrapper" data-min="<?= $minChar ?>" data-max="<?= $maxChar ?>">

        <div id="categories-container">
            <?php foreach ($allCategories as $cat) :?>
                
                <div class="mb-3 no-js"> 
                    <form method="POST" action="category/manage/<?= $cat->getId() ?>" 
                          class="category-row d-flex align-items-center p-2 border border-secondary rounded bg-dark">
                        <input type="text" name="catName"
                            class="form-control border-0 bg-transparent text-white flex-grow-1"
                            value="<?= htmlspecialchars($values[$cat->getId()] ?? $cat->getName()) ?>">
                        <div class="btn-group-custom d-flex flex-shrink-0 ms-2">
                            <button type="submit" name="up" class="btn btn-outline-secondary btn-sm me-1" <?= $cat->isFirst() ? 'disabled' : '' ?>>Up</button>
                            <button type="submit" name="down" class="btn btn-outline-secondary btn-sm me-1" <?= $cat->isLast() ? 'disabled' : '' ?>>Down</button>
                            <button type="submit" name="save" class="btn btn-outline-success btn-sm me-1">Save</button>
                            <a href="category/delete/<?= $cat->getId() ?>" 
                               class="btn btn-outline-danger btn-sm <?= $cat->isNotUsed() ? '' : 'disabled' ?>">Delete</a>
                        </div>
                    </form>
                    <?php if (!empty($errors[$cat->getId()])): ?>
                        <div class="ms-2 mt-1">
                            <?php foreach ($errors[$cat->getId()] as $e): ?>
                                <small class="text-danger d-block"><?= $e ?></small>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="category-item js-only d-flex align-items-center p-2 border border-secondary rounded bg-dark mb-3" 
                     data-id="<?= $cat->getId() ?>" draggable="true">
                    <div class="drag-handle text-secondary me-3 cursor-grab" aria-label="Drag to reorder"><i class="bi bi-list fs-4"></i></div>
                    <div class="cat-name flex-grow-1 text-white">
                        <span class="name-display"><?= htmlspecialchars($cat->getName()) ?></span>
                        <input type="text" class="form-control name-input bg-dark text-white border-secondary d-none" value="<?= htmlspecialchars($cat->getName()) ?>">
                        <small class="text-danger error-msg d-block"></small>
                    </div>
                    <div class="cat-usage text-secondary ms-2 me-3" style="font-size: 0.9em;">
                        <?= $cat->getNbUse() ?> utilisation(s)
                    </div>
                    <div class="cat-actions ms-2 flex-shrink-0">
                        <button type="button" class="btn btn-outline-primary btn-sm btn-edit"><i class="bi bi-pencil"></i></button>
                        <button type="button" class="btn btn-outline-danger btn-sm btn-delete" <?= $cat->isNotUsed() ? '' : 'disabled' ?>><i class="bi bi-trash"></i></button>
                    </div>
                </div>
            <?php endforeach;?>
        </div>

        <hr class="border-secondary my-4 no-js" style="border-style: dashed;">

        <div class="no-js">
            <form method="POST" action="category/manage" 
                class="category-row d-flex align-items-center p-2 border border-secondary rounded bg-dark">
                <input type="text" name="catName" class="form-control border-0 bg-transparent text-white flex-grow-1"
                    placeholder="Category name" value="<?= htmlspecialchars($values['new'] ?? 'Category name') ?>"> 
                <button type="submit" name="add" class="btn btn-primary ms-2"><i class="bi bi-plus-lg"></i></button>
            </form>
            <?php if (!empty($errors['new'])): ?>
                <div class="ms-2 mt-1">
                    <?php foreach ($errors['new'] as $e): ?>
                        <small class="text-danger d-block"><?= $e ?></small>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="js-only add-container text-center mb-4 mt-4">
            <button id="btn-add-init" class="btn btn-primary rounded-circle shadow" style="width: 50px; height: 50px;"><i class="bi bi-plus-lg fs-4"></i></button>
        </div>
        <div id="add-input-container" class="js-only d-none mb-3 p-3 border border-primary rounded bg-dark" style="display: block !important;">
            <input type="text" id="new-cat-name" class="form-control bg-dark text-white border-secondary" placeholder="Nom de la nouvelle catégorie">
            <small id="add-error" class="text-danger d-block mt-1"></small>
            <div class="mt-3 text-end">
                <button id="btn-cancel-add" class="btn btn-sm btn-secondary">Cancel</button>
            </div>
        </div>

    </div>
</div>

<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content bg-dark text-white border-secondary">
      <div class="modal-header border-secondary">
        <h5 class="modal-title">Confirmer la suppression</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body border-secondary">Êtes-vous sûr de vouloir supprimer cette catégorie ?</div>
      <div class="modal-footer border-secondary">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
        <button type="button" id="confirmDeleteBtn" class="btn btn-danger">Supprimer</button>
      </div>
    </div>
  </div>
</div>

<?php include 'view/utilités/footer.php'; ?>
<script src="js/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="js/manage-categories.js"></script>
</body>
</html>