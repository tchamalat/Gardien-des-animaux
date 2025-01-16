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
        body {
            font-family: Arial, sans-serif;
            color: #fff;
            background: url('images/premierplan.png') no-repeat center center fixed;
            background-size: cover;
            min-height: 100vh;
            overflow-x: hidden;
            display: flex;
            flex-direction: column;
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
            background: none;
        }

        header img {
            height: 120px;
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
            margin: 100px auto;
            max-width: 800px; /* Augmentation de la largeur maximale */
            background: rgba(255, 255, 255, 0.95);
            padding: 30px;
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
        .messages-list {
            margin-top: 20px;
            max-height: 400px; 
            overflow-y: auto; 
        }

        .messages-list p {
            background-color: #f1f1f1;
            color: black;
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

        ul {
            margin-top: 20px;
            padding-left: 20px;
            list-style: none;
        }

        ul li {
            position: relative;
            padding-left: 25px;
            margin-bottom: 10px;
        }

        ul li:before {
            content: '✔';
            position: absolute;
            left: 0;
            top: 0;
            color: orange;
            font-weight: bold;
        }

        footer {
            background: rgba(0, 0, 0, 0.85);
            color: #fff;
            padding: 20px;
            position: fixed; /* Fixe le footer */
            bottom: 0; /* Place le footer au bas de la page */
            left: 0;
            width: 100%; /* Prend toute la largeur de la page */
            box-shadow: 0 -2px 5px rgba(0, 0, 0, 0.2); /* Ajoute une ombre légère */
        }

        footer .footer-links {
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
        }

        footer .footer-links h4 {
            color: orange;
            margin-bottom: 10px;
        }

        footer .footer-links ul {
            list-style: none;
            padding: 0;
        }

        footer .footer-links a {
            color: white;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        footer .footer-links a:hover {
            color: orange;
        }

        .message-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
            background: #f9f9f9;
            border-radius: 10px;
            padding: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }        
        .message-item .message-details {
            flex-grow: 1;
            margin-right: 10px;
        }

        .message-item .message-details strong {
            color: orange;
            display: block;
            margin-bottom: 5px;
        }

        .message-item .message-details em {
            font-size: 0.9em;
            color: #666;
        }

        .message-item .message-delete {
            color: red;
            font-size: 1.2em;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .message-item .message-delete:hover {
            color: darkred;
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
    </style>
</head>
<body>
    <!-- Header -->
    <header>
        <img src="images/logo.png" alt="Logo">
        <div class="auth-buttons">
            <button class="btn" onclick="window.location.href='index_connect_gardien.php'">Accueil</button>
            <button class="btn" onclick="window.location.href='profil_gardien.php'">Mon Profil</button>
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
                    echo "<div class='message-item'>
                            <div class='message-details'>
                                <strong>" . htmlspecialchars($msg['sender_name']) . "</strong> à 
                                <strong>" . htmlspecialchars($msg['receiver_name']) . "</strong> : 
                                <span>" . htmlspecialchars($msg['message']) . "</span>
                                <em>(" . $msg['timestamp'] . ")</em>
                            </div>
                            <a href='?delete_id=" . $msg['id'] . "' class='message-delete' title='Supprimer'>&#10060;</a>
                          </div>";
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
                    <li><a href="securite_connect_gardien.php">Sécurité</a></li>
                    <li><a href="aide_connect_gardien.php">Centre d'aide</a></li>
                </ul>
            </div>
            <div>
                <h4>A propos de nous :</h4>
                <ul>
                    <li><a href="confidentialite_connect_gardien.php">Politique de confidentialité</a></li>
                    <li><a href="contact_connect_gardien.php">Nous contacter</a></li>
                </ul>
            </div>
            <div>
                <h4>Conditions Générales :</h4>
                <ul>
                    <li><a href="conditions_connect_gardien.php">Conditions d'utilisateur et de Service</a></li>
                </ul>
            </div>
        </div>
    </footer>
</body>
</html>
