<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (isset($_POST['animal_id'], $_FILES['animalPhoto']) && $_FILES['animalPhoto']['error'] === 0) {
    $animal_id = intval($_POST['animal_id']);
    $fileType = strtolower(pathinfo($_FILES['animalPhoto']['name'], PATHINFO_EXTENSION));
    $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];

    if (in_array($fileType, $allowedTypes)) {
        $fileContent = file_get_contents($_FILES['animalPhoto']['tmp_name']);

        $sql = "UPDATE animaux SET photo_animal = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        if ($stmt) {
            $null = NULL;
            $stmt->bind_param("bi", $null, $animal_id);
            $stmt->send_long_data(0, $fileContent);

            if ($stmt->execute()) {
                $_SESSION['message'] = "Photo de l'animal mise à jour avec succès.";
            } else {
                $_SESSION['message'] = "Erreur lors de la mise à jour : " . $stmt->error;
            }
            $stmt->close();
        } else {
            $_SESSION['message'] = "Erreur de préparation de la requête : " . $conn->error;
        }
    } else {
        $_SESSION['message'] = "Type de fichier non autorisé.";
    }
} else {
    $_SESSION['message'] = "Erreur lors du téléchargement.";
}

header("Location: profil_public.php");
exit();
?>
