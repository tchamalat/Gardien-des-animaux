<?php
include 'config.php';

// Vérifiez si un ID utilisateur est passé
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Récupérez le chemin ou les données de l'image depuis la base de données
    $stmt = $conn->prepare("SELECT profile_picture FROM creation_compte WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($profilePicture);
    $stmt->fetch();
    $stmt->close();

    if ($profilePicture) {
        // Si `profile_picture` est un chemin de fichier
        if (file_exists($profilePicture)) {
            header("Content-Type: " . mime_content_type($profilePicture));
            readfile($profilePicture);
        } else {
            // Image par défaut si le fichier n'existe pas
            header("Content-Type: image/jpeg");
            readfile("images/default.jpg");
        }
    } else {
        // Affichez une image par défaut si aucune photo n'est trouvée
        header("Content-Type: image/jpeg");
        readfile("images/default.jpg");
    }
} else {
    header("HTTP/1.1 400 Bad Request");
    echo "ID utilisateur manquant.";
}

$conn->close();
?>
