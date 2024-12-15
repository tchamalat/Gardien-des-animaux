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
        // Mise à jour de l'avis existant
        $stmt = $conn->prepare("UPDATE avis SET user_id = ?, review = ?, rating = ? WHERE id = ?");
        $stmt->bind_param("isii", $user_id, $review, $rating, $id);
    } else {
        // Ajout d'un nouvel avis
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
    <title>Gérer les Avis</title>
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
    <h1>Gestion des Avis</h1>
    <a href="admin.php">Retour au Tableau de Bord</a>

    <h2>Ajouter ou Modifier un Avis</h2>
    <form method="POST" action="">
        <input type="hidden" name="id" id="id">
        <input type="number" name="user_id" id="user_id" placeholder="ID Utilisateur" required>
        <textarea name="review" id="review" placeholder="Commentaire" required></textarea>
        <input type="number" name="rating" id="rating" placeholder="Note (1-5)" min="1" max="5" required>
        <button type="submit" name="save">Sauvegarder</button>
    </form>

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
                    <a href="?delete=<?php echo $row['id']; ?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet avis ?');">Supprimer</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>

<?php $conn->close(); ?>
