<?php
session_start();
include 'config.php';

// Gestion de la déconnexion
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: login.html");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord Admin</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <div class="header-container">
            <img src="images/logo.png" alt="Logo Gardien des Animaux">
            <h1 class="header-slogan">Tableau de Bord Administrateur</h1>
            <div class="auth-buttons">
                <button class="btn" onclick="window.location.href='?logout=1'">Déconnexion</button>
            </div>
        </div>
    </header>

    <div class="form-container">
        <h2>Bienvenue <?php echo htmlspecialchars($_SESSION['admin']); ?> !</h2>
        <h3>Gestion des Tables Principales</h3>
        <ul class="menu-list">
            <li><a class="btn" href="manage_abonnements.php">Gérer les Abonnements</a></li>
            <li><a class="btn" href="manage_utilisateurs.php">Gérer les Utilisateurs</a></li>
            <li><a class="btn" href="manage_reservations.php">Gérer les Réservations</a></li>
            <li><a class="btn" href="manage_avis.php">Gérer les Avis</a></li>
            <li><a class="btn" href="manage_animaux.php">Gérer les Animaux</a></li>
            <li><a class="btn" href="manage_faq.php">Gérer la FAQ</a></li>
            <li><a class="btn" href="manage_paiements.php">Gérer les Paiements</a></li>
            <li><a class="btn" href="manage_hebergements.php">Gérer les Hébergements</a></li>
        </ul>
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
