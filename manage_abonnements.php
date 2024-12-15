<?php
session_start();
include 'config.php';

// Protection de la page admin
if (!isset($_SESSION['admin'])) {
    header("Location: login.html");
    exit();
}

// Gestion de l'ajout d'un abonnement
if (isset($_POST['add'])) {
    $id_utilisateur = $_POST['id_utilisateur'];
    $id_paiement = $_POST['id_paiement'];
    $type_abo = $_POST['type_abo'];
    $duree_abo = $_POST['duree_abo'];
    $date_debut_abo = $_POST['date_debut_abo'];
    $date_fin_abo = $_POST['date_fin_abo'];

    $stmt = $conn->prepare("INSERT INTO Abonnement (id_utilisateur, id_paiement, type_abo, duree_abo, date_debut_abo, date_fin_abo) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("iissss", $id_utilisateur, $id_paiement, $type_abo, $duree_abo, $date_debut_abo, $date_fin_abo);
    $stmt->execute();
    $stmt->close();
    header("Location: manage_abonnements.php");
    exit();
}

// Gestion de la suppression d'un abonnement
if (isset($_GET['delete'])) {
    $id_abo = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM Abonnement WHERE id_abo = ?");
    $stmt->bind_param("i", $id_abo);
    $stmt->execute();
    $stmt->close();
    header("Location: manage_abonnements.php");
    exit();
}

// Récupération des abonnements
$result = $conn->query("SELECT * FROM Abonnement");
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gérer les Abonnements</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 10px;
            text-align: center;
        }
        th {
            background-color: #f4f4f4;
        }
        a {
            text-decoration: none;
            color: red;
        }
        form {
            margin-bottom: 20px;
        }
        input, button {
            padding: 10px;
            margin: 5px;
        }
    </style>
</head>
<body>
    <h1>Gestion des Abonnements</h1>
    <a href="admin.php">Retour au Tableau de Bord</a>

    <h2>Ajouter un Abonnement</h2>
    <form method="POST" action="">
        <input type="number" name="id_utilisateur" placeholder="ID Utilisateur" required>
        <input type="number" name="id_paiement" placeholder="ID Paiement" required>
        <input type="text" name="type_abo" placeholder="Type d'Abonnement" required>
        <input type="number" name="duree_abo" placeholder="Durée (jours)" required>
        <input type="date" name="date_debut_abo" placeholder="Date Début" required>
        <input type="date" name="date_fin_abo" placeholder="Date Fin" required>
        <button type="submit" name="add">Ajouter</button>
    </form>

    <h2>Liste des Abonnements</h2>
    <table>
        <tr>
            <th>ID Abonnement</th>
            <th>ID Utilisateur</th>
            <th>ID Paiement</th>
            <th>Type Abonnement</th>
            <th>Durée (jours)</th>
            <th>Date Début</th>
            <th>Date Fin</th>
            <th>Action</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['id_abo']; ?></td>
                <td><?php echo $row['id_utilisateur']; ?></td>
                <td><?php echo $row['id_paiement']; ?></td>
                <td><?php echo htmlspecialchars($row['type_abo']); ?></td>
                <td><?php echo $row['duree_abo']; ?></td>
                <td><?php echo $row['date_debut_abo']; ?></td>
                <td><?php echo $row['date_fin_abo']; ?></td>
                <td>
                    <a href="?delete=<?php echo $row['id_abo']; ?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet abonnement ?');">Supprimer</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>

<?php $conn->close(); ?>
