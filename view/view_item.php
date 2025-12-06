<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <title>Browse items</title>
    <base href="<?= $web_root ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
      rel="stylesheet"
    />
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
    />
    <link href="css/styles.css" rel="stylesheet" type="text/css" />
  </head>

  <body class="bg-white text-black">
    <header
      class="bg-dark p-0 d-flex justify-content-center align-items-center border-bottom border-secondary mb-4 sticky-top"
    >
      <h5 class="m-0 me-2 text-primary">Browse</h5>
      <i class="bi bi-cart fs-4 text-grey"></i>
    </header>

    <div class="container mb-5">
      <?php if (!empty($itemsUser)) :?>
      <h4 class="text-primary mb-4">Items I'm Participating In</h4>
      <div class="row g-4">
        <?php foreach ($itemsUser as $item) : ?>
        <?php $pictures = $item->getPictures(); ?>

        <div class="col-12 col-sm-6 col-md-4 col-lg-3 d-flex">
          <?php $username = Item::itemUserName($item->owner) ?>
          <a
            href="test.php"
            class="card card-custom position-relative w-100 h-100 text-decoration-none text-dark"
            title="Open <?= $username ?>'s Item"
          >
            <div
              class="position-absolute top-0 end-0 p-2 d-flex flex-row-reverse"
              style="z-index: 10"
            >
              <?php if ($item->has_buy_now) : ?>
              <span
                class="rounded-pill bg-info text-white py-0 px-1 d-flex align-items-center me-1 fw-bold"
                style="font-size: 0.9rem"
              >
                <i class="bi bi-bag me-2"></i>
                Buy Now
              </span>
              <?php endif; ?>

              <?php if ($item->is_auction) : ?>
              <span
                class="rounded-pill bg-warning text-dark fs-6 py-1 px-2 d-flex align-items-center"
              >
                Auction
              </span>
              <?php endif; ?>
            </div>

            <div
              class="position-relative card-img-top card-img-custom d-flex align-items-center justify-content-center bg-light text-muted"
            >
              <?php if (!empty($pictures)) : ?>
              <div class="position-relative">
                <img
                  src="<?= $web_root . $pictures[0] ?>"
                  class="card-img-top card-img-custom"
                  alt="<?= htmlspecialchars($item->title) ?>"
                />

                <?php if (count($pictures) > 1) : ?>
                <span
                  class="badge bg-secondary position-absolute bottom-0 end-0 m-2"
                >
                  <?= count($pictures) ?> images
                </span>
                <?php endif; ?>
              </div>
              <?php endif; ?>
            </div>

            <div class="card-body d-flex flex-column">
              <h5 class="card-title fw-bold fs-6">
                <?= htmlspecialchars($item->title) ?>
              </h5>

              <p class="text-muted mb-2 mt-auto">
                <small>by <?= Item::itemUserName($item->owner) ?></small>
              </p>

              <div class="d-flex justify-content-between my-2">
                <div class="d-flex flex-column">
                  <span class="text-warning fw-bold">
                    € <?= number_format($item->has_buy_now ? $item->buy_now_price : $item->starting_bid ?? 0, 2) ?>
                  </span>
                </div>

                <?php if ($item->is_auction):?>
                <div class="d-flex flex-column align-items-end">
                  <span class="text-muted fw-normal" style="font-size: 0.85rem">Current Bid</span>
                  <span class="text-success fw-bold">
                    € <?= number_format($item->has_bids ? $item->max_bid : $item->starting_bid ?? 0, 2) ?>
                  </span>
                </div>
                <?php endif;?>
              </div>

              <?php $current = (new DateTime(AppTime::get_current_datetime())); $dateInter =$current->diff(Item::end_at($item->id)) ?>
              <p class="text-info m-0">
                <?= $dateInter->days . "d " . $dateInter->h . "h left" ?>
              </p>
            </div>
          </a>
        </div>
        <?php endforeach; ?>
      </div>

      <div class="my-5"></div>
      <?php endif; ?>

      <div class="row g-4">
        <?php if (!empty($items)) : ?>
        <h4 class="text-primary mb-1">Other Available Items</h4>
        <?php foreach ($items as $item) : ?>
        <?php $pictures = $item->getPictures(); ?>

        <div class="col-12 col-sm-6 col-md-4 col-lg-3 d-flex">
          <?php $username = Item::itemUserName($item->owner) ?>
          <a
            href="test.php"
            class="card card-custom position-relative w-100 h-100 text-decoration-none text-dark"
            title="Open <?= $username ?>'s Item"
          >
            <div
              class="position-absolute top-0 end-0 p-2 d-flex flex-row-reverse"
              style="z-index: 10"
            >
              <?php if ($item->has_buy_now) : ?>
              <span class="rounded-pill bg-primary text-white fs-6 py-1 px-4"
                >Buy Now</span
              >
              <?php endif; ?>

              <?php if ($item->is_auction) : ?>
              <?php $is_buy_now_active = $item->has_buy_now; $margin_style = $is_buy_now_active ? 'style="margin-left: -15px;"' : ''; ?>
              <span
                class="rounded-pill bg-warning text-dark fs-6 py-1 px-4"
                <?= $margin_style ?>
                >Auction</span
              >
              <?php endif; ?>
            </div>

            <div
              class="position-relative card-img-top card-img-custom d-flex align-items-center justify-content-center bg-light text-muted"
            >
              <?php if (!empty($pictures)) : ?>
              <div class="position-relative">
                <img
                  src="<?= $web_root . $pictures[0] ?>"
                  class="card-img-top card-img-custom"
                  alt="<?= htmlspecialchars($item->title) ?>"
                />

                <?php if (count($pictures) > 1) : ?>
                <span
                  class="badge bg-secondary position-absolute bottom-0 end-0 m-2"
                >
                  <?= count($pictures) ?> images
                </span>
                <?php endif; ?>
              </div>
              <?php endif; ?>
            </div>

            <div class="card-body d-flex flex-column">
              <h5 class="card-title fw-bold fs-6">
                <?= htmlspecialchars($item->title) ?>
              </h5>

              <p class="text-muted mb-2 mt-auto">
                <small>by <?= Item::itemUserName($item->owner) ?></small>
              </p>

              <div class="d-flex justify-content-between my-2">
                <div class="d-flex flex-column">
                  <span class="text-warning fw-bold">
                    € <?= number_format($item->has_buy_now ? $item->buy_now_price : $item->starting_bid ?? 0, 2) ?>
                  </span>
                </div>

                <?php if ($item->is_auction): ?>
                <div class="d-flex flex-column align-items-end">
                  <span class="text-muted fw-normal" style="font-size: 0.85rem">Current Bid</span>
                  <span class="text-success fw-bold">
                    € <?= number_format($item->has_bids ? $item->max_bid : $item->starting_bid ?? 0, 2) ?>
                  </span>
                </div>
                <?php endif; ?>
              </div>

              <?php $current = (new DateTime(AppTime::get_current_datetime())); $dateInter = $current->diff(Item::end_at($item->id)); ?>
              <p class="text-info m-0">
                <?= $dateInter->days . "d " . $dateInter->h . "h left" ?>
              </p>
            </div>
          </a>
        </div>
        <?php endforeach; ?>
        <?php endif; ?>
      </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>