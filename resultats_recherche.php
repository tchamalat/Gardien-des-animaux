<?php
include 'config.php';
session_start();

// Check if user_id exists in the session
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    // Handle the case where user_id is not set in the session
    die("Error: User not logged in.");
}

// Get current user city with a safe check for no results
$user_city_query = "SELECT ville FROM creation_compte WHERE id = ?";
$user_city_stmt = $conn->prepare($user_city_query);
$user_city_stmt->bind_param("i", $user_id);
$user_city_stmt->execute();
$user_city_result = $user_city_stmt->get_result();
$user_city_row = $user_city_result->fetch_assoc();

if ($user_city_row) {
    $user_city = $user_city_row['ville'];
} else {
    die("Error: User city not found.");
}
$user_city_stmt->close();

$service = $_GET['service'] ?? '';
$animal = $_GET['animal'] ?? '';
$nombre = $_GET['nombre'] ?? '';
$ville = $_GET['ville'] ?? '';
$budget_min = (int)($_GET['budget_min'] ?? 0);
$budget_max = (int)($_GET['budget_max'] ?? 100);

// Prepare the query with error handling for missing fields
$sql = "SELECT nom_utilisateur AS nom, type_animal AS animal, nombre_animal AS nombre_animaux, ville, budget_min, budget_max, service 
        FROM creation_compte 
        WHERE role = 0 
        AND ville = ? 
        AND (type_animal = ? OR ? = '') 
        AND (service = ? OR ? = '') 
        AND (budget_min >= ? AND budget_max <= ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssiii", $ville, $animal, $animal, $service, $service, $budget_min, $budget_max);
$stmt->execute();

$result = $stmt->get_result();

$gardiens = []; // Initialisation de $gardiens en tant que tableau vide

while ($row = $result->fetch_assoc()) {
    $gardiens[] = $row; // Ajoute chaque ligne de résultat dans le tableau $gardiens
}


// Fetch results and handle potential empty result set
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // Process each row as needed
        echo $row['nom'];
        // Other data processing
    }
} else {
    echo "Aucun gardien trouvé.";
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
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Enhanced styling for result cards */
        .results-list {
            list-style-type: none;
            padding: 0;
        }

        .result-card {
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            margin: 10px 0;
            padding: 20px;
            background-color: #ffffff;
            transition: box-shadow 0.3s;
        }

        .result-card:hover {
            box-shadow: 0px 8px 16px rgba(0, 0, 0, 0.2);
        }

        .result-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .gardien-name {
            font-size: 1.3em;
            font-weight: bold;
            color: #2c3e50;
        }

        .service-type.badge {
            background-color: #3498db;
            color: #fff;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 0.9em;
        }

        .result-details p {
            margin: 8px 0;
            font-size: 1em;
            color: #555;
        }

        .icon {
            margin-right: 5px;
            color: #e67e22;
        }

        .no-results {
            color: #e74c3c;
            font-size: 1.1em;
            text-align: center;
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
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p class="no-results">Aucun gardien trouvé pour ces critères de recherche.</p>
        <?php endif; ?>
    </div>
</body>
</html>
