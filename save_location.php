<?php
include 'config.php'; // Connexion à la base de données

// Récupérer les données JSON envoyées
$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['latitude'], $data['longitude'], $data['user_id'])) {
    $latitude = $data['latitude'];
    $longitude = $data['longitude'];
    $user_id = $data['user_id'];

    // Mise à jour des coordonnées de l'utilisateur dans la base de données
    $query = "UPDATE creation_compte SET latitude = ?, longitude = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ddi", $latitude, $longitude, $user_id);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'Position enregistrée avec succès.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Erreur lors de l\'enregistrement des coordonnées.']);
    }

    $stmt->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Données invalides.']);
}
?>
