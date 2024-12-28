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

    // Si une seule photo est téléchargée, convertir en tableau
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

            if (!$stmt) {
                die("Erreur de préparation de la requête : " . $conn->error);
            }

            $stmt->bind_param("iss", $user_id, $nom_animal, $photo_content);

            if (!$stmt->execute()) {
                die("Erreur d'exécution de la requête : " . $stmt->error);
            }

            $stmt->close();
        } else {
            die("Erreur de téléchargement de la photo : " . $photos_animaux['error'][$i]);
        }
    }

    $message = "Animaux ajoutés avec succès !";
}


// Gérer la mise à jour des animaux
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_animal'])) {
    $id_animal = $_POST['id_animal'];
    $nom_animal = $_POST['nom_animal'];
    $photo_content = null;

    // Vérifie si un fichier a été téléchargé
    if (!empty($_FILES['photo_animal']['tmp_name']) && $_FILES['photo_animal']['error'] === UPLOAD_ERR_OK) {
        $photo_tmp_name = $_FILES['photo_animal']['tmp_name'];
        $photo_content = file_get_contents($photo_tmp_name);
    }

    // Mise à jour avec ou sans photo
    if ($photo_content) {
        $sql_update = "UPDATE Animal SET prenom_animal = ?, url_photo = ? WHERE id_animal = ?";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param("ssi", $nom_animal, $photo_content, $id_animal);
    } else {
        $sql_update = "UPDATE Animal SET prenom_animal = ? WHERE id_animal = ?";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param("si", $nom_animal, $id_animal);
    }

    if ($stmt_update->execute()) {
        $message = "Animal mis à jour avec succès !";
    } else {
        $message = "Erreur lors de la mise à jour : " . $stmt_update->error;
    }

    $stmt_update->close();
}

// Gérer la suppression des animaux
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_animal'])) {
    $id_animal = $_POST['id_animal'];

    $sql_delete = "DELETE FROM Animal WHERE id_animal = ?";
    $stmt_delete = $conn->prepare($sql_delete);
    $stmt_delete->bind_param("i", $id_animal);

    if ($stmt_delete->execute()) {
        $message = "Animal supprimé avec succès !";
    } else {
        $message = "Erreur lors de la suppression : " . $stmt_delete->error;
    }

    $stmt_delete->close();
}


// Récupérer les informations de l'utilisateur
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
            min-height: 100vh;
            overflow-x: hidden;
        }

        header {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 10;
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: none;
        }

        header img {
            height: 100px;
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
            margin-left: 10px;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        header .auth-buttons .btn:hover {
            background-color: #ff7f00;
            transform: translateY(-3px);
        }

        .profile-container {
            margin: 150px auto;
            max-width: 900px;
            background: rgba(255, 255, 255, 0.85);
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
            text-align: center;
        }

        .profile-container h2 {
            color: orange;
            margin-bottom: 30px;
        }

        .profile-picture img {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 15px;
        }

        .profile-details {
            margin-top: 20px;
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 20px;
        }

        .profile-details .profile-item {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 10px;
            padding: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            min-width: 250px;
        }

        .profile-details .profile-item label {
            font-weight: bold;
            color: #555;
            display: block;
            margin-bottom: 5px;
        }

        .profile-details .profile-item span {
            font-size: 1.1em;
            color: #333;
        }

        .animal-list {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .animal-card {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 10px;
            padding: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .animal-card img {
            width: 100%;
            height: 150px;
            object-fit: cover;
            border-radius: 10px;
            margin-bottom: 10px;
        }

        .animal-card button {
            background-color: orange;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 8px;
            font-size: 0.9em;
            cursor: pointer;
            text-decoration: none;
            margin: 5px;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .animal-card button:hover {
            background-color: #ff7f00;
            transform: translateY(-3px);
        }

        .btn-delete {
            background-color: red;
        }

        .btn-delete:hover {
            background-color: darkred;
        }

        footer {
            padding: 20px;
            background: rgba(0, 0, 0, 0.8);
            color: #fff;
            margin-top: 50px;
        }

        .footer-links {
            display: flex;
            justify-content: space-around;
        }

        .footer-links ul {
            list-style: none;
        }

        .footer-links a {
            color: #fff;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .footer-links a:hover {
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
        <div class="profile-item">
            <label>Nom d'utilisateur :</label>
            <span><?php echo htmlspecialchars($nom_utilisateur); ?></span>
        </div>
    </div>

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
                    <button type="submit" name="delete_animal" class="btn-delete" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet animal ?');">Supprimer</button>
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

</body>
</html>
