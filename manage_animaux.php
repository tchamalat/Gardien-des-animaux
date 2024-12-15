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
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <div class="header-container">
            <img src="images/logo.png" alt="Logo Gardien des Animaux">
            <h1 class="header-slogan">Gestion des Animaux</h1>
            <div class="auth-buttons">
                <button class="btn" onclick="window.location.href='admin.php'">Retour au Tableau de Bord</button>
            </div>
        </div>
    </header>

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
            <tr>
                <th>ID Animal</th>
                <th>ID Utilisateur</th>
                <th>Type</th>
                <th>Prénom</th>
                <th>Photo</th>
                <th>Actions</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['id_animal']; ?></td>
                    <td><?php echo $row['id_utilisateur']; ?></td>
                    <td><?php echo htmlspecialchars($row['type']); ?></td>
                    <td><?php echo htmlspecialchars($row['prenom_animal']); ?></td>
                    <td>
                        <?php if (!empty($row['url_photo'])): ?>
                            <img src="data:image/jpeg;base64,<?php echo base64_encode($row['url_photo']); ?>" alt="Photo de l'animal">
                        <?php else: ?>
                            Pas de photo
                        <?php endif; ?>
                    </td>
                    <td>
                        <button class="btn btn-delete" onclick="if(confirm('Êtes-vous sûr de vouloir supprimer cet animal ?')) window.location.href='?delete=<?php echo $row['id_animal']; ?>'">Supprimer</button>
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
