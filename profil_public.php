<?php 
session_start(); 

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include 'config.php';

$user_id = $_SESSION['user_id'];

// Récupérer les informations de l'utilisateur
$sql = "SELECT nom_utilisateur, profile_picture, type_animal, nombre_animal FROM creation_compte WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($nom_utilisateur, $profile_picture, $type_animal, $nombre_animal);
$stmt->fetch();
$stmt->close();
$conn->close();

$message = isset($_SESSION['message']) ? $_SESSION['message'] : '';
unset($_SESSION['message']);
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

    <?php if ($message): ?>
        <div class="alert-message">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>

    <h2 class="profile-title">Mon Profil Public :</h2>

    <div class="profile-info">
        <div class="profile-picture">
            <img id="profile-img" src="display_image.php" alt="Photo de profil">
        </div>

        <form action="upload_image.php" method="POST" enctype="multipart/form-data" class="profile-form">
            <input type="file" id="profile-picture-input" name="profilePicture" accept="image/*" style="display: none;" onchange="previewProfileImage(event)">
            <button type="button" class="btn-photo" onclick="document.getElementById('profile-picture-input').click();">Changer la photo de profil</button>
            <button type="submit" class="btn-photo">Enregistrer la nouvelle photo</button>
        </form>

        <div class="profile-details">
            <div class="profile-item">
                <label>Nom d'utilisateur :</label>
                <span class="profile-value"><?php echo htmlspecialchars($nom_utilisateur); ?></span>
            </div>
            <div class="profile-item">
                <label>Type d'animal :</label>
                <span class="profile-value"><?php echo htmlspecialchars($type_animal ?? 'Non renseigné'); ?></span>
            </div>
            <div class="profile-item">
                <label>Nombre d'animaux :</label>
                <span class="profile-value"><?php echo htmlspecialchars($nombre_animal ?? 'Non renseigné'); ?></span>
            </div>
        </div>
    </div>

    <form action="profil_gardien.php" method="POST">
        <button class="btn-action" type="submit">Modifier les informations d'animaux</button>
    </form>

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

<script>
function previewProfileImage(event) {
    const reader = new FileReader();
    const imgElement = document.getElementById('profile-img');

    reader.onload = function(){
        if (reader.readyState === 2) {
            imgElement.src = reader.result; 
        }
    }
    
    reader.readAsDataURL(event.target.files[0]);
}
</script>

</body>
</html>
