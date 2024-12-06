<?php
session_start();
include 'config.php';

// Détermine si l'utilisateur est un gardien
$is_gardien = isset($_POST['is_gardien']) && $_POST['is_gardien'] === '1';

$fileInputName = $is_gardien ? 'photo_profil' : 'profilePicture';

if (isset($_FILES[$fileInputName]) && $_FILES[$fileInputName]['error'] === 0) {
    $fileType = strtolower(pathinfo($_FILES[$fileInputName]['name'], PATHINFO_EXTENSION));
    $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];

    if (in_array($fileType, $allowedTypes)) {
        // Lire le contenu du fichier
        $fileContent = file_get_contents($_FILES[$fileInputName]['tmp_name']);

        // Échapper les données binaires pour les insérer dans la base
        $fileContentEscaped = addslashes($fileContent);

        $userId = $_SESSION['user_id'];

        // Mettre à jour la base de données avec les données binaires
        $query = "UPDATE creation_compte SET profile_picture = ? WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("bi", $fileContent, $userId);

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

header("Location: " . ($is_gardien ? "profil_gardien.php" : "profil.php"));
exit();
?>
