<?php
session_start();
include 'config.php';

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if (isset($_FILES['profilePicture']) && $_FILES['profilePicture']['error'] === 0) {
    $fileType = strtolower(pathinfo($_FILES['profilePicture']['name'], PATHINFO_EXTENSION));
    $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];

    // Vérifier le type de fichier
    if (in_array($fileType, $allowedTypes)) {
        // Lire le contenu du fichier
        $fileContent = file_get_contents($_FILES['profilePicture']['tmp_name']);
        
        // Préparer la requête pour insérer l'image dans la base de données
        $sql = "UPDATE creation_compte SET profile_picture = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("bi", $fileContent, $user_id);

        if ($stmt->execute()) {
            $_SESSION['message'] = "La photo de profil a été mise à jour avec succès.";
        } else {
            $_SESSION['message'] = "Erreur lors de la mise à jour de la base de données.";
        }
        $stmt->close();
    } else {
        $_SESSION['message'] = "Type de fichier non autorisé.";
    }
} else {
    $_SESSION['message'] = "Aucun fichier téléchargé ou erreur lors de l'upload.";
}

// Redirection après l'upload
header("Location: profil.php");
exit();
?>
