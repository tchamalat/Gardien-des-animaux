<?php
session_start();
require_once 'config.php'; // Fichier de connexion à la base de données

// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Gestion de la suppression du message
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);

    $delete = $conn->prepare("DELETE FROM discussion WHERE id = ? AND (sender_id = ? OR receiver_id = ?)");
    $delete->bind_param('iii', $delete_id, $user_id, $user_id);

    if ($delete->execute()) {
        $success_message = "Message supprimé avec succès.";
    } else {
        $error_message = "Erreur lors de la suppression du message.";
    }
}

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
    <style>
        .messages-list {
            margin-top: 20px;
        }

        .message-item {
            background: rgba(255, 255, 255, 0.9);
            border: 1px solid #ddd;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .message-details {
            max-width: 80%;
        }

        .message-details strong {
            color: orange;
            display: block;
            margin-bottom: 5px;
        }

        .message-details em {
            font-size: 0.9em;
            color: #666;
        }

        .message-delete {
            color: red;
            font-size: 1.2em;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .message-delete:hover {
            color: darkred;
        }
        body {
            font-family: Arial, sans-serif;
            color: #fff;
            background: url('images/premierplan.png') no-repeat center center fixed;
            background-size: cover;
            min-height: 100vh;
            overflow-x: hidden;
        }

        header {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 10;
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        header img {
            height: 100px;
        }

        .auth-buttons .btn {
            background-color: orange;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1em;
            margin-left: 10px;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .auth-buttons .btn:hover {
            background-color: #ff7f00;
            transform: translateY(-3px);
        }

        .container {
            margin: 150px auto;
            max-width: 600px;
            background: rgba(255, 255, 255, 0.85);
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
        }

        h2 {
            color: orange;
            margin-bottom: 20px;
            text-align: center;
        }

        .form-group {
            margin-bottom: 20px;
            text-align: left;
        }

        label {
            font-weight: bold;
            color: #333;
            display: block;
            margin-bottom: 5px;
        }

        input, textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 1em;
        }

        textarea {
            resize: none;
        }

        .envoyer {
            display: block;
            width: fit-content;
            margin: 0 auto;
            background-color: #f5a623;
            color: white;
            padding: 15px 30px; 
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1.2em;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .envoyer:hover {
            background-color: #ff7f00;
            transform: translateY(-3px);
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
            font-size: 12px;
            color: #888;
        }

        footer {
            padding: 20px;
            background: rgba(0, 0, 0, 0.8);
            color: #fff;
            margin-top: 30px;
        }

        .footer-links {
            display: flex;
            justify-content: space-around;
        }

        .footer-links ul {
            list-style: none;
        }

        .footer-links a {
            color: #fff;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .footer-links a:hover {
            color: orange;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header>
        <img src="images/logo.png" alt="Logo">
        <div class="auth-buttons">
            <button class="btn" onclick="window.location.href='index_connect.php'">Accueil</button>
            <button class="btn" onclick="window.location.href='profil.php'">Mon Profil</button>
            <button class="btn" onclick="window.location.href='search_page.php'">Je poste une annonce</button>
        </div>
    </header>

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

            <button type="submit" class="envoyer">Envoyer</button>
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
                            <a href='?delete_id=" . $msg['id'] . "' style='color: red; float: right;' title='Supprimer'>&#10060;</a>
                          </p>";
                }
            } else {
                echo "<p>Aucun message trouvé.</p>";
            }
            ?>
        </div>
    </div>

    <footer>
        <div class="footer-links">
            <div>
                <h4>En savoir plus :</h4>
                <ul>
                    <li><a href="securite_connect.php">Sécurité</a></li>
                    <li><a href="aide_connect.php">Centre d'aide</a></li>
                </ul>
            </div>
            <div>
                <h4>A propos de nous :</h4>
                <ul>
                    <li><a href="confidentialite_connect.php">Politique de confidentialité</a></li>
                    <li><a href="contact_connect.php">Nous contacter</a></li>
                </ul>
            </div>
            <div>
                <h4>Conditions Générales :</h4>
                <ul>
                    <li><a href="conditions_connect.php">Conditions d'utilisateur et de Service</a></li>
                </ul>
            </div>
        </div>
    </footer>
</body>
</html>
