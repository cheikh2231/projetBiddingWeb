
<footer class="footer fixed-bottom  border-top border-secondary p-2 mb-4 " style = "background: linear-gradient(180deg, #0b1324, #0e1a33);">
    <div class="container">
        <div class="row text-center ">
            <div class="col">
                <a href="item/browse" class="text-decoration-none <?= ($place === 'browse') ? 'text-primary' : 'text-white' ?>">
                    <i class="bi bi-search fs-4 d-block"></i>
                    <span style="font-size: 0.65rem;" class="text-uppercase fw-bold">Browse</span>
                </a>
            </div>
            <?php if(isset($_SESSION['user'])):?>
            <div class="col">
                <a href="item/list" class="text-decoration-none <?= ($place === 'items') ? 'text-primary' : 'text-white' ?>">
                    <i class="bi bi-house-door fs-4 d-block"></i>
                    <span style="font-size: 0.65rem;" class="text-uppercase fw-bold">My Items</span>
                </a>
            </div>
            <div class="col">
                <a href="item/add" class="text-decoration-none <?= ($place === 'Add') ? 'text-primary' : 'text-white' ?>">
                    <i class="bi bi-plus-lg fs-4 d-block"></i>
                    <span style="font-size: 0.65rem;" class="text-uppercase fw-bold">Add Offer</span>
                </a>
            </div>
            <div class="col">
                <a href="user/profile" class="text-decoration-none  <?= ($place === 'profile') ? 'text-primary' : 'text-white' ?>">
                    <i class="bi bi-gear fs-4 d-block"></i>
                    <span style="font-size: 0.65rem;" class="text-uppercase fw-bold">Profile</span>
                </a>
            </div>

            <?php else:?>
                <div class="col">
                    <a href="user/signup" class="text-decoration-none text-white">
                        <i class="bi bi-balloon-fill fs-4 d-block"></i>
                        <span style="font-size: 0.65rem;" class="text-uppercase fw-bold">Join us</span>
                    </a>
                </div>
                <?php endif;?>
          
            
        </div>
    
    </div>
</footer>
  <?php include("view/view_time.php") ?>