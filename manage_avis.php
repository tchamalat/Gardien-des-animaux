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
    <style>
        /* Styles globaux */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            color: #333;
            background: url('images/premierplan.png') no-repeat center center fixed;
            background-size: cover;
        }

        header {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 10;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 20px 40px;
            background: transparent;
            box-shadow: none;
        }

        header img {
            height: 80px;
        }

        header .header-slogan {
            font-size: 1.5em;
            color: orange;
            font-weight: bold;
            text-align: center;
            flex: 1;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.8);
        }

        header .auth-buttons {
            display: flex;
            gap: 15px;
        }

        header .auth-buttons .btn {
            background-color: orange;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            font-size: 1em;
            cursor: pointer;
            text-decoration: none;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        header .auth-buttons .btn:hover {
            background-color: #ff7f00;
            transform: translateY(-3px);
        }

        .form-container {
            max-width: 900px;
            margin: 150px auto;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
            text-align: center;
        }

        .form-container h2 {
            font-size: 1.8em;
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
            display: inline-block;
            background-color: orange;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            font-size: 1em;
            cursor: pointer;
            text-decoration: none;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .btn:hover {
            background-color: #ff7f00;
            transform: translateY(-3px);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table th, table td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
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

        footer {
            background: rgba(0, 0, 0, 0.85);
            color: #fff;
            padding: 20px;
            margin-top: 50px;
        }

        footer .footer-links {
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
        }

        footer .footer-links h4 {
            color: orange;
            margin-bottom: 10px;
        }

        footer .footer-links ul {
            list-style: none;
            padding: 0;
        }

        footer .footer-links a {
            color: white;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        footer .footer-links a:hover {
            color: orange;
        }
    </style>
</head>
<body>

<!-- Header -->
<header>
    <img src="images/logo.png" alt="Logo Gardien des Animaux">
    <h1 class="header-slogan">Gestion des Avis</h1>
    <div class="auth-buttons">
        <button class="btn" onclick="window.location.href='admin.php'">Retour au Tableau de Bord</button>
    </div>
</header>

<!-- Contenu principal -->
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
        <tr>
            <th>ID</th>
            <th>ID Utilisateur</th>
            <th>Commentaire</th>
            <th>Note</th>
            <th>Date de Création</th>
            <th>Actions</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['user_id']; ?></td>
                <td><?php echo htmlspecialchars($row['review']); ?></td>
                <td><?php echo $row['rating']; ?></td>
                <td><?php echo $row['date_created']; ?></td>
                <td>
                    <button class="btn btn-delete" onclick="if(confirm('Êtes-vous sûr de vouloir supprimer cet avis ?')) window.location.href='?delete=<?php echo $row['id']; ?>'">Supprimer</button>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</div>

<!-- Footer -->
<footer>
    <div class="footer-links">
        <div>
            <h4>En savoir plus :</h4>
            <ul>
                <li><a href="securite.php">Sécurité</a></li>
                <li><a href="aide.php">Centre d'aide</a></li>
            </ul>
        </div>
        <div>
            <h4>A propos de nous :</h4>
            <ul>
                <li><a href="confidentialite.php">Politique de confidentialité</a></li>
                <li><a href="contact.php">Nous contacter</a></li>
            </ul>
        </div>
        <div>
            <h4>Conditions Générales :</h4>
            <ul>
                <li><a href="conditions.php">Conditions d'utilisateur et de Service</a></li>
            </ul>
        </div>
    </div>
</footer>

</body>
</html>

<?php $conn->close(); ?>
