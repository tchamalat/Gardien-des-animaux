<?php 
session_start(); 

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include 'config.php';

$user_id = $_SESSION['user_id'];

// Récupérer les informations de l'utilisateur
$sql_user = "SELECT nom_utilisateur FROM creation_compte WHERE id = ?";
$stmt_user = $conn->prepare($sql_user);
$stmt_user->bind_param("i", $user_id);
$stmt_user->execute();
$stmt_user->bind_result($nom_utilisateur);
$stmt_user->fetch();
$stmt_user->close();

// Récupérer les informations des animaux
$sql_animaux = "SELECT id, nom_animal, photo_animal FROM animaux WHERE user_id = ?";
$stmt_animaux = $conn->prepare($sql_animaux);
$stmt_animaux->bind_param("i", $user_id);
$stmt_animaux->execute();
$result_animaux = $stmt_animaux->get_result();
$animaux = $result_animaux->fetch_all(MYSQLI_ASSOC);
$stmt_animaux->close();

$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Public - Gardien des Animaux</title>
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
    <h2 class="profile-title">Profil Public de <?php echo htmlspecialchars($nom_utilisateur); ?> :</h2>

    <div class="animal-section">
        <h3>Nombre d'animaux : <?php echo count($animaux); ?></h3>

        <?php foreach ($animaux as $animal): ?>
            <div class="animal-card">
                <div class="animal-photo">
                    <img src="display_animal_image.php?animal_id=<?php echo $animal['id']; ?>" alt="Photo de <?php echo htmlspecialchars($animal['nom_animal']); ?>">
                </div>
                <div class="animal-details">
                    <span class="animal-name"><?php echo htmlspecialchars($animal['nom_animal']); ?></span>
                </div>

                <form action="upload_animal_image.php" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="animal_id" value="<?php echo $animal['id']; ?>">
                    <input type="file" name="animalPhoto" accept="image/*" required>
                    <button type="submit" class="btn-photo">Changer la photo de l'animal</button>
                </form>
            </div>
        <?php endforeach; ?>

    </div>

    <div class="profile-actions">
        <button class="btn-action" onclick="window.location.href='profil.php'">Retour à Mon Profil</button>
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

</body>
</html>
