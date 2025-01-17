<?php
session_start();
include 'config.php';

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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gérer les Abonnements</title>
    <link rel="stylesheet" href="style.css">
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
        <li><a href="manage_abonnements.php" class="active">Gérer les Abonnements</a></li>
        <li><a href="manage_utilisateurs.php">Gérer les Utilisateurs</a></li>
        <li><a href="manage_reservations.php">Gérer les Réservations</a></li>
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
    <h1>Gestion des Abonnements</h1>

    <div class="form-container">
        <h2>Ajouter un Abonnement</h2>
        <form method="POST" action="">
            <div class="form-group">
                <input type="number" name="id_utilisateur" placeholder="ID Utilisateur" required>
            </div>
            <div class="form-group">
                <input type="number" name="id_paiement" placeholder="ID Paiement" required>
            </div>
            <div class="form-group">
                <input type="text" name="type_abo" placeholder="Type d'Abonnement" required>
            </div>
            <div class="form-group">
                <input type="number" name="duree_abo" placeholder="Durée (jours)" required>
            </div>
            <div class="form-group">
                <input type="date" name="date_debut_abo" required>
            </div>
            <div class="form-group">
                <input type="date" name="date_fin_abo" required>
            </div>
            <button type="submit" class="btn" name="add">Ajouter</button>
        </form>
    </div>

    <div class="form-container">
        <h2>Liste des Abonnements</h2>
        <table>
            <thead>
                <tr>
                    <th>ID Abonnement</th>
                    <th>ID Utilisateur</th>
                    <th>ID Paiement</th>
                    <th>Type</th>
                    <th>Durée (jours)</th>
                    <th>Date Début</th>
                    <th>Date Fin</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['id_abo'] ?></td>
                        <td><?= $row['id_utilisateur'] ?></td>
                        <td><?= $row['id_paiement'] ?></td>
                        <td><?= htmlspecialchars($row['type_abo']) ?></td>
                        <td><?= $row['duree_abo'] ?></td>
                        <td><?= $row['date_debut_abo'] ?></td>
                        <td><?= $row['date_fin_abo'] ?></td>
                        <td>
                            <a class="btn btn-delete" href="?delete=<?= $row['id_abo'] ?>" onclick="return confirm('Supprimer cet abonnement ?');">Supprimer</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>
