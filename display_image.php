<?php
include 'config.php';

// Vérifiez si un ID utilisateur est passé
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Récupérez l'image depuis la base de données
    $stmt = $conn->prepare("SELECT profile_picture FROM creation_compte WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($imageData);
    $stmt->fetch();
    $stmt->close();

    if ($imageData) {
        // Envoyez les en-têtes nécessaires pour afficher l'image
        header("Content-Type: image/jpeg");
        echo $imageData;
    } else {
        // Affichez une image par défaut si aucune photo n'est trouvée
        header("Content-Type: image/jpeg");
        readfile("images/default.jpg");
    }
}
$conn->close();
?>
