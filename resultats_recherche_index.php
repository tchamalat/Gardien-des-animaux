<?php
include 'config.php';
session_start();
$service = $_GET['service'] ?? '';
$animal = $_GET['animal'] ?? '';
$budget_min = (int)($_GET['budget_min'] ?? 0);
$budget_max = (int)($_GET['budget_max'] ?? 100);
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
        body {
            font-family: Arial, sans-serif;
            color: #333;
            background: url('images/premierplan.png') no-repeat center center fixed;
            background-size: cover;
            margin: 0;
            padding: 0;
            overflow-x: hidden;
        }

        header {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 10;
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: none;
        }
        header img {
            height: 150px;
            max-width: 170px;
        }

        header h1 {
            color: orange;
            font-size: 1.8em;
            margin: 0;
        }

        header .btn {
            background-color: orange;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            font-size: 1em;
            cursor: pointer;
            text-decoration: none;
            margin-left: 10px;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        header .btn:hover {
            background-color: #ff7f00;
            transform: translateY(-3px);
        }

        .resultats-container {
            max-width: 1000px;
            margin: 150px auto 50px auto;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
        }

        .resultats-container h2 {
            color: orange;
            margin-bottom: 20px;
            text-align: center;
        }

        .results-list {
            list-style: none;
            padding: 0;
        }

        .result-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 15px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        }

        .result-card .result-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
        }

        .result-card .gardien-name {
            font-size: 1.5em;
            color: #333;
            margin: 0;
        }

        .result-card .badge {
            background-color: orange;
            color: white;
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 0.9em;
        }

        .result-card .result-details p {
            margin: 8px 0;
        }

        .result-card .result-details span {
            font-weight: bold;
        }

        .result-card .result-actions .btn-hero {
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

        .result-card .result-actions .btn-hero:hover {
            background-color: #ff7f00;
            transform: translateY(-3px);
        }

        .no-results {
            text-align: center;
            color: #888;
            margin-top: 20px;
            font-size: 1.2em;
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
            position: fixed; 
            bottom: 0; 
            left: 0;
            width: 100%;
            box-shadow: 0 -2px 5px rgba(0, 0, 0, 0.2); 
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
                <button class="btn" onclick="window.location.href='index.php'">Acceuil</button>                
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
