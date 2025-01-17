<?php
// Inclure la connexion à la base de données
require_once("classes/autoload.php");
$DB = new Database();

// Vérifiez si le msgid est passé dans l'URL
if (isset($_GET['msgid'])) {
    $msgid = $_GET['msgid'];

    // Récupérez l'image BLOB à partir de la base de données
    $sql = "SELECT files FROM messages WHERE msgid = :msgid LIMIT 1";
    $result = $DB->read($sql, ['msgid' => $msgid]);

    // Si l'image existe dans la base de données
    if ($result && isset($result[0]->files)) {
        // Définir le type MIME de l'image (par exemple, image/jpeg ou image/png)
        header("Content-Type: image/jpeg");  // Vous pouvez modifier selon le type de l'image (jpeg, png, etc.)
        echo $result[0]->files;  // Affiche l'image à partir du BLOB
    } else {
        // Si l'image n'est pas trouvée, afficher un message d'erreur
        echo "Image non trouvée.";
    }
} else {
    echo "Paramètre 'msgid' manquant.";
}
?>
