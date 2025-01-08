<?php
include 'config.php';

header('Content-Type: application/json');

// Requête SQL pour récupérer tous les gardiens
$query = $conn->prepare("
    SELECT 
        id, prenom, nom_utilisateur, profile_picture
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
    $gardiens[] = [
        'id' => $row['id'],
        'prenom' => $row['prenom'],
        'nom_utilisateur' => $row['nom_utilisateur'],
        'profile_picture' => $row['profile_picture'] ? "display_image.php?id={$row['id']}" : 'images/default_profile.png',
    ];
}

echo json_encode($gardiens);
$conn->close();
?>

