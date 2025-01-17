<?php
session_start();
include 'config.php';

// Gestion de l'ajout ou de la mise à jour d'un avis
if (isset($_POST['save'])) {
    $id = $_POST['id'] ?? null;
    $user_id = $_POST['user_id'];
    $review = $_POST['review'];
    $rating = $_POST['rating'];

    if ($id) {
        $stmt = $conn->prepare("UPDATE avis SET user_id = ?, review = ?, rating = ? WHERE id = ?");
        $stmt->bind_param("isii", $user_id, $review, $rating, $id);
    } else {
        $stmt = $conn->prepare("INSERT INTO avis (user_id, review, rating) VALUES (?, ?, ?)");
        $stmt->bind_param("isi", $user_id, $review, $rating);
    }
    
    $stmt->execute();
    $stmt->close();
    header("Location: manage_avis.php");
    exit();
}

// Gestion de la suppression d'un avis
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM avis WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    header("Location: manage_avis.php");
    exit();
}

// Récupération des avis
$result = $conn->query("SELECT * FROM avis");
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gérer les Avis</title>
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

        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 1em;
        }

        textarea {
            resize: none;
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
        <li><a href="manage_avis.php" class="active">Gérer les Avis</a></li>
        <li><a href="manage_animaux.php">Gérer les Animaux</a></li>
        <li><a href="manage_faq.php">Gérer la FAQ</a></li>
        <li><a href="manage_paiements.php">Gérer les Paiements</a></li>
        <li><a href="manage_hebergements.php">Gérer les Hébergements</a></li>
        <li><a href="?logout=1">Déconnexion</a></li>
    </ul>
</div>

<!-- Contenu principal -->
<div class="content">
    <h1>Gestion des Avis</h1>

    <div class="form-container">
        <h2>Ajouter ou Modifier un Avis</h2>
        <form method="POST" action="">
            <input type="hidden" name="id" id="id">
            <div class="form-group">
                <input type="number" name="user_id" id="user_id" placeholder="ID Utilisateur" required>
            </div>
            <div class="form-group">
                <textarea name="review" id="review" placeholder="Commentaire" required></textarea>
            </div>
            <div class="form-group">
                <input type="number" name="rating" id="rating" placeholder="Note (1-5)" min="1" max="5" required>
            </div>
            <button type="submit" name="save" class="btn">Sauvegarder</button>
        </form>
    </div>

    <div class="form-container">
        <h2>Liste des Avis</h2>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>ID Utilisateur</th>
                    <th>Commentaire</th>
                    <th>Note</th>
                    <th>Date de Création</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['id'] ?></td>
                        <td><?= $row['user_id'] ?></td>
                        <td><?= htmlspecialchars($row['review']) ?></td>
                        <td><?= $row['rating'] ?></td>
                        <td><?= $row['date_created'] ?></td>
                        <td>
                            <a class="btn btn-delete" href="?delete=<?= $row['id'] ?>" onclick="return confirm('Supprimer cet avis ?');">Supprimer</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>
