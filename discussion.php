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
            $feedback = "<div class='alert success'>Message envoyé avec succès.</div>";
        } else {
            $feedback = "<div class='alert error'>Erreur lors de l'envoi du message.</div>";
        }
    } else {
        $feedback = "<div class='alert error'>Destinataire introuvable.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Discussion</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1 class="page-title">Messagerie</h1>
        <div class="form-container">
            <h2>Envoyer un message</h2>
            <?php if (isset($feedback)) echo $feedback; ?>
            <form method="POST">
                <div class="form-group">
                    <label for="recipient_username">Nom d'utilisateur du destinataire :</label>
                    <input type="text" name="recipient_username" id="recipient_username" required>
                </div>

                <div class="form-group">
                    <label for="message">Message :</label>
                    <textarea name="message" id="message" rows="4" required></textarea>
                </div>

                <button type="submit" class="btn">Envoyer</button>
            </form>
        </div>

        <div class="messages-container">
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
                    echo "<div class='message'>
                            <p><strong>{$msg['sender_name']}</strong> à <strong>{$msg['receiver_name']}</strong> :</p>
                            <p>{$msg['message']}</p>
                            <p class='timestamp'>{$msg['timestamp']}</p>
                          </div>";
                }
            } else {
                echo "<p class='no-messages'>Aucun message trouvé.</p>";
            }
            ?>
        </div>
    </div>
</body>
</html>
