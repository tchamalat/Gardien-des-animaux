<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("HTTP/1.1 403 Forbidden");
    exit();
}

$user_id = $_SESSION['user_id'];

// Récupérer les données de l'image depuis la base de données
$sql = "SELECT profile_picture FROM creation_compte WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($profile_picture);
$stmt->fetch();
$stmt->close();
$conn->close();

// Vérifier si une image existe
if ($profile_picture) {
    header("Content-Type: image/jpeg"); // Adapter le type MIME si nécessaire
    echo $profile_picture;
} else {
    // Si aucune image n'est définie, afficher une image par défaut
    header("Content-Type: image/png");
    readfile("images/profile-placeholder.png"); // Remplacez par une image par défaut
}
?>
