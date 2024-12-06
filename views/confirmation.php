<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmation d'inscription</title>
    <link rel="stylesheet" href="/CSS/styles.css">
</head>
<body>
    <header>
        <div class="header-container">
            <img src="images/logo.png" alt="Logo Gardien des Animaux">
            <h1>Inscription Réussie</h1>
        </div>
    </header>

    <div class="form-container">
        <h2>Merci pour votre inscription !</h2>
        <p>Votre compte a été créé avec succès. Vous pouvez maintenant vous connecter.</p>
        <button class="btn" onclick="window.location.href='/views/login.html'">Se connecter</button>
    </div>

    <footer>
    <div class="footer-links">
        <div>
            <h4>En savoir plus :</h4>
            <ul>
                <li><a href="/views/securite.php">Sécurité</a></li>
                <li><a href="/views/aide.php">Centre d'aide</a></li>
            </ul>
        </div>
        <div>
            <h4>A propos de nous :</h4>
            <ul>
                <li><a href="/views/confidentialite.php">Politique de confidentialité</a></li>
                <li><a href="/views/contact.php">Nous contacter</a></li>
            </ul>
        </div>
        <div>
            <h4>Conditions Générales :</h4>
            <ul>
                <li><a href="/views/conditions.php">Conditions d'utilisateur et de Service</a></li>
            </ul>
        </div>
    </div>
</footer>
</body>
</html>
