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
<html>
<?php  
include 'config.php'; // Connexion à la base de données
session_start();

// Gestion des requêtes AJAX pour la discussion et les gardiens
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);

    // Récupérer les messages pour le chat
    if (isset($input['action']) && $input['action'] === 'get_messages') {
        $sender_id = $_SESSION['user_id']; // L'utilisateur connecté
        $receiver_id = $input['receiver_id'];

        $stmt = $conn->prepare("
            SELECT * FROM discussion 
            WHERE (sender_id = ? AND receiver_id = ?) OR (sender_id = ? AND receiver_id = ?) 
            ORDER BY timestamp ASC
        ");
        $stmt->bind_param("iiii", $sender_id, $receiver_id, $receiver_id, $sender_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $messages = [];

        while ($row = $result->fetch_assoc()) {
            $messages[] = $row;
        }
        echo json_encode($messages);
        exit;
    }

    // Envoyer un message pour le chat
    if (isset($input['action']) && $input['action'] === 'send_message') {
        $sender_id = $_SESSION['user_id']; // L'utilisateur connecté
        $receiver_id = $input['receiver_id'];
        $message = $input['message'];

        $stmt = $conn->prepare("INSERT INTO discussion (sender_id, receiver_id, message) VALUES (?, ?, ?)");
        $stmt->bind_param("iis", $sender_id, $receiver_id, $message);
        $stmt->execute();

        echo json_encode(['status' => 'success']);
        exit;
    }

    // Garder la logique pour récupérer les gardiens
    if (isset($input['latitude']) && isset($input['longitude']) && isset($_SESSION['role']) && $_SESSION['role'] == 1) { 
        $user_latitude = floatval($input['latitude']);
        $user_longitude = floatval($input['longitude']);
        $radius = 10;

        $gardiens_query = $conn->prepare("
            SELECT 
                id, prenom, nom_utilisateur, profile_picture, latitude, longitude,
                (6371 * ACOS(COS(RADIANS(?)) * COS(RADIANS(latitude)) * COS(RADIANS(longitude) - RADIANS(?)) + SIN(RADIANS(?)) * SIN(RADIANS(latitude)))) AS distance
            FROM creation_compte
            WHERE role = 0
            HAVING distance <= ?
            ORDER BY distance ASC
        ");
        $gardiens_query->bind_param("dddi", $user_latitude, $user_longitude, $user_latitude, $radius);
        $gardiens_query->execute();
        $gardiens_result = $gardiens_query->get_result();

        while ($gardien = $gardiens_result->fetch_assoc()) {
            echo '<div class="gardien">';
            echo '<img src="images/' . htmlspecialchars($gardien['profile_picture']) . '" alt="' . htmlspecialchars($gardien['prenom']) . '">';
            echo '<p><strong>' . htmlspecialchars($gardien['prenom']) . '</strong> (' . htmlspecialchars($gardien['nom_utilisateur']) . ')</p>';
            echo '<p class="distance">Distance : ' . round($gardien['distance'], 2) . ' km</p>';
            echo '</div>';
        }
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gardien des Animaux - Connecté</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Styles pour la fenêtre de discussion */
        #chatButton {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background-color: #f5a623;
            color: white;
            border: none;
            border-radius: 50%;
            width: 60px;
            height: 60px;
            font-size: 24px;
            cursor: pointer;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        #chatWindow {
            display: none;
            position: fixed;
            bottom: 90px;
            right: 20px;
            width: 300px;
            height: 400px;
            background-color: white;
            border: 1px solid #ccc;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            display: flex;
            flex-direction: column;
        }

        #chatHeader {
            background-color: #f5a623;
            color: white;
            padding: 10px;
            text-align: center;
        }

        #chatMessages {
            flex: 1;
            padding: 10px;
            overflow-y: auto;
            background-color: #f9f9f9;
        }

        #chatInput {
            display: flex;
            border-top: 1px solid #ccc;
        }

        #chatInput input {
            flex: 1;
            padding: 10px;
            border: none;
            outline: none;
        }

        #chatInput button {
            padding: 10px;
            background-color: #f5a623;
            color: white;
            border: none;
            cursor: pointer;
        }
    </style>
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
<?php  
include 'config.php'; // Connexion à la base de données
session_start();

// Gestion des requêtes AJAX pour la discussion et les gardiens
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);

    // Récupérer les messages pour le chat
    if (isset($input['action']) && $input['action'] === 'get_messages') {
        $sender_id = $_SESSION['user_id']; // L'utilisateur connecté
        $receiver_id = $input['receiver_id'];

        $stmt = $conn->prepare("
            SELECT * FROM discussion 
            WHERE (sender_id = ? AND receiver_id = ?) OR (sender_id = ? AND receiver_id = ?) 
            ORDER BY timestamp ASC
        ");
        $stmt->bind_param("iiii", $sender_id, $receiver_id, $receiver_id, $sender_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $messages = [];

        while ($row = $result->fetch_assoc()) {
            $messages[] = $row;
        }
        echo json_encode($messages);
        exit;
    }

    // Envoyer un message pour le chat
    if (isset($input['action']) && $input['action'] === 'send_message') {
        $sender_id = $_SESSION['user_id']; // L'utilisateur connecté
        $receiver_id = $input['receiver_id'];
        $message = $input['message'];

        $stmt = $conn->prepare("INSERT INTO discussion (sender_id, receiver_id, message) VALUES (?, ?, ?)");
        $stmt->bind_param("iis", $sender_id, $receiver_id, $message);
        $stmt->execute();

        echo json_encode(['status' => 'success']);
        exit;
    }

    // Garder la logique pour récupérer les gardiens
    if (isset($input['latitude']) && isset($input['longitude']) && isset($_SESSION['role']) && $_SESSION['role'] == 1) { 
        $user_latitude = floatval($input['latitude']);
        $user_longitude = floatval($input['longitude']);
        $radius = 10;

        $gardiens_query = $conn->prepare("
            SELECT 
                id, prenom, nom_utilisateur, profile_picture, latitude, longitude,
                (6371 * ACOS(COS(RADIANS(?)) * COS(RADIANS(latitude)) * COS(RADIANS(longitude) - RADIANS(?)) + SIN(RADIANS(?)) * SIN(RADIANS(latitude)))) AS distance
            FROM creation_compte
            WHERE role = 0
            HAVING distance <= ?
            ORDER BY distance ASC
        ");
        $gardiens_query->bind_param("dddi", $user_latitude, $user_longitude, $user_latitude, $radius);
        $gardiens_query->execute();
        $gardiens_result = $gardiens_query->get_result();

        while ($gardien = $gardiens_result->fetch_assoc()) {
            echo '<div class="gardien">';
            echo '<img src="images/' . htmlspecialchars($gardien['profile_picture']) . '" alt="' . htmlspecialchars($gardien['prenom']) . '">';
            echo '<p><strong>' . htmlspecialchars($gardien['prenom']) . '</strong> (' . htmlspecialchars($gardien['nom_utilisateur']) . ')</p>';
            echo '<p class="distance">Distance : ' . round($gardien['distance'], 2) . ' km</p>';
            echo '</div>';
        }
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gardien des Animaux - Connecté</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Styles pour la fenêtre de discussion */
        #chatButton {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background-color: #f5a623;
            color: white;
            border: none;
            border-radius: 50%;
            width: 60px;
            height: 60px;
            font-size: 24px;
            cursor: pointer;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        #chatWindow {
            display: none;
            position: fixed;
            bottom: 90px;
            right: 20px;
            width: 300px;
            height: 400px;
            background-color: white;
            border: 1px solid #ccc;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            display: flex;
            flex-direction: column;
        }

        #chatHeader {
            background-color: #f5a623;
            color: white;
            padding: 10px;
            text-align: center;
        }

        #chatMessages {
            flex: 1;
            padding: 10px;
            overflow-y: auto;
            background-color: #f9f9f9;
        }

        #chatInput {
            display: flex;
            border-top: 1px solid #ccc;
        }

        #chatInput input {
            flex: 1;
            padding: 10px;
            border: none;
            outline: none;
        }

        #chatInput button {
            padding: 10px;
            background-color: #f5a623;
            color: white;
            border: none;
            cursor: pointer;
        }
    </style>
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

    <!-- Hero Section -->
    <section class="hero">
        <img src="images/premierplan.png" alt="Un foyer chaleureux">
        <div class="hero-text">
            <button class="btn btn-hero" onclick="window.location.href='search_page.php'">Trouver un gardien</button>
            <button class="btn" onclick="window.location.href='discussion.php'">Discussion</button>
        </div>
    </section>

    <!-- Section Gardien -->
    <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 1): ?>
    <section class="gardiens">
        <h2>Gardiens près de chez vous :</h2>
        <div class="gardien-list">
            <p>Chargement des gardiens en fonction de votre position...</p>
        </div>
    </section>
    <?php endif; ?>

    <!-- Avis Section -->
    <section class="avis-section">
        <h3>Avis</h3>
        <div class="avis-list">
            <?php
            $query = "SELECT avis.review, avis.rating, avis.date_created, creation_compte.nom_utilisateur 
                      FROM avis 
                      JOIN creation_compte ON avis.user_id = creation_compte.id 
                      ORDER BY avis.date_created DESC LIMIT 3";
            $result = $conn->query($query);

            while ($row = $result->fetch_assoc()) {
                echo "<div class='avis'>";
                echo "<p>" . htmlspecialchars($row['nom_utilisateur']) . " :</p>";
                echo "<p>" . htmlspecialchars($row['review']) . "</p>";
                echo "<span>" . htmlspecialchars($row['rating']) . " / 5 <img src='images/star.png' alt='étoile'></span>";
                echo "</div>";
            }
            ?>
        </div>
        <button class="voir-plus" onclick="window.location.href='leave_review.php'">Laisser un avis</button>
    </section>

    <!-- Chat Section -->
    <button id="chatButton">💬</button>
    <div id="chatWindow">
        <div id="chatHeader">Discussion</div>
        <div id="chatMessages"></div>
        <div id="chatInput">
            <input type="text" id="messageInput" placeholder="Écrire un message..." />
            <button id="sendButton">Envoyer</button>
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
