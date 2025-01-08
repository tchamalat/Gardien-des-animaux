<?php
include 'config.php';
session_start();

// Récupération des paramètres de recherche
$latitude_user = isset($_GET['latitude']) ? floatval($_GET['latitude']) : 0;
$longitude_user = isset($_GET['longitude']) ? floatval($_GET['longitude']) : 0;
$rayon = $_GET['rayon'] ?? 20;

$service = $_GET['service'] ?? '';
$animal = $_GET['animal'] ?? '';
$budget_min = (int)($_GET['budget_min'] ?? 0);
$budget_max = (int)($_GET['budget_max'] ?? 100);

// Préparation de la requête SQL
$sql = "
    SELECT nom_utilisateur AS nom, type_animal AS animal, nombre_animal AS nombre_animaux, ville, budget_min, budget_max, service,
    (
        6371 * ACOS(
            COS(RADIANS(?)) * COS(RADIANS(latitude)) * COS(RADIANS(longitude) - RADIANS(?)) +
            SIN(RADIANS(?)) * SIN(RADIANS(latitude))
        )
    ) AS distance
    FROM creation_compte
    WHERE role = 0
    AND (type_animal = ? OR ? = '')
    AND (service = ? OR ? = '')
    AND (budget_min >= ? AND budget_max <= ?)
    HAVING distance <= ?
    ORDER BY distance ASC
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("dddsssiiii", $latitude_user, $longitude_user, $latitude_user, $animal, $animal, $service, $service, $budget_min, $budget_max, $rayon);
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
        /* Styles globaux */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            color: #fff;
            background: url('images/premierplan.png') no-repeat center center fixed;
            background-size: cover;
            min-height: 100vh;
        }

        header {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 10;
            padding: 20px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        header img {
            height: 120px;
            max-width: 200px;
        }

        .auth-buttons {
            display: flex;
            gap: 15px;
        }

        .auth-buttons .btn {
            background-color: orange;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            font-size: 1em;
            cursor: pointer;
            text-decoration: none;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .auth-buttons .btn:hover {
            background-color: #ff7f00;
            transform: translateY(-3px);
        }

        .resultats-container {
            max-width: 900px;
            margin: 150px auto;
            background: rgba(255, 255, 255, 0.9);
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
            color: #333;
        }

        h2 {
            color: orange;
            text-align: center;
            margin-bottom: 30px;
        }

        .results-list {
            list-style: none;
            padding: 0;
        }

        .result-card {
            background: rgba(255, 255, 255, 0.85);
            border: 1px solid #ddd;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .result-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .gardien-name {
            font-size: 1.5em;
            color: #555;
        }

        .service-type {
            background: orange;
            color: white;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.9em;
        }

        .result-details p {
            margin: 10px 0;
            font-size: 1em;
        }

        .no-results {
            text-align: center;
            font-size: 1.2em;
            color: #555;
            margin-top: 30px;
        }

        ul {
            margin-top: 20px;
            padding-left: 20px;
            list-style: none;
        }

        ul li {
            position: relative;
            padding-left: 25px;
            margin-bottom: 10px;
        }

        ul li:before {
            content: '✔';
            position: absolute;
            left: 0;
            top: 0;
            color: orange;
            font-weight: bold;
        }

        footer {
            background: rgba(0, 0, 0, 0.85);
            color: #fff;
            padding: 20px;
            margin-top: 50px;
        }

        footer .footer-links {
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
        }

        footer .footer-links h4 {
            color: orange;
            margin-bottom: 10px;
        }

        footer .footer-links ul {
            list-style: none;
            padding: 0;
        }

        footer .footer-links a {
            color: white;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        footer .footer-links a:hover {
            color: orange;
        }
    </style>
</head>
<body>
    <header>
        <div class="header-container">
            <img src="images/logo.png" alt="Logo Gardien des Animaux">
            <div class="auth-buttons">
                <button class="btn" onclick="window.location.href='search_page_index.php'">Nouvelle recherche</button>
            </div>
        </div>
    </header>

    <div class="resultats-container">
        <h2>Gardiens trouvés</h2>
        <?php if (count($gardiens) > 0): ?>
            <ul class="results-list">
                <?php foreach ($gardiens as $gardien): ?>
                    <li class="result-card">
                        <div class="result-header">
                            <h3 class="gardien-name"><?php echo htmlspecialchars($gardien['nom']); ?></h3>
                            <span class="service-type"><?php echo htmlspecialchars($gardien['service']); ?></span>
                        </div>
                        <div class="result-details">
                            <p><strong>Type d'animal:</strong> <?php echo htmlspecialchars($gardien['animal']); ?></p>
                            <p><strong>Ville:</strong> <?php echo htmlspecialchars($gardien['ville']); ?></p>
                            <p><strong>Budget:</strong> <?php echo htmlspecialchars($gardien['budget_min']); ?>€ - <?php echo htmlspecialchars($gardien['budget_max']); ?>€</p>
                            <p><strong>Nombre d'animaux:</strong> <?php echo htmlspecialchars($gardien['nombre_animaux']); ?></p>
                            <p><strong>Distance:</strong> <?php echo round($gardien['distance'], 2); ?> km</p>
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
