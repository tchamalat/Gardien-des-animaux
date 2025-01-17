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
    $id_reservation = $_POST['id_reservation'];
    $id_paiement = $_POST['id_paiement'];
    $id_avis = $_POST['id_avis'] ?? null;
    $date_debut = $_POST['date_debut'];
    $date_fin = $_POST['date_fin'];
    $lieu = $_POST['lieu'];
    if ($id) {
        $stmt = $conn->prepare("UPDATE Hebergement SET id_reservation = ?, id_paiement = ?, id_avis = ?, date_debut = ?, date_fin = ?, lieu = ? WHERE id_hebergement = ?");
        $stmt->bind_param("iiisssi", $id_reservation, $id_paiement, $id_avis, $date_debut, $date_fin, $lieu, $id);
    } else {
        $stmt = $conn->prepare("INSERT INTO Hebergement (id_reservation, id_paiement, id_avis, date_debut, date_fin, lieu) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("iiisss", $id_reservation, $id_paiement, $id_avis, $date_debut, $date_fin, $lieu);
    }
    $stmt->execute();
    $stmt->close();
    header("Location: manage_hebergements.php");
    exit();
}

if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM Hebergement WHERE id_hebergement = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    header("Location: manage_hebergements.php");
    exit();
}
$result = $conn->query("SELECT * FROM Hebergement");
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gérer les Hébergements</title>
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
        <li><a href="manage_abonnements.php">Gérer les Abonnements</a></li>
        <li><a href="manage_utilisateurs.php">Gérer les Utilisateurs</a></li>
        <li><a href="manage_reservations.php">Gérer les Réservations</a></li>
        <li><a href="manage_avis.php">Gérer les Avis</a></li>
        <li><a href="manage_animaux.php">Gérer les Animaux</a></li>
        <li><a href="manage_faq.php">Gérer la FAQ</a></li>
        <li><a href="manage_paiements.php">Gérer les Paiements</a></li>
        <li><a href="manage_hebergements.php" class="active">Gérer les Hébergements</a></li>
        <li><a href="?logout=1">Déconnexion</a></li>
    </ul>
</div>

<!-- Contenu principal -->
<div class="content">
    <h1>Gestion des Hébergements</h1>

    <div class="form-container">
        <h2>Ajouter ou Modifier un Hébergement</h2>
        <form method="POST" action="">
            <input type="hidden" name="id" id="id">
            <div class="form-group">
                <input type="number" name="id_reservation" id="id_reservation" placeholder="ID Réservation" required>
            </div>
            <div class="form-group">
                <input type="number" name="id_paiement" id="id_paiement" placeholder="ID Paiement" required>
            </div>
            <div class="form-group">
                <input type="number" name="id_avis" id="id_avis" placeholder="ID Avis">
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
            <button type="submit" name="save" class="btn">Sauvegarder</button>
        </form>
    </div>

    <div class="form-container">
        <h2>Liste des Hébergements</h2>
        <table>
            <thead>
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
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['id_hebergement'] ?></td>
                        <td><?= $row['id_reservation'] ?></td>
                        <td><?= $row['id_paiement'] ?></td>
                        <td><?= $row['id_avis'] ?? 'N/A' ?></td>
                        <td><?= $row['date_debut'] ?></td>
                        <td><?= $row['date_fin'] ?></td>
                        <td><?= htmlspecialchars($row['lieu']) ?></td>
                        <td>
                            <a class="btn btn-delete" href="?delete=<?= $row['id_hebergement'] ?>" onclick="return confirm('Supprimer cet hébergement ?');">Supprimer</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>
