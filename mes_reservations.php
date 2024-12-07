<?php
session_start();
include 'config.php'; // Inclut le fichier de connexion à la base de données

// Vérifie si le gardien est connecté, sinon redirige vers la page de connexion
if (!isset($_SESSION['gardien_id'])) {
    header("Location: login.php");
    exit();
}

// Récupère l'ID du gardien connecté
$gardien_id = $_SESSION['gardien_id'];

// Requête SQL pour récupérer les réservations associées au gardien
$sql = "
    SELECT r.id_reservation, r.date_debut, r.date_fin, r.lieu, r.type, r.heure_debut, r.heure_fin, c.nom, c.prenom, c.mail
    FROM reservation r
    INNER JOIN creation_compte c ON r.id_utilisateur = c.id
    WHERE r.gardien_id = ?
    ORDER BY r.date_debut ASC
";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $gardien_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mes Réservations</title>
    <link rel="stylesheet" href="styles.css"> <!-- Inclut une feuille de style CSS si existante -->
</head>
<body>
    <h1>Mes Réservations</h1>

    <?php if ($result->num_rows > 0): ?>
        <table border="1">
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
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Aucune réservation trouvée.</p>
    <?php endif; ?>

    <a href="dashboard.php">Retour au tableau de bord</a> <!-- Lien pour retourner au tableau de bord -->
</body>
</html>
