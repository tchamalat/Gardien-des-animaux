<?php
include 'config.php';

header('Content-Type: application/json');

// Vérifier que les données JSON POST sont envoyées
$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['latitude']) || !isset($data['longitude'])) {
    echo json_encode(['error' => 'Données de localisation manquantes.']);
    exit;
}

$userLatitude = $data['latitude'];
$userLongitude = $data['longitude'];

// Fonction pour calculer la distance en km entre deux points géographiques
function calculateDistance($lat1, $lon1, $lat2, $lon2) {
    $earthRadius = 6371; // Rayon moyen de la Terre en km

    $latDelta = deg2rad($lat2 - $lat1);
    $lonDelta = deg2rad($lon2 - $lon1);

    $a = sin($latDelta / 2) * sin($latDelta / 2) +
         cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
         sin($lonDelta / 2) * sin($lonDelta / 2);

    $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

    return $earthRadius * $c;
}

// Requête SQL pour récupérer les gardiens avec leurs coordonnées
$query = $conn->prepare("
    SELECT 
        id, prenom, nom_utilisateur, profile_picture, latitude, longitude
    FROM creation_compte
    WHERE role = 0
    ORDER BY prenom ASC
");

if (!$query) {
    echo json_encode(['error' => 'Erreur de préparation de la requête : ' . $conn->error]);
    exit;
}

$query->execute();
$result = $query->get_result();

$gardiens = [];
while ($row = $result->fetch_assoc()) {
    // Vérifier que les coordonnées du gardien sont disponibles
    if (!empty($row['latitude']) && !empty($row['longitude'])) {
        $distance = calculateDistance(
            $userLatitude,
            $userLongitude,
            $row['latitude'],
            $row['longitude']
        );

        $distance = round($distance, 1); // Arrondir la distance à une décimale
    } else {
        $distance = 'Indisponible'; // Distance non calculable
    }

    $gardiens[] = [
        'id' => $row['id'],
        'prenom' => $row['prenom'],
        'nom_utilisateur' => $row['nom_utilisateur'],
        'profile_picture' => $row['profile_picture'] ? "display_image.php?id={$row['id']}" : 'images/default_profile.png',
        'distance' => $distance
    ];
}

echo json_encode($gardiens);
$conn->close();
?>
