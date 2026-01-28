<footer class="footer fixed-bottom bg-dark border-top border-secondary p-0 ">
    <div class="container">
        <div class="row text-center ">
            <div class="col">
                <a href="item/browse" class="text-decoration-none <?= ($source === 'browse') ? 'text-primary' : 'text-white' ?>">
                    <i class="bi bi-search fs-4 d-block"></i>
                    <span style="font-size: 0.65rem;" class="text-uppercase fw-bold">Browse</span>
                </a>
            </div>
            <div class="col">
                <a href="item/list" class="text-decoration-none <?= ($source === 'items') ? 'text-primary' : 'text-white' ?>">
                    <i class="bi bi-house-door fs-4 d-block"></i>
                    <span style="font-size: 0.65rem;" class="text-uppercase fw-bold">My Items</span>
                </a>
            </div>
            <div class="col">
                <a href="item/add" class="text-decoration-none text-white">
                    <i class="bi bi-plus-lg fs-4 d-block"></i>
                    <span style="font-size: 0.65rem;" class="text-uppercase fw-bold">Add Offer</span>
                </a>
            </div>
            <div class="col">
                <a href="user/profile" class="text-decoration-none text-white">
                    <i class="bi bi-gear fs-4 d-block"></i>
                    <span style="font-size: 0.65rem;" class="text-uppercase fw-bold">Profile</span>
                </a>
            </div>
        </div>
    </div>
</footer>