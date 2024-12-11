<?php
session_start();
require_once 'config.php'; // Fichier de connexion à la base de données

// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Récupère l'ID de l'utilisateur connecté
$user_id = $_SESSION['user_id'];

// Gestion de l'envoi du message
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $recipient_username = $_POST['recipient_username'];
    $message_content = $_POST['message'];

    // Vérifie si le destinataire existe
    $stmt = $conn->prepare("SELECT id FROM creation_compte WHERE nom_utilisateur = ?");
    $stmt->bind_param('s', $recipient_username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $receiver_id = $row['id'];

        // Insère le message dans la table discussion
        $insert = $conn->prepare("INSERT INTO discussion (sender_id, receiver_id, message) VALUES (?, ?, ?)");
        $insert->bind_param('iis', $user_id, $receiver_id, $message_content);
        if ($insert->execute()) {
            echo "<p style='color: green;'>Message envoyé avec succès.</p>";
        } else {
            echo "<p style='color: red;'>Erreur lors de l'envoi du message.</p>";
        }
    } else {
        echo "<p style='color: red;'>Destinataire introuvable.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Discussion</title>
</head>
<body>
    <h2>Envoyer un message</h2>
    <form method="POST">
        <label for="recipient_username">Nom d'utilisateur du destinataire :</label><br>
        <input type="text" name="recipient_username" required><br><br>

        <label for="message">Message :</label><br>
        <textarea name="message" rows="4" cols="50" required></textarea><br><br>

        <input type="submit" value="Envoyer">
    </form>

    <h2>Messages échangés</h2>
    <?php
    // Récupère les messages où l'utilisateur est soit l'expéditeur, soit le destinataire
    $messages = $conn->prepare("
        SELECT d.*, c1.nom_utilisateur AS sender_name, c2.nom_utilisateur AS receiver_name 
        FROM discussion d
        JOIN creation_compte c1 ON d.sender_id = c1.id
        JOIN creation_compte c2 ON d.receiver_id = c2.id
        WHERE d.sender_id = ? OR d.receiver_id = ?
        ORDER BY d.timestamp DESC
    ");
    $messages->bind_param('ii', $user_id, $user_id);
    $messages->execute();
    $result = $messages->get_result();

    if ($result->num_rows > 0) {
        while ($msg = $result->fetch_assoc()) {
            echo "<p><strong>{$msg['sender_name']}</strong> à <strong>{$msg['receiver_name']}</strong> : {$msg['message']} <em>({$msg['timestamp']})</em></p>";
        }
    } else {
        echo "<p>Aucun message trouvé.</p>";
    }
    ?>
</body>
</html>
