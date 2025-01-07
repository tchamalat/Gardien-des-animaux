<?php
include 'config.php';

// Vérifiez si un ID utilisateur est passé dans l'URL
if (!isset($_GET['id'])) {
    header("HTTP/1.1 400 Bad Request");
    exit("ID de propriétaire manquant.");
}

$proprietaire_id = intval($_GET['id']);

// Récupérer les données de l'image depuis la base de données pour les propriétaires (role = 1)
$sql = "SELECT profile_picture FROM creation_compte WHERE id = ? AND role = 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $proprietaire_id);
$stmt->execute();
$stmt->bind_result($profile_picture);
$stmt->fetch();
$stmt->close();

// Vérifier si une image existe
if ($profile_picture) {
    header("Content-Type: image/jpeg"); // Adapter le type MIME si nécessaire
    echo $profile_picture;
} else {
    // Si aucune image n'est définie, afficher une image par défaut
    header("Content-Type: image/png");
    readfile("images/default_owner.png"); // Remplacez par l'image par défaut pour les propriétaires
}

$conn->close();
?>
