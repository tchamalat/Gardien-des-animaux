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

        <form action="update_profile_public.php" method="POST">
            <div class="profile-details">
                <div class="profile-item">
                    <label>Nom d'utilisateur :</label>
                    <span class="profile-value"><?php echo htmlspecialchars($nom_utilisateur); ?></span>
                </div>
                <div class="profile-item">
                    <label for="type_animal">Type d'animal :</label>
                    <input type="text" id="type_animal" name="type_animal" value="<?= htmlspecialchars($type_animal) ?>" required>
                </div>
                <div class="profile-item">
                    <label for="nombre_animal">Nombre d'animaux :</label>
                    <input type="number" id="nombre_animal" name="nombre_animal" value="<?php echo htmlspecialchars($nombre_animal ?? ''); ?>" min="1" required>
                </div>
                
                <div class="profile-item" id="animal-names-container">
                    <label>Noms des animaux :</label>
                    <div id="animal-names-fields"></div>
                </div>
            </div>

            <button type="submit" class="btn">Enregistrer les modifications</button>
        </form>
    </div>
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

// Générer dynamiquement les champs pour les noms des animaux
document.getElementById('nombre_animal').addEventListener('input', function() {
    const container = document.getElementById('animal-names-fields');
    container.innerHTML = ''; // Effacer les champs précédents
    const count = parseInt(this.value, 10) || 0;

    for (let i = 1; i <= count; i++) {
        const input = document.createElement('input');
        input.type = 'text';
        input.name = 'nom_animal[]';
        input.placeholder = 'Nom de l\'animal ' + i;
        input.required = true;
        container.appendChild(input);
    }
});

// Pour charger les champs au chargement de la page si un nombre est déjà défini
document.addEventListener('DOMContentLoaded', function() {
    const event = new Event('input');
    document.getElementById('nombre_animal').dispatchEvent(event);
});
</script>

</body>
</html>
