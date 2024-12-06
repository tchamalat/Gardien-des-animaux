<?php
include 'config.php';
session_start();


// Récupération des paramètres de recherche
$latitude_user = $_GET['latitude'] ?? 0;
$longitude_user = $_GET['longitude'] ?? 0;
$rayon = $_GET['rayon'] ?? 20;

$service = $_GET['service'] ?? '';
$animal = $_GET['animal'] ?? '';
$budget_min = (int)($_GET['budget_min'] ?? 0);
$budget_max = (int)($_GET['budget_max'] ?? 100);

// Préparation de la requête SQL
$sql = "
    SELECT id, nom_utilisateur AS nom, type_animal AS animal, nombre_animal AS nombre_animaux, ville, budget_min, budget_max, service,
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
    <link rel="stylesheet" href="/CSS/styles.css">
</head>
<body>
<header>
    <h1>Résultats de la recherche</h1>
    <button class="btn" onclick="window.location.href='/views/search_page.php'">Nouvelle recherche</button>
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
                        <a href="/controllers/reservation.php?gardien_id=<?php echo htmlspecialchars($gardien['id']); ?>" class="btn btn-hero">
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
                <li><a href="/views/securite.php">Sécurité</a></li>
                <li><a href="/views/aide.php">Centre d'aide</a></li>
            </ul>
        </div>
        <div>
            <h4>A propos de nous :</h4>
            <ul>
                <li><a href="/views/confidentialite.php">Politique de confidentialité</a></li>
                <li><a href="/views/contact.php">Nous contacter</a></li>
            </ul>
        </div>
        <div>
            <h4>Conditions Générales :</h4>
            <ul>
                <li><a href="/views/conditions.php">Conditions d'utilisateur et de Service</a></li>
            </ul>
        </div>
    </div>
</footer>

</body>
</html>
