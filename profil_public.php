<?php 
session_start(); 

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include 'config.php';

$user_id = $_SESSION['user_id'];

// Récupérer les informations de l'utilisateur
$sql_user = "SELECT nom_utilisateur, profile_picture FROM creation_compte WHERE id = ?";
$stmt_user = $conn->prepare($sql_user);
$stmt_user->bind_param("i", $user_id);
$stmt_user->execute();
$stmt_user->bind_result($nom_utilisateur, $profile_picture);
$stmt_user->fetch();
$stmt_user->close();

// Récupérer les animaux de l'utilisateur
$sql_animaux = "SELECT prenom_animal, url_photo FROM Animal WHERE id_utilisateur = ?";
$stmt_animaux = $conn->prepare($sql_animaux);
$stmt_animaux->bind_param("i", $user_id);
$stmt_animaux->execute();
$result_animaux = $stmt_animaux->get_result();
$stmt_animaux->close();

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
        <div class="profile-details">
            <div class="profile-item">
                <label>Nom d'utilisateur :</label>
                <span class="profile-value"><?php echo htmlspecialchars($nom_utilisateur); ?></span>
            </div>
        </div>
    </div>

    <h3>Ajouter des Animaux</h3>
    <form action="update_animals.php" method="POST" enctype="multipart/form-data">
        <div class="profile-item">
            <label for="nombre_animal">Nombre d'animaux :</label>
            <input type="number" id="nombre_animal" name="nombre_animal" min="1" required>
        </div>
        
        <div class="profile-item" id="animal-details-container">
            <label>Détails des animaux :</label>
            <div id="animal-fields"></div>
        </div>

        <button type="submit" class="btn">Enregistrer les animaux</button>
    </form>

    <h3>Mes Animaux</h3>
    <div class="animal-list">
        <?php while ($row = $result_animaux->fetch_assoc()): ?>
            <div class="animal-card">
                <p><strong>Nom :</strong> <?php echo htmlspecialchars($row['prenom_animal']); ?></p>
                <div class="animal-photo">
                    <?php if ($row['url_photo']): ?>
                        <img src="data:image/jpeg;base64,<?php echo base64_encode($row['url_photo']); ?>" alt="Photo de <?php echo htmlspecialchars($row['prenom_animal']); ?>">
                    <?php else: ?>
                        <p>Aucune photo disponible</p>
                    <?php endif; ?>
                </div>
            </div>
        <?php endwhile; ?>
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
document.getElementById('nombre_animal').addEventListener('input', function() {
    const container = document.getElementById('animal-fields');
    container.innerHTML = ''; // Effacer les champs précédents
    const count = parseInt(this.value, 10) || 0;

    for (let i = 1; i <= count; i++) {
        const div = document.createElement('div');
        div.className = 'animal-entry';
        
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
