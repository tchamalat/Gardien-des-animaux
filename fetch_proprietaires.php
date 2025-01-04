<?php
session_start();
include 'config.php'; // Connexion à la base de données

header('Content-Type: application/json');

// Récupère les données envoyées par fetchProprietaires()
$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['latitude'], $data['longitude'])) {
    echo json_encode(['error' => 'Coordonnées non fournies.']);
    exit;
}

$latitude = $data['latitude'];
$longitude = $data['longitude'];

// Requête SQL pour récupérer les propriétaires disponibles
$sql = "
    SELECT id, nom_utilisateur, prenom, ville, profile_picture,
    (6371 * acos(
        cos(radians(?)) * cos(radians(latitude)) *
        cos(radians(longitude) - radians(?)) +
        sin(radians(?)) * sin(radians(latitude))
    )) AS distance
    FROM creation_compte
    WHERE role = 1 /* Propriétaires */
    HAVING distance < 50 /* Propriétaires dans un rayon de 50 km */
    ORDER BY distance ASC
";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    echo json_encode(['error' => 'Erreur lors de la préparation de la requête : ' . $conn->error]);
    exit;
}

$stmt->bind_param('ddd', $latitude, $longitude, $latitude);

if (!$stmt->execute()) {
    echo json_encode(['error' => 'Erreur lors de l\'exécution de la requête : ' . $stmt->error]);
    exit;
}

$result = $stmt->get_result();

$proprietaires = [];
while ($row = $result->fetch_assoc()) {
    $imageData = !empty($row['profile_picture']) ? base64_encode($row['profile_picture']) : null;
    $proprietaires[] = [
        'id' => $row['id'],
        'nom_utilisateur' => $row['nom_utilisateur'],
        'prenom' => $row['prenom'],
        'ville' => $row['ville'],
        'distance' => $row['distance'],
        'profile_picture' => $imageData, // Inclure les données de l'image encodée en base64
    ];
}

$stmt->close();
$conn->close();

echo json_encode($proprietaires);
