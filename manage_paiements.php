<?php
session_start();
include 'config.php';

// Gestion de l'ajout ou de la mise à jour d'un paiement
if (isset($_POST['save'])) {
    $id = $_POST['id'] ?? null;
    $id_reservation = $_POST['id_reservation'];
    $prix = $_POST['prix'];
    $date = $_POST['date'];
    $statut_du_paiement = $_POST['statut_du_paiement'];

    if ($id) {
        // Mise à jour du paiement existant
        $stmt = $conn->prepare("UPDATE Paiement SET id_reservation = ?, prix = ?, date = ?, statut_du_paiement = ? WHERE id_paiement = ?");
        $stmt->bind_param("idssi", $id_reservation, $prix, $date, $statut_du_paiement, $id);
    } else {
        // Ajout d'un nouveau paiement
        $stmt = $conn->prepare("INSERT INTO Paiement (id_reservation, prix, date, statut_du_paiement) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("idss", $id_reservation, $prix, $date, $statut_du_paiement);
    }

    $stmt->execute();
    $stmt->close();
    header("Location: manage_paiements.php");
    exit();
}

// Gestion de la suppression d'un paiement
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM Paiement WHERE id_paiement = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    header("Location: manage_paiements.php");
    exit();
}

// Récupération des paiements
$result = $conn->query("SELECT * FROM Paiement");
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gérer les Paiements</title>
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
    <h1>Gestion des Paiements</h1>
    <a href="admin.php">Retour au Tableau de Bord</a>

    <h2>Ajouter ou Modifier un Paiement</h2>
    <form method="POST" action="">
        <input type="hidden" name="id" id="id">
        <input type="number" name="id_reservation" id="id_reservation" placeholder="ID Réservation" required>
        <input type="number" step="0.01" name="prix" id="prix" placeholder="Prix" required>
        <input type="datetime-local" name="date" id="date" placeholder="Date" required>
        <input type="text" name="statut_du_paiement" id="statut_du_paiement" placeholder="Statut du Paiement" required>
        <button type="submit" name="save">Sauvegarder</button>
    </form>

    <h2>Liste des Paiements</h2>
    <table>
        <tr>
            <th>ID Paiement</th>
            <th>ID Réservation</th>
            <th>Prix</th>
            <th>Date</th>
            <th>Statut du Paiement</th>
            <th>Actions</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['id_paiement']; ?></td>
                <td><?php echo $row['id_reservation']; ?></td>
                <td><?php echo $row['prix']; ?> €</td>
                <td><?php echo $row['date']; ?></td>
                <td><?php echo htmlspecialchars($row['statut_du_paiement']); ?></td>
                <td>
                    <a href="?delete=<?php echo $row['id_paiement']; ?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce paiement ?');">Supprimer</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>

<?php $conn->close(); ?>
