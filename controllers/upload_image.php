<?php
session_start();
include 'config.php';

if (isset($_FILES['profilePicture']) && $_FILES['profilePicture']['error'] === 0) {
    // Define the upload directory
    $uploadDir = __DIR__ . '/images/';

    // Create the directory if it doesn't exist
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    // Generate a unique file name to avoid conflicts
    $fileName = uniqid() . "_" . basename($_FILES['profilePicture']['name']);
    $uploadFile = $uploadDir . $fileName;

    // Validate the file type
    $fileType = pathinfo($uploadFile, PATHINFO_EXTENSION);
    $allowedTypes = array('jpg', 'png', 'jpeg', 'gif');

    if (in_array(strtolower($fileType), $allowedTypes)) {
        // Check if the temporary file exists
        if (!file_exists($_FILES['profilePicture']['tmp_name'])) {
            $_SESSION['message'] = "Le fichier temporaire n'existe pas.";
            header("Location: /views/profil.php");
            exit();
        }

        // Move the uploaded file
        if (move_uploaded_file($_FILES['profilePicture']['tmp_name'], $uploadFile)) {
            // Save the file path relative to the web directory
            $relativePath = 'images/' . $fileName;

            // Update the database
            $userId = $_SESSION['user_id'];
            $query = "UPDATE creation_compte SET profile_picture = ? WHERE id = ?";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("si", $relativePath, $userId);

            if ($stmt->execute()) {
                $_SESSION['message'] = "La photo de profil a été mise à jour avec succès.";
            } else {
                $_SESSION['message'] = "Erreur lors de la mise à jour de la base de données.";
            }
        } else {
            $_SESSION['message'] = "Erreur lors du téléversement du fichier. Vérifiez les permissions du dossier.";
        }
    } else {
        $_SESSION['message'] = "Type de fichier non autorisé. Veuillez téléverser une image (jpg, jpeg, png, gif).";
    }
} else {
    $_SESSION['message'] = "Aucun fichier sélectionné ou erreur lors du téléversement : " . $_FILES['profilePicture']['error'];
}

header("Location: /views/profil.php");
exit();
?>
