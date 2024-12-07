<?php
session_start();
require_once 'config.php'; // Fichier de connexion à la base de données

// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Gestion de l'envoi du message
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $recipient_username = $_POST['recipient_username'];
    $message_content = $_POST['message'];

    $stmt = $conn->prepare("SELECT id FROM creation_compte WHERE nom_utilisateur = ?");
    $stmt->bind_param('s', $recipient_username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $receiver_id = $row['id'];

        $insert = $conn->prepare("INSERT INTO discussion (sender_id, receiver_id, message) VALUES (?, ?, ?)");
        $insert->bind_param('iis', $user_id, $receiver_id, $message_content);
        if ($insert->execute()) {
            $success_message = "Message envoyé avec succès.";
        } else {
            $error_message = "Erreur lors de l'envoi du message.";
        }
    } else {
        $error_message = "Destinataire introuvable.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Discussion</title>
    <link rel="stylesheet" href="styles.css"> <!-- Assurez-vous d'avoir un fichier CSS externe -->
    <style>
        .btn {
            background-color: #ff7f00;  /* Bouton orange */
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            width: 100%;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }
        
        .btn:hover {
            background-color: #e66b00;  /* Effet survol orange foncé */
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Envoyer un message</h2>

        <?php if (!empty($success_message)): ?>
            <div class="message success"><?= htmlspecialchars($success_message); ?></div>
        <?php elseif (!empty($error_message)): ?>
            <div class="message error"><?= htmlspecialchars($error_message); ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label for="recipient_username">Nom d'utilisateur du destinataire :</label>
                <input type="text" name="recipient_username" required>
            </div>

            <div class="form-group">
                <label for="message">Message :</label>
                <textarea name="message" rows="4" required></textarea>
            </div>

            <button type="submit" class="btn">Envoyer</button>
        </form>
    </div>
</body>
</html>
