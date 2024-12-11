<?php
include 'config.php';
session_start();

$message_confirmation = ''; // Variable pour stocker le message de confirmation

// Vérification si un gardien est sélectionné
if (isset($_GET['gardien_id'])) {
    $gardien_id = $_GET['gardien_id'];
    $_SESSION['selected_gardien'] = $gardien_id;

    // Récupérer les informations du gardien depuis la base de données
    $sql = "SELECT nom_utilisateur, ville, service, budget_min, budget_max FROM creation_compte WHERE id = ? AND role = 0";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $gardien_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $gardien_info = $result->fetch_assoc();
    } else {
        die("Gardien introuvable ou non valide !");
    }

    $stmt->close();
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['selected_gardien'])) {
    $gardien_id = $_SESSION['selected_gardien'];
    $proprietaire_id = $_SESSION['user_id'];
    $date_debut = $_POST['date_debut'];
    $date_fin = $_POST['date_fin'];
    $heure_debut = $_POST['heure_debut'];
    $heure_fin = $_POST['heure_fin'];
    $lieu = $_POST['lieu'];
    $type = $_POST['type'];

    if (!$date_debut || !$date_fin || !$heure_debut || !$heure_fin || !$lieu || !$type) {
        die("Tous les champs doivent être renseignés !");
    }

    $sql = "INSERT INTO reservation (gardien_id, id_utilisateur, date_debut, date_fin, lieu, type, heure_debut, heure_fin) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iissssss", $gardien_id, $proprietaire_id, $date_debut, $date_fin, $lieu, $type, $heure_debut, $heure_fin);

    if ($stmt->execute()) {
        // Utilisation du nom du gardien dans le message de confirmation
        $message_confirmation = "Votre réservation a été effectuée avec succès pour le gardien <strong>" 
                                . htmlspecialchars($gardien_info['nom_utilisateur']) . "</strong> !";
        unset($_SESSION['selected_gardien']);
    } else {
        $message_confirmation = "Erreur lors de la réservation : " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réservation</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .reservation-container {
            background-color: #f9f9f9;
            padding: 30px;
            border-radius: 15px;
            max-width: 600px;
            margin: 50px auto;
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
        }

        .reservation-container h1, .reservation-container h2 {
            text-align: center;
            color: #f5a623;
            margin-bottom: 20px;
        }

        .reservation-details {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .reservation-details p {
            margin: 10px 0;
            font-size: 1em;
        }

        .reservation-form label {
            font-weight: bold;
            margin-bottom: 5px;
            display: block;
        }

        .reservation-form input, .reservation-form select {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1em;
        }

        .reservation-form button {
            background-color: #f5a623;
            color: white;
            border: none;
            padding: 15px;
            font-size: 1.2em;
            border-radius: 8px;
            cursor: pointer;
            width: 100%;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .reservation-form button:hover {
            background-color: #ff7f00;
            transform: scale(1.05);
        }

        .confirmation-message {
            background-color: #e6ffe6;
            color: #2c7a2c;
            border: 1px solid #2c7a2c;
            border-radius: 10px;
            padding: 20px;
            margin: 20px auto;
            text-align: center;
            font-size: 1.2em;
        }
    </style>
</head>
<body>

<header>
    <h1>Réservation</h1>
    <div class="auth-buttons">
        <button class="btn" onclick="window.location.href='logout.php'">Déconnexion</button> 
        <button class="btn" onclick="window.location.href='index_connect.php'">Accueil</button>
    </div>
</header>

<div class="reservation-container">
    <h1>Faire une réservation</h1>

    <?php if (!empty($message_confirmation)): ?>
        <div class="confirmation-message">
            <?php echo $message_confirmation; ?>
        </div>
    <?php endif; ?>

    <?php if (isset($gardien_info)): ?>
        <h2>Informations sur le gardien</h2>
        <div class="reservation-details">
            <p><strong>Nom :</strong> <?php echo htmlspecialchars($gardien_info['nom_utilisateur']); ?></p>
            <p><strong>Ville :</strong> <?php echo htmlspecialchars($gardien_info['ville']); ?></p>
            <p><strong>Service :</strong> <?php echo htmlspecialchars($gardien_info['service']); ?></p>
            <p><strong>Budget :</strong> <?php echo htmlspecialchars($gardien_info['budget_min']); ?>€ - <?php echo htmlspecialchars($gardien_info['budget_max']); ?>€</p>
        </div>
    <?php endif; ?>

    <form action="reservation.php" method="POST" class="reservation-form">
        <label for="date_debut">Date de début :</label>
        <input type="date" name="date_debut" id="date_debut" required>

        <label for="date_fin">Date de fin :</label>
        <input type="date" name="date_fin" id="date_fin" required>

        <label for="heure_debut">Heure de début :</label>
        <input type="time" name="heure_debut" id="heure_debut" required>

        <label for="heure_fin">Heure de fin :</label>
        <input type="time" name="heure_fin" id="heure_fin" required>

        <label for="lieu">Lieu :</label>
        <input type="text" name="lieu" id="lieu" required>

        <label for="type">Type de service :</label>
        <select name="type" id="type" required>
            <option value="Hébergement">Hébergement</option>
            <option value="Promenade">Promenade</option>
            <option value="Garde à domicile">Garde à domicile</option>
        </select>

        <button type="submit">Confirmer la réservation</button>
    </form>
</div>

<footer>
    <div class="footer-links">
        <div>
            <h4>En savoir plus :</h4>
            <ul>
                <li><a href="securite.php">Sécurité</a></li>
                <li><a href="aide.php">Centre d'aide</a></li>
            </ul>
        </div>
        <div>
            <h4>A propos de nous :</h4>
            <ul>
                <li><a href="confidentialite.php">Politique de confidentialité</a></li>
                <li><a href="contact.php">Nous contacter</a></li>
            </ul>
        </div>
        <div>
            <h4>Conditions Générales :</h4>
            <ul>
                <li><a href="conditions.php">Conditions d'utilisateur et de Service</a></li>
            </ul>
        </div>
    </div>
</footer>

</body>
</html>
