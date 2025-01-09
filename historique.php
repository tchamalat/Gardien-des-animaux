<?php
session_start();
require_once 'config.php';

// Vérifie si l'utilisateur est connecté et s'il a le rôle de propriétaire (role = 1)
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 1) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Récupération des réservations associées au propriétaire
$sql = "
    SELECT r.id_reservation, r.date_debut, r.date_fin, r.lieu, r.type, r.heure_debut, r.heure_fin, r.validite, c.nom_utilisateur AS gardien
    FROM reservation r
    JOIN creation_compte c ON r.gardien_id = c.id
    WHERE r.id_utilisateur = ?
    ORDER BY r.date_debut DESC
";

$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historique des Réservations</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
            background: url('images/premierplan.png') no-repeat center center fixed;
            background-size: cover;
            margin: 0;
            padding: 0;
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
            background: none;
        }

        header img {
            height: 100px;
        }
        header .auth-buttons {
            display: flex;
            flex-wrap: wrap; /* Permet d'adapter les boutons s'ils ne tiennent pas sur une seule ligne */
            gap: 10px; /* Espace entre les boutons */
        }

        header .auth-buttons .btn {
            background-color: orange;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 8px;
            font-size: 1em;
            cursor: pointer;
            text-decoration: none;
            transition: background-color 0.3s ease, transform 0.3s ease;
            white-space: nowrap; /* Empêche les boutons de se diviser sur plusieurs lignes */
        }

        header .auth-buttons .btn:hover {
            background-color: #ff7f00;
            transform: translateY(-3px);
        }
        .auth-buttons {
	        position: absolute;
            top: 20px; /* Ajustez la valeur si nécessaire */
            right: 20px; /* Ajustez la valeur si nécessaire */
            display: flex;
            gap: 15px;
            z-index: 100; /* Assurez-vous que les boutons sont au-dessus d'autres éléments */
	}
        .auth-buttons .btn {
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

        .container {
            max-width: 900px;
            margin: 150px auto 50px auto;
            background: rgba(255, 255, 255, 0.9);
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
            text-align: center;
        }

        h2 {
            color: orange;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }

        th {
            background-color: orange;
            color: white;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        .no-reservations {
            text-align: center;
            color: #888;
            margin-top: 20px;
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
        <button class="btn" onclick="window.location.href='index_connect.php'">Accueil</button>
    </div>
</header>

<!-- Container -->
<div class="container">
    <h2>Historique des réservations</h2>

    <?php if ($result->num_rows > 0): ?>
        <table>
            <tr>
                <th>ID Réservation</th>
                <th>Date Début</th>
                <th>Date Fin</th>
                <th>Heure Début</th>
                <th>Heure Fin</th>
                <th>Lieu</th>
                <th>Type</th>
                <th>Gardien</th>
                <th>Statut</th>
                <th>Supprimer</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['id_reservation']); ?></td>
                    <td><?php echo htmlspecialchars($row['date_debut']); ?></td>
                    <td><?php echo htmlspecialchars($row['date_fin']); ?></td>
                    <td><?php echo htmlspecialchars($row['heure_debut']); ?></td>
                    <td><?php echo htmlspecialchars($row['heure_fin']); ?></td>
                    <td><?php echo htmlspecialchars($row['lieu']); ?></td>
                    <td><?php echo htmlspecialchars($row['type']); ?></td>
                    <td><?php echo htmlspecialchars($row['gardien']); ?></td>
                    <td>
                        <?php
                        if (is_null($row['validite'])) {
                            echo "En attente";
                        } elseif ($row['validite'] == 1) {
                            echo "Validée";
                        } elseif ($row['validite'] == 0) {
                            echo "Refusée";
                        }
                        ?>
                    </td>
                    <td>
                        <a href="supprimer_reservation.php?id=<?php echo $row['id_reservation']; ?>" onclick="return confirm('Voulez-vous vraiment supprimer cette réservation ?');">
                            ❌
                        </a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p class="no-reservations">Aucune réservation trouvée.</p>
    <?php endif; ?>
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
