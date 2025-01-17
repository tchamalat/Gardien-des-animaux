<?php
session_start();
include 'config.php';

if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: login.html");
    exit();
}

// Gestion de l'ajout ou de la mise à jour d'un paiement
if (isset($_POST['save'])) {
    $id = $_POST['id'] ?? null;
    $id_reservation = $_POST['id_reservation'];
    $prix = $_POST['prix'];
    $date = $_POST['date'];
    $statut_du_paiement = $_POST['statut_du_paiement'];

    if ($id) {
        $stmt = $conn->prepare("UPDATE Paiement SET id_reservation = ?, prix = ?, date = ?, statut_du_paiement = ? WHERE id_paiement = ?");
        $stmt->bind_param("idssi", $id_reservation, $prix, $date, $statut_du_paiement, $id);
    } else {
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gérer les Paiements</title>
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
        <li><a href="manage_paiements.php" class="active">Gérer les Paiements</a></li>
        <li><a href="manage_hebergements.php">Gérer les Hébergements</a></li>
        <li><a href="?logout=1">Déconnexion</a></li>
    </ul>
</div>

<!-- Contenu principal -->
<div class="content">
    <h1>Gestion des Paiements</h1>

    <div class="form-container">
        <h2>Ajouter ou Modifier un Paiement</h2>
        <form method="POST" action="">
            <input type="hidden" name="id" id="id">
            <div class="form-group">
                <input type="number" name="id_reservation" id="id_reservation" placeholder="ID Réservation" required>
            </div>
            <div class="form-group">
                <input type="number" step="0.01" name="prix" id="prix" placeholder="Prix (€)" required>
            </div>
            <div class="form-group">
                <input type="datetime-local" name="date" id="date" required>
            </div>
            <div class="form-group">
                <input type="text" name="statut_du_paiement" id="statut_du_paiement" placeholder="Statut du Paiement" required>
            </div>
            <button type="submit" name="save" class="btn">Sauvegarder</button>
        </form>
    </div>

    <div class="form-container">
        <h2>Liste des Paiements</h2>
        <table>
            <thead>
                <tr>
                    <th>ID Paiement</th>
                    <th>ID Réservation</th>
                    <th>Prix (€)</th>
                    <th>Date</th>
                    <th>Statut du Paiement</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['id_paiement'] ?></td>
                        <td><?= $row['id_reservation'] ?></td>
                        <td><?= $row['prix'] ?> €</td>
                        <td><?= $row['date'] ?></td>
                        <td><?= htmlspecialchars($row['statut_du_paiement']) ?></td>
                        <td>
                            <a class="btn btn-delete" href="?delete=<?= $row['id_paiement'] ?>" onclick="return confirm('Supprimer ce paiement ?');">Supprimer</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>
