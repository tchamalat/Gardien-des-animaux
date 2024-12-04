<?php
include 'config.php'; // Connexion à la base de données
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sécurité - Gardien des Animaux</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<!-- Header -->
<header>
    <div class="header-container">
        <img src="images/logo.png" alt="Logo Gardien des Animaux">
        <h1 class="header-slogan">Votre sécurité, notre priorité</h1>
        <div class="auth-buttons">
            <button class="btn" onclick="window.location.href='index_connect.php'">Accueil</button>
        </div>
    </div>
</header>

<!-- Section Sécurité -->
<div class="security-container">
    <h2>Sécurité sur Gardien des Animaux</h2>
    <p>
        Chez <strong>Gardien-des-animaux.fr</strong>, nous comprenons que la sécurité de vos animaux et de vos données est essentielle. Voici comment nous garantissons une expérience sûre et fiable pour tous nos utilisateurs.
    </p>

    <h2>1. Vérification des profils</h2>
    <p>
        Tous les profils des gardiens sont soumis à une vérification rigoureuse pour garantir leur authenticité. Nous vérifions :
    </p>
    <ul>
        <li>Les informations personnelles fournies par les utilisateurs.</li>
        <li>Les avis et évaluations des propriétaires précédents.</li>
    </ul>
    <p>
        Cela permet de créer un environnement de confiance où vous pouvez trouver des gardiens fiables pour vos animaux.
    </p>

    <h2>2. Communication sécurisée</h2>
    <p>
        Notre plateforme offre un espace de messagerie sécurisé, vous permettant de discuter et de clarifier tous les détails avec votre gardien avant de confirmer une réservation. Nous encourageons les utilisateurs à :
    </p>
    <ul>
        <li>Poser toutes les questions nécessaires sur les services proposés.</li>
        <li>Clarifier les attentes concernant les soins des animaux.</li>
    </ul>

    <h2>3. Paiements protégés</h2>
    <p>
        Toutes les transactions effectuées via notre plateforme sont sécurisées grâce à des systèmes de paiement certifiés. Nous utilisons :
    </p>
    <ul>
        <li>Des connexions cryptées pour protéger vos informations bancaires.</li>
        <li>Un suivi transparent pour chaque paiement, accompagné d’un reçu numérique.</li>
    </ul>

    <h2>4. Protection des données personnelles</h2>
    <p>
        Vos informations personnelles sont stockées de manière sécurisée et ne sont jamais partagées avec des tiers sans votre consentement explicite. Nous respectons les normes strictes de protection des données, conformément au RGPD.
    </p>

    <h2>5. Assurance et assistance</h2>
    <p>
        Bien que nous agissions comme intermédiaire, nous vous recommandons d’avoir une assurance adaptée pour vos animaux. En cas de problème ou de litige, notre équipe d’assistance est disponible pour vous aider et trouver une solution.
    </p>

    <p>
        <strong>Gardien-des-animaux.fr</strong> s'engage à continuer d'améliorer nos mesures de sécurité pour garantir votre tranquillité d'esprit.
    </p>

    <h3>Besoin d'aide ?</h3>
    <p>
        Si vous avez des questions ou des inquiétudes concernant la sécurité, n'hésitez pas à nous contacter à : 
        <a href="mailto:contact@gardien-des-animaux.fr">contact@gardien-des-animaux.fr</a>.
    </p>
</div>

<!-- Footer -->
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
