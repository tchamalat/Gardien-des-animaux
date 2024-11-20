<?php
session_start();
include 'config.php';

if (isset($_FILES['photo_profil']) && $_FILES['photo_profil']['error'] === 0) {
    $uploadDir = 'uploads/'; 
    $fileName = basename($_FILES['photo_profil']['name']);
    $uploadFile = $uploadDir . $fileName;
    
    $fileType = pathinfo($uploadFile, PATHINFO_EXTENSION);
    $allowedTypes = array('jpg', 'png', 'jpeg', 'gif');
    
    if (in_array($fileType, $allowedTypes)) {
        if (move_uploaded_file($_FILES['photo_profil']['tmp_name'], $uploadFile)) {
            $userId = $_SESSION['user_id'];
            // Ici on utilise profile_picture, qui correspond à la colonne de la base de données
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
        $_SESSION['message'] = "Type de fichier non autorisé. Veuillez choisir un fichier JPG, PNG, JPEG ou GIF.";
    }
} else {
    $_SESSION['message'] = "Aucun fichier téléchargé. Veuillez sélectionner une image.";
}

header("Location: profil_gardien.php"); 
exit();
?>
