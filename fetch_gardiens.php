<?php
include 'config.php';

$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['latitude'], $data['longitude'])) {
    $latitude = $data['latitude'];
    $longitude = $data['longitude'];
    $radius = $data['radius'] ?? 10; // Rayon en kilomètres, valeur par défaut : 10

    // Préparer la requête SQL
    $query = $conn->prepare("
        SELECT 
            id, nom_utilisateur, profile_picture, latitude, longitude,
            (6371 * ACOS(COS(RADIANS(?)) * COS(RADIANS(latitude)) * COS(RADIANS(longitude) - RADIANS(?)) + SIN(RADIANS(?)) * SIN(RADIANS(latitude)))) AS distance
        FROM creation_compte
        WHERE role = 0 AND latitude IS NOT NULL AND longitude IS NOT NULL
        HAVING distance <= ?
        ORDER BY distance ASC
    ");

    if (!$query) {
        echo json_encode(['error' => 'Erreur de préparation de la requête : ' . $conn->error]);
        exit;
    }

    $query->bind_param("dddi", $latitude, $longitude, $latitude, $radius);

    if (!$query->execute()) {
        echo json_encode(['error' => 'Erreur d\'exécution de la requête : ' . $query->error]);
        exit;
    }

    $result = $query->get_result();
    $gardiens = [];

    while ($row = $result->fetch_assoc()) {
        $gardiens[] = [
            'id' => $row['id'],
            'nom_utilisateur' => $row['nom_utilisateur'],
            'profile_picture' => $row['profile_picture'] ? "display_image.php?id={$row['id']}" : 'images/default.jpg',
            'distance' => round($row['distance'], 2), // Distance arrondie à 2 décimales
        ];
    }

    if (empty($gardiens)) {
        echo json_encode(['error' => 'Aucun gardien trouvé dans ce rayon.']);
    } else {
        echo json_encode($gardiens);
    }
} else {
    echo json_encode(['error' => 'Données invalides. Latitude et longitude manquantes.']);
}

$conn->close();
