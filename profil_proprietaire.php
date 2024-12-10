<?php 
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include 'config.php';

// Vérifier si un ID de propriétaire est passé dans l'URL
if (!isset($_GET['id'])) {
    echo "ID du propriétaire non spécifié.";
    exit();
}

$proprietaire_id = $_GET['id'];

// Activer le débogage
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Récupérer les informations du propriétaire
$sql_user = "SELECT nom_utilisateur, profile_picture FROM creation_compte WHERE id = ?";
$stmt_user = $conn->prepare($sql_user);
$stmt_user->bind_param("i", $proprietaire_id);
$stmt_user->execute();
$stmt_user->bind_result($nom_utilisateur, $profile_picture);
$stmt_user->fetch();
$stmt_user->close();

// Récupérer les animaux du propriétaire
$sql_animaux = "SELECT prenom_animal, url_photo FROM Animal WHERE id_utilisateur = ?";
$stmt_animaux = $conn->prepare($sql_animaux);
$stmt_animaux->bind_param("i", $proprietaire_id);
$stmt_animaux->execute();
$result_animaux = $stmt_animaux->get_result();
$stmt_animaux->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Public du Propriétaire - Gardien des Animaux</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<header>
    <div class="header-container">
        <img src="images/logo.png" alt="Logo Gardien des Animaux">
        <h1 class="header-slogan">Un foyer chaleureux même en votre absence</h1>
        <div class="auth-buttons">
            <button class="btn" onclick="window.location.href='index_connect_gardien.php'">Accueil</button>
        </div>
    </div>
</header>

<div class="profile-container">
    <h2 class="profile-title">Profil Public du Propriétaire</h2>

    <div class="profile-info">
        <div class="profile-picture">
            <?php if ($profile_picture): ?>
                <img id="profile-img" src="data:image/jpeg;base64,<?php echo base64_encode($profile_picture); ?>" alt="Photo de profil">
            <?php else: ?>
                <img id="profile-img" src="images/default_profile.png" alt="Photo de profil par défaut">
            <?php endif; ?>
        </div>
        <div class="profile-details">
            <div class="profile-item">
                <label>Nom d'utilisateur :</label>
                <span class="profile-value"><?php echo htmlspecialchars($nom_utilisateur); ?></span>
            </div>
        </div>
    </div>

    <h3>Animaux du Propriétaire</h3>
    <div id="animal-list" class="animal-list">
        <?php if ($result_animaux->num_rows > 0): ?>
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
        <?php else: ?>
            <p class="no-animals">Aucun animal enregistré pour ce propriétaire.</p>
        <?php endif; ?>
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
