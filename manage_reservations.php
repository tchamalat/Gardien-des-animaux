<?php
session_start();
include 'config.php';

// Gestion de l'ajout ou de la mise à jour d'une réservation
if (isset($_POST['save'])) {
    $id = $_POST['id'] ?? null;
    $id_utilisateur = $_POST['id_utilisateur'];
    $date_debut = $_POST['date_debut'];
    $date_fin = $_POST['date_fin'];
    $lieu = $_POST['lieu'];
    $type = $_POST['type'];
    $heure_debut = $_POST['heure_debut'];
    $heure_fin = $_POST['heure_fin'];
    $gardien_id = $_POST['gardien_id'];

    if ($id) {
        // Mise à jour de la réservation existante
        $stmt = $conn->prepare("UPDATE reservation SET id_utilisateur = ?, date_debut = ?, date_fin = ?, lieu = ?, type = ?, heure_debut = ?, heure_fin = ?, gardien_id = ? WHERE id_reservation = ?");
        $stmt->bind_param("issssssii", $id_utilisateur, $date_debut, $date_fin, $lieu, $type, $heure_debut, $heure_fin, $gardien_id, $id);
    } else {
        // Ajout d'une nouvelle réservation
        $stmt = $conn->prepare("INSERT INTO reservation (id_utilisateur, date_debut, date_fin, lieu, type, heure_debut, heure_fin, gardien_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("issssssi", $id_utilisateur, $date_debut, $date_fin, $lieu, $type, $heure_debut, $heure_fin, $gardien_id);
    }
    
    $stmt->execute();
    $stmt->close();
    header("Location: manage_reservations.php");
    exit();
}

// Gestion de la suppression d'une réservation
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM reservation WHERE id_reservation = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    header("Location: manage_reservations.php");
    exit();
}

// Récupération des réservations
$result = $conn->query("SELECT * FROM reservation");
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gérer les Réservations</title>
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
    <h1>Gestion des Réservations</h1>
    <a href="admin.php">Retour au Tableau de Bord</a>

    <h2>Ajouter ou Modifier une Réservation</h2>
    <form method="POST" action="">
        <input type="hidden" name="id" id="id">
        <input type="number" name="id_utilisateur" id="id_utilisateur" placeholder="ID Utilisateur" required>
        <input type="date" name="date_debut" id="date_debut" placeholder="Date Début" required>
        <input type="date" name="date_fin" id="date_fin" placeholder="Date Fin" required>
        <input type="text" name="lieu" id="lieu" placeholder="Lieu" required>
        <input type="text" name="type" id="type" placeholder="Type de Réservation" required>
        <input type="time" name="heure_debut" id="heure_debut" placeholder="Heure Début" required>
        <input type="time" name="heure_fin" id="heure_fin" placeholder="Heure Fin" required>
        <input type="number" name="gardien_id" id="gardien_id" placeholder="ID Gardien" required>
        <button type="submit" name="save">Sauvegarder</button>
    </form>

    <h2>Liste des Réservations</h2>
    <table>
        <tr>
            <th>ID Réservation</th>
            <th>ID Utilisateur</th>
            <th>Date Début</th>
            <th>Date Fin</th>
            <th>Lieu</th>
            <th>Type</th>
            <th>Heure Début</th>
            <th>Heure Fin</th>
            <th>ID Gardien</th>
            <th>Actions</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['id_reservation']; ?></td>
                <td><?php echo $row['id_utilisateur']; ?></td>
                <td><?php echo $row['date_debut']; ?></td>
                <td><?php echo $row['date_fin']; ?></td>
                <td><?php echo htmlspecialchars($row['lieu']); ?></td>
                <td><?php echo htmlspecialchars($row['type']); ?></td>
                <td><?php echo $row['heure_debut']; ?></td>
                <td><?php echo $row['heure_fin']; ?></td>
                <td><?php echo $row['gardien_id']; ?></td>
                <td>
                    <a href="?delete=<?php echo $row['id_reservation']; ?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette réservation ?');">Supprimer</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>

<?php $conn->close(); ?>
