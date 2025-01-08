<?php
include 'config.php';
session_start();

// Récupération des paramètres de recherche
$service = $_GET['service'] ?? '';
$animal = $_GET['animal'] ?? '';
$budget_min = (int)($_GET['budget_min'] ?? 0);
$budget_max = (int)($_GET['budget_max'] ?? 100);

// Préparation de la requête SQL
$sql = "
    SELECT 
        id, nom_utilisateur AS nom, type_animal AS animal, nombre_animal AS nombre_animaux, ville, budget_min, budget_max, service
    FROM 
        creation_compte
    WHERE 
        role = 0
        AND (type_animal = ? OR ? = '')
        AND (service = ? OR ? = '')
        AND (budget_min >= ? AND budget_max <= ?)
    ORDER BY 
        budget_min ASC
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("sssiii", $animal, $animal, $service, $service, $budget_min, $budget_max);
$stmt->execute();

$result = $stmt->get_result();

$gardiens = [];
while ($row = $result->fetch_assoc()) {
    $gardiens[] = $row;
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang='fr'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Résultats de la recherche</title>
    <style>
        /* Ajoutez ici vos styles CSS */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: #f4f4f4;
            color: #333;
        }

        header {
            background: #333;
            color: #fff;
            padding: 15px 20px;
            text-align: center;
        }

        .resultats-container {
            padding: 20px;
            max-width: 1000px;
            margin: 20px auto;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: orange;
            text-align: center;
        }

        .results-list {
            list-style: none;
            padding: 0;
        }

        .result-card {
            margin: 15px 0;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            background: #f9f9f9;
        }

        .result-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .result-header h3 {
            margin: 0;
            font-size: 1.2em;
        }

        .badge {
            background: orange;
            color: #fff;
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 0.9em;
        }

        .result-details {
            margin: 10px 0;
        }

        .result-details p {
            margin: 5px 0;
        }

        .result-actions {
            text-align: right;
        }

        .btn-hero {
            background: orange;
            color: #fff;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 8px;
            transition: background 0.3s ease;
        }

        .btn-hero:hover {
            background: #ff7f00;
        }

        .no-results {
            text-align: center;
            color: #888;
        }
    </style>
</head>
<body>
<header>
    <h1>Résultats de la recherche</h1>
    <button class="btn" onclick="window.location.href='search_page.php'">Nouvelle recherche</button>
</header>

<div class="resultats-container">
    <h2>Gardiens trouvés</h2>
    <?php if (count($gardiens) > 0): ?>
        <ul class="results-list">
            <?php foreach ($gardiens as $gardien): ?>
                <li class="result-card">
                    <div class="result-header">
                        <h3 class="gardien-name"><?php echo htmlspecialchars($gardien['nom']); ?></h3>
                        <span class="service-type badge"><?php echo htmlspecialchars($gardien['service']); ?></span>
                    </div>
                    <div class="result-details">
                        <p><span class="icon">🐾</span><strong>Type d'animal:</strong> <?php echo htmlspecialchars($gardien['animal']); ?></p>
                        <p><span class="icon">📍</span><strong>Ville:</strong> <?php echo htmlspecialchars($gardien['ville']); ?></p>
                        <p><span class="icon">💰</span><strong>Budget:</strong> <?php echo htmlspecialchars($gardien['budget_min']); ?>€ - <?php echo htmlspecialchars($gardien['budget_max']); ?>€</p>
                        <p><span class="icon">🐕</span><strong>Nombre d'animaux:</strong> <?php echo htmlspecialchars($gardien['nombre_animaux']); ?></p>
                        <p><span class="icon">🌍</span><strong>Distance:</strong> <?php echo round($gardien['distance'], 2); ?> km</p>
                    </div>
                    <div class="result-actions">
                        <a href="reservation.php?gardien_id=<?php echo htmlspecialchars($gardien['id']); ?>" class="btn btn-hero">
                            Réserver ce gardien
                        </a>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p class="no-results">Aucun gardien trouvé pour ces critères de recherche.</p>
    <?php endif; ?>
</div>

<footer>
    <div class="footer-links">
        <div>
            <h4>En savoir plus :</h4>
            <ul>
                <li><a href="securite.php">Sécurité</a></li>
                <li><a href="aide.php">Centre d'aide</a></li>
            </ul>
        </div>
        <div>
            <h4>A propos de nous :</h4>
            <ul>
                <li><a href="confidentialite.php">Politique de confidentialité</a></li>
                <li><a href="contact.php">Nous contacter</a></li>
            </ul>
        </div>
        <div>
            <h4>Conditions Générales :</h4>
            <ul>
                <li><a href="conditions.php">Conditions d'utilisateur et de Service</a></li>
            </ul>
        </div>
    </div>
</footer>
</body>
</html>
