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
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nom_animal'])) {
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

    if (!empty($_FILES['photo_animal']['tmp_name']) && $_FILES['photo_animal']['error'] === UPLOAD_ERR_OK) {
        $photo_tmp_name = $_FILES['photo_animal']['tmp_name'];
        $photo_content = file_get_contents($photo_tmp_name);
    }

    if ($photo_content) {
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
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<header>
    <div class="header-container">
        <img src="images/logo.png" alt="Logo Gardien des Animaux">
        <h1 class="header-slogan">Un foyer chaleureux même en votre absence</h1>
        <div class="auth-buttons">
            <button class="btn" onclick="window.location.href='logout.php'">Déconnexion</button> 
            <button class="btn" onclick="window.location.href='index_connect.php'">Accueil</button>
        </div>
    </div>
</header>

<div class="profile-container">
    <h2 class="profile-title">Mon Profil Public :</h2>

    <div class="profile-info">
        <div class="profile-picture">
            <img id="profile-img" src="display_image.php" alt="Photo de profil">
        </div>
        <div class="profile-details">
            <div class="profile-item">
                <label>Nom d'utilisateur :</label>
                <span class="profile-value"><?php echo htmlspecialchars($nom_utilisateur); ?></span>
            </div>
        </div>
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
        
        <div class="profile-item" id="animal-details-container">
            <label>Détails des animaux :</label>
            <div id="animal-fields"></div>
        </div>

        <button type="submit" name="ajouter_animaux" class="btn">Enregistrer les animaux</button>
    </form>

    <h3>Mes Animaux</h3>
    <div id="animal-list" class="animal-list">
        <?php while ($row = $result_animaux->fetch_assoc()): ?>
            <div class="animal-card">
                <form method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="id_animal" value="<?php echo $row['id_animal']; ?>">

                    <label for="nom_animal_<?php echo $row['id_animal']; ?>">Nom :</label>
                    <input type="text" id="nom_animal_<?php echo $row['id_animal']; ?>" name="nom_animal" value="<?php echo htmlspecialchars($row['prenom_animal']); ?>" required>

                    <label for="photo_animal_<?php echo $row['id_animal']; ?>">Photo :</label>
                    <input type="file" id="photo_animal_<?php echo $row['id_animal']; ?>" name="photo_animal" accept="image/*">

                    <div class="animal-photo">
                        <?php if ($row['url_photo']): ?>
                            <img src="data:image/jpeg;base64,<?php echo base64_encode($row['url_photo']); ?>" alt="Photo de <?php echo htmlspecialchars($row['prenom_animal']); ?>">
                        <?php else: ?>
                            <p>Aucune photo disponible</p>
                        <?php endif; ?>
                    </div>

                    <button type="submit" name="update_animal" class="btn">Modifier</button>
                </form>
            </div>
        <?php endwhile; ?>
    </div>
</div>

<div class="profile-actions">
    <button class="btn-action" onclick="window.location.href='profil.php'">MON PROFIL</button>
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
        div.className = 'animal-entry';
        div.innerHTML = 
            <label>Nom de l'animal ${i} :</label>
            <input type="text" name="nom_animal[]" required>
            <label>Photo de l'animal ${i} :</label>
            <input type="file" name="photo_animal[]" accept="image/*" required>
        ;
        container.appendChild(div);
    }
});
</script>

</body>
</html>
