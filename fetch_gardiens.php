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

display_image.php

<?php
include 'config.php';

// Vérifiez si un ID utilisateur est passé dans l'URL
if (isset($_GET['id'])) {
    $id = intval($_GET['id']); // Sécurisation du paramètre ID

    // Récupérez l'image depuis la base de données
    $stmt = $conn->prepare("SELECT profile_picture FROM creation_compte WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($profile_picture);
    $stmt->fetch();
    $stmt->close();
    $conn->close();

    // Vérifiez si l'image existe et affichez-la
    if ($profile_picture) {
        header("Content-Type: image/jpeg"); // Adapter le type MIME si nécessaire
        echo $profile_picture;
    } else {
        // Si aucune image n'est définie, afficher une image par défaut
        header("Content-Type: image/png");
        readfile("images/default_profile.png"); // Remplacez par une image par défaut
    }
} else {
    header("HTTP/1.1 400 Bad Request");
    echo "ID utilisateur manquant.";
}
