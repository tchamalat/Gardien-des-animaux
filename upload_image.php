<?php
session_start();
include 'config.php';

// Détermine si l'utilisateur est un gardien
$is_gardien = isset($_POST['is_gardien']) && $_POST['is_gardien'] === '1';

$uploadDir = $is_gardien ? 'uploads/' : 'images/';
$fileInputName = $is_gardien ? 'photo_profil' : 'profilePicture';

if (isset($_FILES[$fileInputName]) && $_FILES[$fileInputName]['error'] === 0) {
    // Crée le dossier si nécessaire
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $fileName = uniqid() . '_' . basename($_FILES[$fileInputName]['name']);
    $uploadFile = $uploadDir . $fileName;

    $fileType = strtolower(pathinfo($uploadFile, PATHINFO_EXTENSION));
    $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];

    if (in_array($fileType, $allowedTypes)) {
        if (move_uploaded_file($_FILES[$fileInputName]['tmp_name'], $uploadFile)) {
            $userId = $_SESSION['user_id'];
            $query = "UPDATE creation_compte SET profile_picture = ? WHERE id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("si", $fileName, $userId);

            if ($stmt->execute()) {
                $_SESSION['message'] = "La photo de profil a été mise à jour avec succès.";
            } else {
                $_SESSION['message'] = "Erreur lors de la mise à jour de la base de données.";
            }
            $stmt->close();
        } else {
            $_SESSION['message'] = "Erreur lors de l'enregistrement de l'image.";
        }
    } else {
        $_SESSION['message'] = "Type de fichier non autorisé.";
    }
} else {
    $_SESSION['message'] = "Aucun fichier téléchargé ou erreur lors de l'upload.";
}

header("Location: " . ($is_gardien ? "profil_gardien.php" : "profil.php"));
exit();
?>
