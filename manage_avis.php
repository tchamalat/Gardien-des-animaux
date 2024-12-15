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
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <div class="header-container">
            <img src="images/logo.png" alt="Logo Gardien des Animaux">
            <h1 class="header-slogan">Gestion des Avis</h1>
            <div class="auth-buttons">
                <button class="btn" onclick="window.location.href='admin.php'">Retour au Tableau de Bord</button>
            </div>
        </div>
    </header>

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
                        <a href="?delete=<?php echo $row['id']; ?>" class="btn auth-buttons" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet avis ?');">Supprimer</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    </div>

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
