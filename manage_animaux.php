<?php
session_start();
include 'config.php';

// Gestion de l'ajout ou de la mise à jour d'un animal
if (isset($_POST['save'])) {
    $id = $_POST['id'] ?? null;
    $id_utilisateur = $_POST['id_utilisateur'];
    $type = $_POST['type'];
    $prenom_animal = $_POST['prenom_animal'];

    // Gestion du téléchargement de la photo
    if (isset($_FILES['url_photo']) && $_FILES['url_photo']['error'] === UPLOAD_ERR_OK) {
        $url_photo = file_get_contents($_FILES['url_photo']['tmp_name']);
    } else {
        $url_photo = null;
    }

    if ($id) {
        if ($url_photo) {
            $stmt = $conn->prepare("UPDATE Animal SET id_utilisateur = ?, type = ?, prenom_animal = ?, url_photo = ? WHERE id_animal = ?");
            $stmt->bind_param("isssi", $id_utilisateur, $type, $prenom_animal, $url_photo, $id);
        } else {
            $stmt = $conn->prepare("UPDATE Animal SET id_utilisateur = ?, type = ?, prenom_animal = ? WHERE id_animal = ?");
            $stmt->bind_param("issi", $id_utilisateur, $type, $prenom_animal, $id);
        }
    } else {
        $stmt = $conn->prepare("INSERT INTO Animal (id_utilisateur, type, prenom_animal, url_photo) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("isss", $id_utilisateur, $type, $prenom_animal, $url_photo);
    }

    $stmt->execute();
    $stmt->close();
    header("Location: manage_animaux.php");
    exit();
}

// Gestion de la suppression d'un animal
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM Animal WHERE id_animal = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    header("Location: manage_animaux.php");
    exit();
}

// Récupération des animaux
$result = $conn->query("SELECT * FROM Animal");
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gérer les Animaux</title>
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

        table img {
            max-width: 100px;
            border-radius: 10px;
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
        <li><a href="manage_animaux.php" class="active">Gérer les Animaux</a></li>
        <li><a href="manage_faq.php">Gérer la FAQ</a></li>
        <li><a href="manage_paiements.php">Gérer les Paiements</a></li>
        <li><a href="manage_hebergements.php">Gérer les Hébergements</a></li>
        <li><a href="?logout=1">Déconnexion</a></li>
    </ul>
</div>

<!-- Contenu principal -->
<div class="content">
    <h1>Gestion des Animaux</h1>

    <div class="form-container">
        <h2>Ajouter ou Modifier un Animal</h2>
        <form method="POST" action="" enctype="multipart/form-data">
            <input type="hidden" name="id" id="id">
            <div class="form-group">
                <input type="number" name="id_utilisateur" id="id_utilisateur" placeholder="ID Utilisateur" required>
            </div>
            <div class="form-group">
                <input type="text" name="type" id="type" placeholder="Type d'Animal" required>
            </div>
            <div class="form-group">
                <input type="text" name="prenom_animal" id="prenom_animal" placeholder="Prénom de l'Animal" required>
            </div>
            <div class="form-group">
                <input type="file" name="url_photo" id="url_photo" accept="image/*">
            </div>
            <button type="submit" name="save" class="btn">Sauvegarder</button>
        </form>
    </div>

    <div class="form-container">
        <h2>Liste des Animaux</h2>
        <table>
            <thead>
                <tr>
                    <th>ID Animal</th>
                    <th>ID Utilisateur</th>
                    <th>Type</th>
                    <th>Prénom</th>
                    <th>Photo</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['id_animal'] ?></td>
                        <td><?= $row['id_utilisateur'] ?></td>
                        <td><?= htmlspecialchars($row['type']) ?></td>
                        <td><?= htmlspecialchars($row['prenom_animal']) ?></td>
                        <td>
                            <?php if (!empty($row['url_photo'])): ?>
                                <img src="data:image/jpeg;base64,<?= base64_encode($row['url_photo']) ?>" alt="Photo de l'animal">
                            <?php else: ?>
                                Pas de photo
                            <?php endif; ?>
                        </td>
                        <td>
                            <a class="btn btn-delete" href="?delete=<?= $row['id_animal'] ?>" onclick="return confirm('Supprimer cet animal ?');">Supprimer</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>
