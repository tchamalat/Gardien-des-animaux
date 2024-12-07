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
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['recipient_username']) && isset($_POST['message'])) {
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

// Gestion de la suppression des messages
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_message_id'])) {
    $message_id = $_POST['delete_message_id'];
    
    // Vérifie si l'utilisateur est le sender ou le receiver du message
    $check = $conn->prepare("SELECT * FROM discussion WHERE id = ? AND (sender_id = ? OR receiver_id = ?)");
    $check->bind_param('iii', $message_id, $user_id, $user_id);
    $check->execute();
    $result_check = $check->get_result();

    if ($result_check->num_rows > 0) {
        $delete = $conn->prepare("DELETE FROM discussion WHERE id = ?");
        $delete->bind_param('i', $message_id);
        if ($delete->execute()) {
            $success_message = "Message supprimé avec succès.";
        } else {
            $error_message = "Erreur lors de la suppression du message.";
        }
    } else {
        $error_message = "Vous n'avez pas la permission de supprimer ce message.";
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
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 90%;
            max-width: 600px;
            margin: 40px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            color: #333;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
            color: #555;
        }
        input[type="text"], textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
        }
        textarea {
            resize: vertical;
        }
        .message {
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 15px;
        }
        .success {
            background-color: #d4edda;
            color: #155724;
        }
        .error {
            background-color: #f8d7da;
            color: #721c24;
        }
        .messages-list p {
            background-color: #f1f1f1;
            padding: 10px;
            border-left: 4px solid #f5a623;
            margin-bottom: 10px;
            border-radius: 4px;
            font-size: 14px;
        }
        .messages-list em {
            color: #888;
            font-size: 12px;
        }
        .btn {
            padding: 10px 15px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .btn-danger {
            background-color: #DC3545;
        }
        .btn:hover {
            background-color: #0056b3;
        }
        .btn-danger:hover {
            background-color: #c82333;
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

        <h2>Messages échangés</h2>
        <div class="messages-list">
            <?php
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
                    echo "<p>
                            <strong>" . htmlspecialchars($msg['sender_name']) . "</strong> à 
                            <strong>" . htmlspecialchars($msg['receiver_name']) . "</strong> : 
                            " . htmlspecialchars($msg['message']) . " 
                            <em>(" . $msg['timestamp'] . ")</em>
                            <form method='POST' style='display:inline;'>
                                <input type='hidden' name='delete_message_id' value='" . $msg['id'] . "'>
                                <button type='submit' class='btn btn-danger' onclick='return confirm(\"Voulez-vous vraiment supprimer ce message ?\");'>Supprimer</button>
                            </form>
                          </p>";
                }
            } else {
                echo "<p>Aucun message trouvé.</p>";
            }
            ?>
        </div>
    </div>
</body>
</html>
