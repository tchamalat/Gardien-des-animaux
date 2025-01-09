<?php 
session_start(); 

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include 'config.php';

$user_id = $_SESSION['user_id'];

// Activer le débogage
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Gérer l'ajout des animaux
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajouter_animaux']) && isset($_POST['nom_animal'])) {
    $noms_animaux = is_array($_POST['nom_animal']) ? $_POST['nom_animal'] : [$_POST['nom_animal']];
    $photos_animaux = $_FILES['photo_animal'];

    if (!is_array($photos_animaux['tmp_name'])) {
        $photos_animaux = [
            'name' => [$photos_animaux['name']],
            'type' => [$photos_animaux['type']],
            'tmp_name' => [$photos_animaux['tmp_name']],
            'error' => [$photos_animaux['error']],
            'size' => [$photos_animaux['size']],
        ];
    }

    for ($i = 0; $i < count($noms_animaux); $i++) {
        $nom_animal = $noms_animaux[$i];

        if ($photos_animaux['error'][$i] === UPLOAD_ERR_OK) {
            $photo_tmp_name = $photos_animaux['tmp_name'][$i];
            $photo_content = file_get_contents($photo_tmp_name);

            $sql = "INSERT INTO Animal (id_utilisateur, prenom_animal, url_photo) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("iss", $user_id, $nom_animal, $photo_content);
            $stmt->execute();
            $stmt->close();
        }
    }
    $message = "Animaux ajoutés avec succès !";
}

// Gérer la mise à jour des animaux
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_animal'])) {
    $id_animal = $_POST['id_animal'];
    $nom_animal = $_POST['nom_animal'];

    if (!empty($_FILES['photo_animal']['tmp_name']) && $_FILES['photo_animal']['error'] === UPLOAD_ERR_OK) {
        $photo_tmp_name = $_FILES['photo_animal']['tmp_name'];
        $photo_content = file_get_contents($photo_tmp_name);
        $sql_update = "UPDATE Animal SET prenom_animal = ?, url_photo = ? WHERE id_animal = ?";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param("ssi", $nom_animal, $photo_content, $id_animal);
    } else {
        $sql_update = "UPDATE Animal SET prenom_animal = ? WHERE id_animal = ?";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param("si", $nom_animal, $id_animal);
    }
    $stmt_update->execute();
    $stmt_update->close();
    $message = "Animal mis à jour avec succès !";
}

// Gérer la suppression des animaux
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_animal'])) {
    $id_animal = $_POST['id_animal'];
    $sql_delete = "DELETE FROM Animal WHERE id_animal = ?";
    $stmt_delete = $conn->prepare($sql_delete);
    $stmt_delete->bind_param("i", $id_animal);
    $stmt_delete->execute();
    $stmt_delete->close();
    $message = "Animal supprimé avec succès !";
}

// Récupérer les informations utilisateur
$sql_user = "SELECT nom_utilisateur, profile_picture FROM creation_compte WHERE id = ?";
$stmt_user = $conn->prepare($sql_user);
$stmt_user->bind_param("i", $user_id);
$stmt_user->execute();
$stmt_user->bind_result($nom_utilisateur, $profile_picture);
$stmt_user->fetch();
$stmt_user->close();

// Récupérer les animaux de l'utilisateur
$sql_animaux = "SELECT id_animal, prenom_animal, url_photo FROM Animal WHERE id_utilisateur = ?";
$stmt_animaux = $conn->prepare($sql_animaux);
$stmt_animaux->bind_param("i", $user_id);
$stmt_animaux->execute();
$result_animaux = $stmt_animaux->get_result();
$stmt_animaux->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Profil Public - Gardien des Animaux</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            color: #333;
            background: url('images/premierplan.png') no-repeat center center fixed;
            background-size: cover;
            margin: 0;
            padding: 0;
        }

        header {
            padding: 20px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: rgba(255, 255, 255, 0.9);
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        header img {
            height: 80px;
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
            transition: all 0.3s ease;
        }

        header .auth-buttons .btn:hover {
            background-color: #ff7f00;
        }

        .profile-container {
            max-width: 1200px;
            margin: 50px auto;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            padding: 30px 40px;
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
            text-align: center;
        }

        .profile-picture img {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            border: 4px solid orange;
            object-fit: cover;
            margin-bottom: 20px;
        }

        .profile-details {
            font-size: 1.2em;
            margin-bottom: 30px;
        }

        .profile-details span {
            font-weight: bold;
            color: #333;
        }

        h3 {
            color: orange;
            margin-top: 30px;
        }

        .alert-message {
            margin: 20px 0;
            padding: 15px;
            background-color: #f1f1f1;
            border-left: 5px solid orange;
            font-size: 1em;
            text-align: left;
        }

        form .profile-item {
            margin-bottom: 15px;
            text-align: left;
        }

        form input[type="text"], form input[type="number"], form input[type="file"] {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        form button {
            margin-top: 15px;
            padding: 10px 15px;
            background-color: orange;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1em;
            transition: background-color 0.3s ease;
        }

        form button:hover {
            background-color: #ff7f00;
        }

        .animal-list {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 30px;
        }

        .animal-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            padding: 20px;
        }

        .animal-card img {
            width: 100%;
            height: 150px;
            object-fit: cover;
            border-radius: 10px;
            margin-bottom: 15px;
        }

        .animal-card button {
            margin: 5px;
            padding: 10px 15px;
            background-color: orange;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 0.9em;
            transition: all 0.3s ease;
        }

        .animal-card button:hover {
            background-color: #ff7f00;
        }

        .animal-card .btn-delete {
            background: red;
        }

        .animal-card .btn-delete:hover {
            background: darkred;
        }

        footer {
            background: rgba(0, 0, 0, 0.9);
            color: white;
            padding: 20px;
            margin-top: 50px;
            text-align: center;
        }

        footer .footer-links {
            display: flex;
            justify-content: center;
            gap: 40px;
            flex-wrap: wrap;
        }

        footer .footer-links a {
            color: white;
            text-decoration: none;
            font-size: 0.9em;
        }

        footer .footer-links a:hover {
            color: orange;
        }
    </style>
</head>
<body>
<header>
    <img src="images/logo.png" alt="Logo Gardien des Animaux">
    <div class="auth-buttons">
        <button class="btn" onclick="window.location.href='index_connect.php'">Accueil</button>
        <button class="btn" onclick="window.location.href='profil.php'">Mon Profil</button>
    </div>
</header>
<div class="profile-container">
    <h2>Mon Profil Public</h2>
    <div class="profile-picture">
        <img id="profile-img" src="display_image.php" alt="Photo de profil">
    </div>
    <div class="profile-details">
        <p><strong>Nom d'utilisateur :</strong> <?php echo htmlspecialchars($nom_utilisateur); ?></p>
    </div>
    <?php if (isset($message)): ?>
        <div class="alert-message"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>
    <h3>Ajouter des animaux</h3>
    <form method="POST" enctype="multipart/form-data">
        <div class="profile-item">
            <label for="nombre_animal">Nombre d'animaux :</label>
            <input type="number" id="nombre_animal" name="nombre_animal" min="1" required>
        </div>
        <div id="animal-fields"></div>
        <button type="submit" name="ajouter_animaux" class="btn">Enregistrer les animaux</button>
    </form>
    <h3>Mes Animaux</h3>
    <div class="animal-list">
        <?php while ($row = $result_animaux->fetch_assoc()): ?>
            <div class="animal-card">
                <form method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="id_animal" value="<?php echo $row['id_animal']; ?>">
                    <label>Nom :</label>
                    <input type="text" name="nom_animal" value="<?php echo htmlspecialchars($row['prenom_animal']); ?>" required>
                    <div class="animal-photo">
                        <?php if ($row['url_photo']): ?>
                            <img src="data:image/jpeg;base64,<?php echo base64_encode($row['url_photo']); ?>" alt="Photo de <?php echo htmlspecialchars($row['prenom_animal']); ?>">
                        <?php else: ?>
                            <p>Aucune photo disponible</p>
                        <?php endif; ?>
                    </div>
                    <button type="submit" name="update_animal">Modifier</button>
                    <button type="submit" name="delete_animal" class="btn-delete">Supprimer</button>
                </form>
            </div>
        <?php endwhile; ?>
    </div>
</div>
<footer>
    <div class="footer-links">
        <div>
            <h4>En savoir plus :</h4>
            <ul>
                <li><a href="securite_connect.php">Sécurité</a></li>
                <li><a href="aide_connect.php">Centre d'aide</a></li>
            </ul>
        </div>
        <div>
            <h4>A propos de nous :</h4>
            <ul>
                <li><a href="confidentialite_connect.php">Politique de confidentialité</a></li>
                <li><a href="contact_connect.php">Nous contacter</a></li>
            </ul>
        </div>
        <div>
            <h4>Conditions Générales :</h4>
            <ul>
                <li><a href="conditions_connect.php">Conditions d'utilisateur et de Service</a></li>
            </ul>
        </div>
    </div>
</footer>

<script>
    document.getElementById('nombre_animal').addEventListener('input', function() {
        const container = document.getElementById('animal-fields');
        container.innerHTML = '';
        const count = parseInt(this.value, 10) || 0;
        for (let i = 1; i <= count; i++) {
            const div = document.createElement('div');
            div.innerHTML = `
                <label>Nom de l'animal ${i} :</label>
                <input type="text" name="nom_animal[]" required>
                <label>Photo de l'animal ${i} :</label>
                <input type="file" name="photo_animal[]" accept="image/*" required>
            `;
            container.appendChild(div);
        }
    });
</script>
</body>
</html>
