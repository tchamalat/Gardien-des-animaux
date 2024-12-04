<?php
session_start();
include 'config.php'; // Assure que la configuration pour la base de données est incluse

// Vérifiez si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Récupérez le rôle de l'utilisateur à partir de la base de données
$sql = "SELECT role FROM creation_compte WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($user) {
    $role = $user['role'];
} else {
    // Si l'utilisateur n'existe pas, redirigez vers la page de connexion
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmation de Connexion</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <div class="header-container">
            <img src="images/logo.png" alt="Logo Gardien des Animaux">
            <h1>Connexion Réussie</h1>
        </div>
    </header>

    <div class="form-container">
        <h2>Bienvenue !</h2>
        <p>Votre connexion a été effectuée avec succès. Vous pouvez maintenant accéder à votre profil.</p>
        <button class="btn" onclick="window.location.href='<?php echo $role == 0 ? 'index_connect_gardien.php' : 'index_connect.php'; ?>'">Aller à la page d'accueil</button>
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
