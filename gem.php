<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historique des Enchères</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #0d1117; /* Fond très sombre type GitHub/Dashboard */
            color: white;
            padding-top: 50px;
        }

        .bid-card {
            background-color: #161b22; /* Couleur de la carte */
            border-radius: 8px;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #30363d;
        }

        .bid-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .bid-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #8b949e;
        }

        .entries-badge {
            background-color: #f0f6fc;
            color: #0d1117;
            border-radius: 20px;
            padding: 2px 12px;
            font-size: 0.85rem;
            font-weight: bold;
        }

        .bid-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 0;
            border-bottom: 1px solid #30363d;
        }

        .bid-row:last-child {
            border-bottom: none;
        }

        .bidder-info .name {
            display: block;
            font-weight: bold;
            font-size: 1.1rem;
        }

        .bidder-info .date {
            display: block;
            color: #8b949e;
            font-size: 0.9rem;
            margin-top: 4px;
        }

        .bid-amount {
            color: #3fb950; /* Vert émeraude spécifique aux prix */
            font-weight: bold;
            font-size: 1.2rem;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="bid-card shadow-lg">
        <div class="bid-header">
            <span class="bid-title">Bid History</span>
            <span class="entries-badge">3 entries</span>
        </div>

        <div class="bid-row">
            <div class="bidder-info">
                <span class="name">Xavier</span>
                <span class="date">16/11/2025 03:27:14</span>
            </div>
            <div class="bid-amount">€ 162,00</div>
        </div>

        <div class="bid-row">
            <div class="bidder-info">
                <span class="name">Quentin</span>
                <span class="date">15/11/2025 01:30:10</span>
            </div>
            <div class="bid-amount">€ 152,00</div>
        </div>

        <div class="bid-row">
            <div class="bidder-info">
                <span class="name">Quentin</span>
                <span class="date">14/11/2025 05:01:54</span>
            </div>
            <div class="bid-amount">€ 140,00</div>
        </div>
    </div>
</div>

</body>
</html>