<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$sql_check_role = "SELECT id FROM creation_compte WHERE id = ? AND role = 0";
$stmt_check = $conn->prepare($sql_check_role);
$stmt_check->bind_param("i", $user_id);
$stmt_check->execute();
$result_check = $stmt_check->get_result();

if ($result_check->num_rows === 0) {
    echo "Vous devez être un gardien pour voir vos réservations.";
    exit();
}

if (isset($_GET['action']) && isset($_GET['id_reservation'])) {
    $action = $_GET['action']; 
    $id_reservation = $_GET['id_reservation'];
    $sql_check_payment = "SELECT paiement_effectue FROM reservation WHERE id_reservation = ?";
    $stmt_check_payment = $conn->prepare($sql_check_payment);
    $stmt_check_payment->bind_param("i", $id_reservation);
    $stmt_check_payment->execute();
    $result_payment = $stmt_check_payment->get_result();
    $payment_status = $result_payment->fetch_assoc();

    if ($payment_status['paiement_effectue'] == 1) {
        echo "<script>alert('Cette réservation a déjà été payée et ne peut pas être modifiée.');</script>";
        header("Location: mes_reservations.php");
        exit();
    }
    $validite = ($action === "valider") ? 1 : 0;
    $sql_update_validite = "UPDATE reservation SET validite = ? WHERE id_reservation = ?";
    $stmt_update = $conn->prepare($sql_update_validite);
    $stmt_update->bind_param("ii", $validite, $id_reservation);
    $stmt_update->execute();
    $stmt_update->close();
    header("Location: mes_reservations.php");
    exit();
}

$sql = "
    SELECT r.id_reservation, r.date_debut, r.date_fin, r.lieu, r.type, r.heure_debut, r.heure_fin, r.validite,
           r.paiement_effectue, c.id AS proprietaire_id, c.nom, c.prenom, c.mail
    FROM reservation r
    INNER JOIN creation_compte c ON r.id_utilisateur = c.id
    WHERE r.gardien_id = ?
    ORDER BY r.date_debut ASC
";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mes Réservations</title>
        <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html, body {
            height: 100%;
            font-family: Arial, sans-serif;
            background: url('images/premierplan.png') no-repeat center center fixed;
            background-size: cover;
            color: #333;
        }

        body {
            display: flex;
            flex-direction: column;
        }

        header {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 10;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 20px;
            background: none;
            box-shadow: none;
        }

        header img {
            height: 120px;
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
            flex: 1;
            max-width: 90%; 
            margin: 120px auto 50px;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
            overflow-x: auto; 
        }


        h1 {
            font-size: 2.5em;
            color: orange;
            text-align: center;
            margin-bottom: 30px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table th, table td {
            padding: 15px;
            text-align: center;
            border: 1px solid #ddd;
        }

        table th {
            background-color: orange;
            color: white;
            font-weight: bold;
        }

        table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        table tr:hover {
            background-color: #f1f1f1;
        }

        .no-reservation {
            text-align: center;
            font-size: 1.2em;
            color: #888;
            margin: 20px 0;
        }

        .btn-profile {
            display: inline-block;
            padding: 10px 15px;
            background-color: orange;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-size: 1em;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .btn-profile:hover {
            background-color: #ff7f00;
            transform: translateY(-3px);
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
            margin-top: 50px;
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
    </style>
</head>
<body>
    <!-- Header -->
    <header>
        <img src="images/logo.png" alt="Logo Gardien des Animaux">
        <div class="auth-buttons">
            <button class="btn" onclick="window.location.href='index_connect_gardien.php'">Accueil</button>
        </div>
    </header>

    <!-- Réservations -->
    <div class="container">
        <h1>Mes Réservations</h1>

        <?php if ($result->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nom Utilisateur</th>
                        <th>Email</th>
                        <th>Date Début</th>
                        <th>Date Fin</th>
                        <th>Lieu</th>
                        <th>Type</th>
                        <th>Heure Début</th>
                        <th>Heure Fin</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                     <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['id_reservation']); ?></td>
                            <td><?php echo htmlspecialchars($row['prenom'] . ' ' . $row['nom']); ?></td>
                            <td><?php echo htmlspecialchars($row['mail']); ?></td>
                            <td><?php echo htmlspecialchars($row['date_debut']); ?></td>
                            <td><?php echo htmlspecialchars($row['date_fin']); ?></td>
                            <td><?php echo htmlspecialchars($row['lieu']); ?></td>
                            <td><?php echo htmlspecialchars($row['type']); ?></td>
                            <td><?php echo htmlspecialchars($row['heure_debut']); ?></td>
                            <td><?php echo htmlspecialchars($row['heure_fin']); ?></td>
                            <td>
                                <?php 
                                if (is_null($row['validite'])) {
                                    echo "En attente";
                                } elseif ($row['validite'] == 1) {
                                    echo "Validée";
                                } elseif ($row['validite'] == 0) {
                                    echo "Refusée";
                                } else {
                                    echo "Inconnu";
                                }
                                ?>
                            <td>
                                <?php if ($row['paiement_effectue'] == 1): ?>
                                    <span style="color: green; font-weight: bold;">Payée</span>
                                    <br>
                                    <a href="profil_proprietaire.php?id=<?php echo $row['proprietaire_id']; ?>" class="btn-profile" style="margin-top: 5px;">Voir Profil</a>
                                <?php else: ?>
                                    <div style="display: flex; gap: 10px; justify-content: center;">
                                        <a href="?action=valider&id_reservation=<?php echo $row['id_reservation']; ?>" 
                                            class="btn-profile" 
                                            style="background-color: green;">Valider</a>
                                        <a href="?action=refuser&id_reservation=<?php echo $row['id_reservation']; ?>" 
                                            class="btn-profile" 
                                            style="background-color: red;">Refuser</a>
                                    </div>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>

        <?php else: ?>
            <p class="no-reservation">Aucune réservation trouvée.</p>
        <?php endif; ?>
    </div>

    <!-- Footer -->
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



