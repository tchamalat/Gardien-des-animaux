<?php
include 'config.php'; // Connexion à la base de données
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Centre d'aide - Gardien des Animaux</title>
    <link rel="stylesheet" href="/CSS/styles.css">
</head>
<body>

<!-- Header -->
<header>
    <div class="header-container">
        <img src="images/logo.png" alt="Logo Gardien des Animaux">
        <h1 class="header-slogan">Besoin d'aide ? Nous sommes là pour vous.</h1>
        <div class="auth-buttons">
            <button class="btn" onclick="window.location.href='/controllers/index_connect.php'">Accueil</button>
        </div>
    </div>
</header>

<!-- Section Centre d'aide -->
<div class="help-center-container">
    <h2>Centre d'aide</h2>
    <p>
        Bienvenue dans notre centre d'aide. Retrouvez ici les réponses aux questions les plus fréquentes ou contactez-nous directement si vous avez besoin d'assistance supplémentaire.
    </p>

    <h2>Questions fréquentes</h2>
    <div class="faq">
        <h3>1. Comment créer un compte ?</h3>
        <p>
            Cliquez sur le bouton "Créer un compte" dans l'en-tête de la page d'accueil. Remplissez le formulaire d'inscription avec vos informations personnelles et validez.
        </p>

        <h3>2. Comment trouver un gardien pour mon animal ?</h3>
        <p>
            Utilisez notre moteur de recherche pour voir les gardiens disponibles près de chez vous. Consultez leurs profils, lisez les avis et contactez-les directement pour discuter de vos besoins.
        </p>

        <h3>3. Puis-je annuler une réservation ?</h3>
        <p>
            Oui, vous pouvez annuler une réservation via votre espace utilisateur. Consultez nos <a href="/views/conditions_connect.php">Conditions Générales</a> pour connaître les politiques d'annulation applicables.
        </p>

        <h3>4. Les paiements sont-ils sécurisés ?</h3>
        <p>
            Absolument. Tous les paiements effectués sur notre plateforme sont protégés par des systèmes de cryptage de pointe pour garantir la sécurité de vos informations bancaires.
        </p>

        <h3>5. Que faire en cas de problème avec un gardien ?</h3>
        <p>
            Si vous rencontrez un problème, contactez notre équipe d'assistance via l'adresse email ci-dessous. Nous ferons de notre mieux pour résoudre la situation.
        </p>
    </div>

    <h2>Contactez-nous</h2>
    <p>
        Si vous ne trouvez pas la réponse à votre question, notre équipe est à votre disposition pour vous aider. Voici comment nous contacter :
    </p>
    <ul>
        <li><strong>Email :</strong> <a href="mailto:contact@gardien-des-animaux.fr">contact@gardien-des-animaux.fr</a></li>
        <li><strong>Téléphone :</strong> +33 1 23 45 67 89 (disponible du lundi au vendredi, de 9h à 18h).</li>
        <li><strong>Formulaire de contact :</strong> <a href="/views/contact_connect.php">Cliquez ici pour accéder au formulaire</a>.</li>
    </ul>
</div>

<!-- Footer -->
<footer>
    <div class="footer-links">
        <div>
            <h4>En savoir plus :</h4>
            <ul>
                <li><a href="/views/securite_connect.php">Sécurité</a></li>
                <li><a href="/views/aide_connect.php">Centre d'aide</a></li>
            </ul>
        </div>
        <div>
            <h4>A propos de nous :</h4>
            <ul>
                <li><a href="/views/confidentialite_connect.php">Politique de confidentialité</a></li>
                <li><a href="/views/contact_connect.php">Nous contacter</a></li>
            </ul>
        </div>
        <div>
            <h4>Conditions Générales :</h4>
            <ul>
                <li><a href="/views/conditions_connect.php">Conditions d'utilisateur et de Service</a></li>
            </ul>
        </div>
    </div>
</footer>

</body>
</html>
