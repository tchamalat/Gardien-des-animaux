<?php
include 'config.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['latitude'], $data['longitude'])) {
    $latitude = $data['latitude'];
    $longitude = $data['longitude'];

    $query = $conn->prepare("
        SELECT 
            id, nom_utilisateur, prenom, ville, profile_picture, latitude, longitude,
            (6371 * ACOS(COS(RADIANS(?)) * COS(RADIANS(latitude)) * COS(RADIANS(longitude) - RADIANS(?)) + SIN(RADIANS(?)) * SIN(RADIANS(latitude)))) AS distance
        FROM creation_compte
        WHERE role = 1 AND latitude IS NOT NULL AND longitude IS NOT NULL
        ORDER BY distance ASC
    ");

    if (!$query) {
        echo json_encode(['error' => 'Erreur de préparation de la requête : ' . $conn->error]);
        exit;
    }

    $query->bind_param("ddd", $latitude, $longitude, $latitude);

    if (!$query->execute()) {
        echo json_encode(['error' => 'Erreur d\'exécution de la requête : ' . $query->error]);
        exit;
    }

    $result = $query->get_result();
    $proprietaires = [];

    while ($row = $result->fetch_assoc()) {
        $proprietaires[] = [
            'id' => $row['id'],
            'nom_utilisateur' => $row['nom_utilisateur'],
            'prenom' => $row['prenom'],
            'ville' => $row['ville'],
            'profile_picture' => "display_image.php?id={$row['id']}",
            'distance' => round($row['distance'], 2), 
        ];
    }

    if (empty($proprietaires)) {
        echo json_encode(['error' => 'Aucun propriétaire trouvé.']);
    } else {
        echo json_encode($proprietaires);
    }
} else {
    echo json_encode(['error' => 'Données invalides. Latitude et longitude manquantes.']);
}

$conn->close();
?>
