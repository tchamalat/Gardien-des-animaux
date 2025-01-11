<?php
session_start();
require_once 'config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Get reservation ID from URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "Réservation introuvable.";
    exit;
}

$reservation_id = intval($_GET['id']);

// Fetch reservation details
$sql = "
    SELECT r.id_reservation, r.date_debut, r.date_fin, r.lieu, r.type, r.heure_debut, r.heure_fin, r.validite, c.nom_utilisateur AS gardien
    FROM reservation r
    JOIN creation_compte c ON r.gardien_id = c.id
    WHERE r.id_utilisateur = ? AND r.id_reservation = ? AND r.validite = 1
";
$stmt = $conn->prepare($sql);
$stmt->bind_param('ii', $user_id, $reservation_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Réservation introuvable ou déjà payée.";
    exit;
}

$reservation = $result->fetch_assoc();

// Handle payment submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Simulate payment process
    $payment_success = true; // Set to `false` for payment failure simulation

    if ($payment_success) {
        // Update reservation to mark as paid
        $update_sql = "UPDATE reservation SET paiement_effectue = 1 WHERE id_reservation = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param('i', $reservation_id);
        $update_stmt->execute();

        echo "<script>alert('Paiement réussi !'); window.location.href='historique.php';</script>";
        exit;
    } else {
        echo "<script>alert('Le paiement a échoué. Veuillez réessayer.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paiement pour la Réservation</title>
    <style>
        /* Global Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            color: #333;
            background: url('images/premierplan.png') no-repeat center center fixed;
            background-size: cover;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            background: none;
            z-index: 10;
        }

        header img {
            height: 100px;
        }

        header .auth-buttons {
            display: flex;
            gap: 15px;
        }

        header .auth-buttons .btn {
            background-color: orange;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            font-size: 1em;
            cursor: pointer;
            text-decoration: none;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        header .auth-buttons .btn:hover {
            background-color: #ff7f00;
            transform: translateY(-3px);
        }

        .container {
            max-width: 700px;
            margin: 150px auto;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            padding: 30px;
            text-align: center;
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
        }

        h2 {
            color: orange;
            margin-bottom: 20px;
        }

        p {
            font-size: 1.1em;
            margin-bottom: 20px;
            color: #555;
        }

        .btn {
            background-color: orange;
            color: white;
            padding: 15px 30px;
            border: none;
            border-radius: 8px;
            font-size: 1.2em;
            cursor: pointer;
            text-decoration: none;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .btn:hover {
            background-color: #ff7f00;
            transform: translateY(-3px);
        }

        footer {
            background: rgba(0, 0, 0, 0.85);
            color: #fff;
            padding: 20px;
            margin-top: auto;
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
    </style>
</head>
<body>
    <!-- Header -->
    <header>
        <img src="images/logo.png" alt="Logo Gardien des Animaux">
        <div class="auth-buttons">
            <a href="historique.php" class="btn">Retour</a>
            <a href="index_connect.php" class="btn">Accueil</a>
        </div>
    </header>

    <!-- Payment Section -->
    <div class="container">
        <h2>Paiement pour la Réservation</h2>
        <p>Gardien : <strong><?php echo htmlspecialchars($reservation['gardien']); ?></strong></p>
        <p>Lieu : <strong><?php echo htmlspecialchars($reservation['lieu']); ?></strong></p>
        <p>Date : <strong><?php echo htmlspecialchars($reservation['date_debut']); ?> au <?php echo htmlspecialchars($reservation['date_fin']); ?></strong></p>
        <form method="POST">
            <button type="submit" class="btn">Effectuer le paiement</button>
        </form>
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
