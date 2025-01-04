<?php
include 'config.php';
header('Content-Type: application/json');
session_start();

$input = json_decode(file_get_contents('php://input'), true);

if (!isset($input['latitude'], $input['longitude'])) {
    echo json_encode(['error' => 'Coordonnées non fournies']);
    exit;
}

$latitude = floatval($input['latitude']);
$longitude = floatval($input['longitude']);
$radius = 10;

try {
    $stmt = $conn->prepare("
        SELECT 
            id, prenom, nom_utilisateur, profile_picture, ville, latitude, longitude,
            (6371 * ACOS(COS(RADIANS(?)) * COS(RADIANS(latitude)) * COS(RADIANS(longitude) - RADIANS(?)) + SIN(RADIANS(?)) * SIN(RADIANS(latitude)))) AS distance
        FROM creation_compte
        WHERE role = 1
        HAVING distance <= ?
        ORDER BY distance ASC
    ");
    $stmt->bind_param("dddi", $latitude, $longitude, $latitude, $radius);
    $stmt->execute();
    $result = $stmt->get_result();

    $proprietaires = [];
    while ($row = $result->fetch_assoc()) {
        $proprietaires[] = $row;
    }

    echo json_encode($proprietaires);
} catch (Exception $e) {
    echo json_encode(['error' => 'Erreur lors de la récupération des données : ' . $e->getMessage()]);
}
?>
