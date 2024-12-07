<?php
session_start();
include 'config.php'; // Inclut le fichier de connexion à la base de données

// Vérifie si l'utilisateur est connecté et a le rôle de gardien (role = 0)
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
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
    SELECT r.id_reservation, r.date_debut, r.date_fin, r.lieu, r.type, r.heure_debut, r.heure_fin, c.nom, c.prenom, c.mail
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
