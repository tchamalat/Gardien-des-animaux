<?php
include 'config.php';

// Récupérer les données JSON envoyées
$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['latitude'], $data['longitude'])) {
    $latitude = $data['latitude'];
    $longitude = $data['longitude'];
    $radius = 10; // Rayon en kilomètres

    // Requête SQL pour trouver les gardiens dans le rayon
    $query = $conn->prepare("
        SELECT 
            id, prenom, nom_utilisateur, profile_picture, latitude, longitude,
            (6371 * ACOS(COS(RADIANS(?)) * COS(RADIANS(latitude)) * COS(RADIANS(longitude) - RADIANS(?)) + SIN(RADIANS(?)) * SIN(RADIANS(latitude)))) AS distance
        FROM creation_compte
        WHERE role = 0
        HAVING distance <= ?
        ORDER BY distance ASC
    ");
    $query->bind_param("dddi", $latitude, $longitude, $latitude, (double)$radius);
    $query->execute();
    $result = $query->get_result();

    $gardiens = [];
    while ($row = $result->fetch_assoc()) {
        $gardiens[] = [
            'id' => $row['id'],
            'prenom' => $row['prenom'],
            'nom_utilisateur' => $row['nom_utilisateur'],
            'profile_picture' => $row['profile_picture'],
            'distance' => $row['distance'],
        ];
    }

    echo json_encode($gardiens);
} else {
    echo json_encode(['error' => 'Données invalides.']);
}
?>
