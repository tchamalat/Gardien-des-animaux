<?php
session_start();
include 'config.php'; // Inclut le fichier de connexion à la base de données

// Vérifie si l'utilisateur est connecté et a le rôle de gardien (role = 0)
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Récupère l'ID de l'utilisateur connecté
$user_id = $_SESSION['user_id'];

// Vérifie si l'utilisateur est un gardien en consultant la base de données
$sql_check_role = "SELECT id FROM creation_compte WHERE id = ? AND role = 0";
$stmt_check = $conn->prepare($sql_check_role);
$stmt_check->bind_param("i", $user_id);
$stmt_check->execute();
$result_check = $stmt_check->get_result();

if ($result_check->num_rows === 0) {
    echo "Vous devez être un gardien pour voir vos réservations.";
    exit();
}

// Requête SQL pour récupérer les réservations associées au gardien
$sql = "
    SELECT r.id_reservation, r.date_debut, r.date_fin, r.lieu, r.type, r.heure_debut, r.heure_fin, 
           c.id AS proprietaire_id, c.nom, c.prenom, c.mail
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
    <title>Mes Réservations</title>
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
            max-width: 1100px;
            margin: 50px auto;
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #444;
            margin-bottom: 30px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table th, table td {
            padding: 12px;
            text-align: center;
            border: 1px solid #ddd;
        }

        table th {
            background-color: #f5a623;
            color: #fff;
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
            font-size: 18px;
            color: #888;
        }

        .btn-profile {
            display: inline-block;
            padding: 8px 12px;
            background-color: #007bff;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .btn-profile:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header>
        <div class="header-container">
            <img src="images/logo.png" alt="Logo Gardien des Animaux">
            <div class="auth-buttons">
                <button class="btn" onclick="window.location.href='index_connect_gardien.php'">Accueil</button>
            </div>
        </div>
    </header>
    <div class="container">
        <h1>Mes Réservations</h1>

        <?php if ($result->num_rows > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID Réservation</th>
                        <th>Nom Utilisateur</th>
                        <th>Email</th>
                        <th>Date Début</th>
                        <th>Date Fin</th>
                        <th>Lieu</th>
                        <th>Type</th>
                        <th>Heure Début</th>
                        <th>Heure Fin</th>
                        <th>Profil Public</th>
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
                                <a href="profil_proprietaire.php?id=<?php echo htmlspecialchars($row['proprietaire_id']); ?>" class="btn">Voir le Profil</a>
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
