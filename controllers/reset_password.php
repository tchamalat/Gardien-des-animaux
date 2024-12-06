<?php
// Définir le fuseau horaire pour éviter les problèmes avec NOW()
date_default_timezone_set('Europe/Paris'); 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer le token et le nouveau mot de passe
    $token = $_POST['token'];
    $new_password = md5($_POST['new_password']);

    require 'config.php';

    // Vérifier la validité du token
    $query = $conn->prepare('SELECT * FROM creation_compte WHERE reset_token = ? AND token_expiration > NOW()');
    $query->bind_param('s', $token);
    $query->execute();
    $result = $query->get_result();

    if ($result->num_rows > 0) {
        // Token valide : continuer avec la mise à jour
        $query = $conn->prepare('UPDATE creation_compte SET mot_de_passe = ?, reset_token = NULL, token_expiration = NULL WHERE reset_token = ?');
        $query->bind_param('ss', $new_password, $token);
        if ($query->execute()) {
            $message = "Votre mot de passe a été mis à jour.";
        } else {
            $message = "Erreur lors de la mise à jour du mot de passe.";
        }
    } else {
        // Token invalide ou expiré
        $message = "Le lien de réinitialisation est invalide ou a expiré.";
    }
} else if (isset($_GET['token'])) {
    // Récupérer le token depuis l'URL
    $token = $_GET['token'];
} else {
    // Aucun token fourni
    $message = "Aucun token fourni.";
    $token = null;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réinitialiser le mot de passe - Gardien des Animaux</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <div class="header-container">
            <img src="images/logo.png" alt="Logo Gardien des Animaux">
            <h1 class="header-slogan">Un foyer chaleureux même en votre absence</h1>
            <div class="auth-buttons">
                <button class="btn" onclick="window.location.href='index.php'">Accueil</button>
            </div>
        </div>
    </header>

    <div class="form-container">
        <h2>Réinitialisation de mot de passe</h2>
        <?php if (isset($message)): ?>
            <p class="message"><?php echo htmlspecialchars($message); ?></p>
        <?php endif; ?>

        <?php if (isset($token)): ?>
            <p>Veuillez saisir un nouveau mot de passe pour votre compte.</p>
            <form action="reset_password.php" method="POST">
                <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
                <div class="form-group">
                    <label for="new_password">Nouveau mot de passe :</label>
                    <input type="password" name="new_password" id="new_password" required>
                </div>
                <button type="submit" class="btn">Mettre à jour</button>
            </form>
        <?php endif; ?>
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
