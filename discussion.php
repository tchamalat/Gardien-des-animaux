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
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <!-- Header -->
    <header>
        <div class="header-container">
            <img src="images/logo.png" alt="Logo Gardien des Animaux">
            <div class="auth-buttons">
                <?php
                if (isset($_SESSION['role'])) {
                    if ($_SESSION['role'] == 0) {
                        echo '<button class="btn" onclick="window.location.href=\'profil_gardien.php\'">Mon Profil</button>';
                    } elseif ($_SESSION['role'] == 1) {
                        echo '<button class="btn" onclick="window.location.href=\'profil.php\'">Mon Profil</button>';
                    }
                } else {
                    echo '<button class="btn" onclick="window.location.href=\'login.php\'">Mon Profil</button>';
                }
                ?>
                <button class="btn" onclick="window.location.href='search_page.php'">Je poste une annonce</button>
            </div>
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

    <!-- Footer -->
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
                <h4>À propos de nous :</h4>
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
