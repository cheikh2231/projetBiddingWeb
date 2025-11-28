<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Browse items</title>
    <base href="<?= $web_root ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="css/styles.css" rel="stylesheet" type="text/css"/>
</head>

<body>
    <div>
        <h3>Browse</h3>
        <div class="cards-container" >
        <?php foreach ($items as $item) : ?>
        <div class="card">
            <h2><?= $item->title ?></h2>
            <p><?= $item->description ?></p>

            <div class="card-labels">
                <span class="label auction">Enchère</span>
                <span class="label direct">Vente directe</span>
            </div> <!-- fermeture ici -->
        </div>


            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>
