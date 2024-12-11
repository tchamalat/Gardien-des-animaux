<?php
session_start();
require_once 'config.php'; // Fichier de connexion à la base de données

// Vérifie si l'utilisateur est connecté et s'il a le rôle de propriétaire (role = 1)
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 1) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Récupération des réservations associées au propriétaire
$sql = "
    SELECT r.id_reservation, r.date_debut, r.date_fin, r.lieu, r.type, r.heure_debut, r.heure_fin, c.nom_utilisateur AS gardien
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
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 90%;
            max-width: 800px;
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
            background-color: #f5a623;
            color: #fff;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .no-reservations {
            text-align: center;
            color: #888;
            margin-top: 20px;
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
                <button class="btn" onclick="window.location.href='index_connect.php'">Accueil</button>
            </div>
        </div>
    </header>

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
