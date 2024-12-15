<?php
session_start();
include 'config.php';

// Protection de la page admin
if (!isset($_SESSION['admin'])) {
    header("Location: login.html");
    exit();
}

// Gestion de l'ajout ou de la mise à jour d'un hébergement
if (isset($_POST['save'])) {
    $id = $_POST['id'] ?? null;
    $id_reservation = $_POST['id_reservation'];
    $id_paiement = $_POST['id_paiement'];
    $id_avis = $_POST['id_avis'] ?? null;
    $date_debut = $_POST['date_debut'];
    $date_fin = $_POST['date_fin'];
    $lieu = $_POST['lieu'];

    if ($id) {
        // Mise à jour de l'hébergement existant
        $stmt = $conn->prepare("UPDATE Hebergement SET id_reservation = ?, id_paiement = ?, id_avis = ?, date_debut = ?, date_fin = ?, lieu = ? WHERE id_hebergement = ?");
        $stmt->bind_param("iiisssi", $id_reservation, $id_paiement, $id_avis, $date_debut, $date_fin, $lieu, $id);
    } else {
        // Ajout d'un nouvel hébergement
        $stmt = $conn->prepare("INSERT INTO Hebergement (id_reservation, id_paiement, id_avis, date_debut, date_fin, lieu) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("iiisss", $id_reservation, $id_paiement, $id_avis, $date_debut, $date_fin, $lieu);
    }

    $stmt->execute();
    $stmt->close();
    header("Location: manage_hebergements.php");
    exit();
}

// Gestion de la suppression d'un hébergement
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM Hebergement WHERE id_hebergement = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    header("Location: manage_hebergements.php");
    exit();
}

// Récupération des hébergements
$result = $conn->query("SELECT * FROM Hebergement");
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gérer les Hébergements</title>
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
    <h1>Gestion des Hébergements</h1>
    <a href="admin.php">Retour au Tableau de Bord</a>

    <h2>Ajouter ou Modifier un Hébergement</h2>
    <form method="POST" action="">
        <input type="hidden" name="id" id="id">
        <input type="number" name="id_reservation" id="id_reservation" placeholder="ID Réservation" required>
        <input type="number" name="id_paiement" id="id_paiement" placeholder="ID Paiement" required>
        <input type="number" name="id_avis" id="id_avis" placeholder="ID Avis">
        <input type="date" name="date_debut" id="date_debut" placeholder="Date Début" required>
        <input type="date" name="date_fin" id="date_fin" placeholder="Date Fin" required>
        <input type="text" name="lieu" id="lieu" placeholder="Lieu" required>
        <button type="submit" name="save">Sauvegarder</button>
    </form>

    <h2>Liste des Hébergements</h2>
    <table>
        <tr>
            <th>ID Hébergement</th>
            <th>ID Réservation</th>
            <th>ID Paiement</th>
            <th>ID Avis</th>
            <th>Date Début</th>
            <th>Date Fin</th>
            <th>Lieu</th>
            <th>Actions</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['id_hebergement']; ?></td>
                <td><?php echo $row['id_reservation']; ?></td>
                <td><?php echo $row['id_paiement']; ?></td>
                <td><?php echo $row['id_avis'] ?? 'N/A'; ?></td>
                <td><?php echo $row['date_debut']; ?></td>
                <td><?php echo $row['date_fin']; ?></td>
                <td><?php echo htmlspecialchars($row['lieu']); ?></td>
                <td>
                    <a href="?delete=<?php echo $row['id_hebergement']; ?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet hébergement ?');">Supprimer</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>

<?php $conn->close(); ?>
