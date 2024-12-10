<?php 
session_start(); 

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include 'config.php';

$user_id = $_SESSION['user_id'];

$sql = "SELECT nom_utilisateur, nom, prenom, mail, numero_telephone, adresse, ville, profile_picture FROM creation_compte WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($nom_utilisateur, $nom, $prenom, $mail, $numero_telephone, $adresse, $ville, $profile_picture);
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
    <title>Mon Profil - Gardien des Animaux</title>
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

    <h2 class="profile-title">Mon profil :</h2>
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
                <label>Nom :</label>
                <span class="profile-value"><?php echo htmlspecialchars($nom); ?></span>
            </div>
            <div class="profile-item">
                <label>Prénom :</label>
                <span class="profile-value"><?php echo htmlspecialchars($prenom); ?></span>
            </div>
            <div class="profile-item">
                <label>Adresse mail :</label>
                <span class="profile-value"><?php echo htmlspecialchars($mail); ?></span>
            </div>
            <div class="profile-item">
                <label>Numéro de téléphone :</label>
                <span class="profile-value"><?php echo htmlspecialchars($numero_telephone); ?></span>
            </div>
            <div class="profile-item">
                <label>Adresse :</label>
                <span class="profile-value"><?php echo htmlspecialchars($adresse); ?></span>
            </div>
            <div class="profile-item">
                <label>Ville :</label>
                <span class="profile-value"><?php echo htmlspecialchars($ville); ?></span>
            </div>
        </div>
    </div>

    <div class="profile-actions">
        <button class="btn-action" onclick="window.location.href='historique.php'">HISTORIQUE</button>
        <button class="btn-action" onclick="window.location.href='profil_public.php'">MON PROFIL PUBLIC</button>
    </div>

    <form method="POST" action="delete_account.php">
        <button class="btn-delete-account" type="submit" name="delete_account">Supprimer mon compte</button>
    </form>
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
