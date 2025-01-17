<?php
session_start();
include 'config.php';

if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: login.html");
    exit();
}

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
        $stmt = $conn->prepare("UPDATE reservation SET id_utilisateur = ?, date_debut = ?, date_fin = ?, lieu = ?, type = ?, heure_debut = ?, heure_fin = ?, gardien_id = ? WHERE id_reservation = ?");
        $stmt->bind_param("issssssii", $id_utilisateur, $date_debut, $date_fin, $lieu, $type, $heure_debut, $heure_fin, $gardien_id, $id);
    } else {
        $stmt = $conn->prepare("INSERT INTO reservation (id_utilisateur, date_debut, date_fin, lieu, type, heure_debut, heure_fin, gardien_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("issssssi", $id_utilisateur, $date_debut, $date_fin, $lieu, $type, $heure_debut, $heure_fin, $gardien_id);
    }
    $stmt->execute();
    $stmt->close();
    header("Location: manage_reservations.php");
    exit();
}

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM reservation WHERE id_reservation = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    header("Location: manage_reservations.php");
    exit();
}
$result = $conn->query("SELECT * FROM reservation");
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gérer les Réservations</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
        }

        .sidebar {
            width: 250px;
            height: 100vh;
            background-color: #2c3e50;
            color: white;
            position: fixed;
            top: 0;
            left: 0;
            padding: 20px;
            display: flex;
            flex-direction: column;
        }

        .sidebar h2 {
            font-size: 1.5rem;
            color: orange;
            margin-bottom: 30px;
            text-align: center;
        }

        .sidebar ul {
            list-style: none;
            padding: 0;
        }

        .sidebar ul li {
            margin: 10px 0;
        }

        .sidebar ul li a {
            color: white;
            text-decoration: none;
            padding: 10px 15px;
            border-radius: 5px;
            display: block;
            transition: background-color 0.3s;
        }

        .sidebar ul li a:hover {
            background-color: #34495e;
        }

        .sidebar ul li a.active {
            background-color: #e96d0c;
        }

        .content {
            margin-left: 270px;
            padding: 20px;
        }

        .content h1 {
            font-size: 2.5rem;
            color: orange;
            text-align: center;
            margin-bottom: 20px;
        }

        .form-container {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
            margin-bottom: 30px;
        }

        .form-container h2 {
            color: orange;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .btn {
            background-color: orange;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .btn:hover {
            background-color: #e96d0c;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table th, table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }

        table th {
            background-color: orange;
            color: white;
        }

        .btn-delete {
            background-color: #e74c3c;
        }

        .btn-delete:hover {
            background-color: #c0392b;
        }
    </style>
</head>
<body>

<!-- Barre latérale -->
<div class="sidebar">
    <h2>Menu Admin</h2>
    <ul>
        <li><a href="admin.php">Tableau de Bord</a></li>
        <li><a href="manage_utilisateurs.php">Gérer les Utilisateurs</a></li>
        <li><a href="manage_reservations.php" class="active">Gérer les Réservations</a></li>
        <li><a href="manage_avis.php">Gérer les Avis</a></li>
        <li><a href="manage_animaux.php">Gérer les Animaux</a></li>
        <li><a href="manage_faq.php">Gérer la FAQ</a></li>
        <li><a href="manage_paiements.php">Gérer les Paiements</a></li>
        <li><a href="manage_hebergements.php">Gérer les Hébergements</a></li>
        <li><a href="?logout=1">Déconnexion</a></li>
    </ul>
</div>

<!-- Contenu principal -->
<div class="content">
    <h1>Gestion des Réservations</h1>

    <div class="form-container">
        <h2>Ajouter ou Modifier une Réservation</h2>
        <form method="POST" action="">
            <input type="hidden" name="id" id="id">
            <div class="form-group">
                <input type="number" name="id_utilisateur" id="id_utilisateur" placeholder="ID Utilisateur" required>
            </div>
            <div class="form-group">
                <input type="date" name="date_debut" id="date_debut" required>
            </div>
            <div class="form-group">
                <input type="date" name="date_fin" id="date_fin" required>
            </div>
            <div class="form-group">
                <input type="text" name="lieu" id="lieu" placeholder="Lieu" required>
            </div>
            <div class="form-group">
                <input type="text" name="type" id="type" placeholder="Type de Réservation" required>
            </div>
            <div class="form-group">
                <input type="time" name="heure_debut" id="heure_debut" required>
            </div>
            <div class="form-group">
                <input type="time" name="heure_fin" id="heure_fin" required>
            </div>
            <div class="form-group">
                <input type="number" name="gardien_id" id="gardien_id" placeholder="ID Gardien" required>
            </div>
            <button type="submit" name="save" class="btn">Sauvegarder</button>
        </form>
    </div>

    <div class="form-container">
        <h2>Liste des Réservations</h2>
        <table>
            <thead>
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
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['id_reservation'] ?></td>
                        <td><?= $row['id_utilisateur'] ?></td>
                        <td><?= $row['date_debut'] ?></td>
                        <td><?= $row['date_fin'] ?></td>
                        <td><?= htmlspecialchars($row['lieu']) ?></td>
                        <td><?= htmlspecialchars($row['type']) ?></td>
                        <td><?= $row['heure_debut'] ?></td>
                        <td><?= $row['heure_fin'] ?></td>
                        <td><?= $row['gardien_id'] ?></td>
                        <td>
                            <a class="btn btn-delete" href="?delete=<?= $row['id_reservation'] ?>" onclick="return confirm('Supprimer cette réservation ?');">Supprimer</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>
