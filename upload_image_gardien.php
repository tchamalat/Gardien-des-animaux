<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (isset($_FILES['photo_profil']) && $_FILES['photo_profil']['error'] === 0) {
    $fileType = strtolower(pathinfo($_FILES['photo_profil']['name'], PATHINFO_EXTENSION));
    $allowedTypes = array('jpg', 'png', 'jpeg', 'gif');

    // Vérification du type de fichier
    if (in_array($fileType, $allowedTypes)) {
        // Lire le contenu de l'image
        $fileContent = file_get_contents($_FILES['photo_profil']['tmp_name']);

        $userId = $_SESSION['user_id'];

        // Préparer la requête pour mettre à jour les données binaires
        $query = "UPDATE creation_compte SET profile_picture = ? WHERE id = ?";
        $stmt = $conn->prepare($query);
        if ($stmt) {
            $null = NULL;
            $stmt->bind_param("bi", $null, $userId);
            $stmt->send_long_data(0, $fileContent); // Envoi des données binaires
            if ($stmt->execute()) {
                $_SESSION['message'] = "La photo de profil a été mise à jour avec succès.";
            } else {
                $_SESSION['message'] = "Erreur lors de la mise à jour de la base de données : " . $stmt->error;
            }
            $stmt->close();
        } else {
            $_SESSION['message'] = "Erreur de préparation de la requête : " . $conn->error;
        }
    } else {
        $_SESSION['message'] = "Type de fichier non autorisé. Veuillez choisir un fichier JPG, PNG, JPEG ou GIF.";
    }
} else {
    $_SESSION['message'] = "Aucun fichier téléchargé ou erreur lors de l'upload.";
}

// Redirection après traitement
header("Location: profil_gardien.php");
exit();
?>
