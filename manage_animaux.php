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
        // Mise à jour de l'animal existant
        if ($url_photo) {
            $stmt = $conn->prepare("UPDATE Animal SET id_utilisateur = ?, type = ?, prenom_animal = ?, url_photo = ? WHERE id_animal = ?");
            $stmt->bind_param("isssi", $id_utilisateur, $type, $prenom_animal, $url_photo, $id);
        } else {
            $stmt = $conn->prepare("UPDATE Animal SET id_utilisateur = ?, type = ?, prenom_animal = ? WHERE id_animal = ?");
            $stmt->bind_param("issi", $id_utilisateur, $type, $prenom_animal, $id);
        }
    } else {
        // Ajout d'un nouvel animal
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
    <title>Gérer les Animaux</title>
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
        img {
            max-width: 100px;
            height: auto;
        }
    </style>
</head>
<body>
    <h1>Gestion des Animaux</h1>
    <a href="admin.php">Retour au Tableau de Bord</a>

    <h2>Ajouter ou Modifier un Animal</h2>
    <form method="POST" action="" enctype="multipart/form-data">
        <input type="hidden" name="id" id="id">
        <input type="number" name="id_utilisateur" id="id_utilisateur" placeholder="ID Utilisateur" required>
        <input type="text" name="type" id="type" placeholder="Type d'Animal" required>
        <input type="text" name="prenom_animal" id="prenom_animal" placeholder="Prénom de l'Animal" required>
        <input type="file" name="url_photo" id="url_photo" accept="image/*">
        <button type="submit" name="save">Sauvegarder</button>
    </form>

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
                    <a href="?delete=<?php echo $row['id_animal']; ?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet animal ?');">Supprimer</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>

<?php $conn->close(); ?>
