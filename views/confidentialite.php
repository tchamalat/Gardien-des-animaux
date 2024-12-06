<?php
include 'config.php'; // Connexion à la base de données
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Politique de Confidentialité - Gardien des Animaux</title>
    <link rel="stylesheet" href="/CSS/styles.css">
</head>
<body>

<!-- Header -->
<header>
    <div class="header-container">
        <img src="images/logo.png" alt="Logo Gardien des Animaux">
        <h1 class="header-slogan">Votre vie privée, notre priorité</h1>
        <div class="auth-buttons">
            <button class="btn" onclick="window.location.href='/index.php'">Accueil</button>
        </div>
    </div>
</header>

<!-- Section Politique de Confidentialité -->
<div class="privacy-container">
    <h2>Politique de Confidentialité</h2>
    <p>
        Chez <strong>Gardien-des-animaux.fr</strong>, nous prenons la protection de vos données personnelles très au sérieux. Cette politique explique comment nous collectons, utilisons et protégeons vos informations dans le cadre de nos services.
    </p>

    <h2>1. Les informations que nous collectons</h2>
    <p>
        Nous collectons uniquement les informations nécessaires pour fournir et améliorer nos services, notamment :
    </p>
    <ul>
        <li><strong>Informations personnelles :</strong> Nom, adresse email, numéro de téléphone, adresse.</li>
        <li><strong>Informations sur vos animaux :</strong> Type, race, besoins spécifiques.</li>
        <li><strong>Informations d’utilisation :</strong> Historique de navigation, recherches sur la plateforme.</li>
    </ul>

    <h2>2. Utilisation de vos données</h2>
    <p>
        Nous utilisons vos données pour :
    </p>
    <ul>
        <li>Gérer votre compte utilisateur et vos réservations.</li>
        <li>Mettre en relation les propriétaires d’animaux avec les gardiens.</li>
        <li>Améliorer nos services et personnaliser votre expérience sur la plateforme.</li>
        <li>Envoyer des communications importantes, comme des confirmations de réservation ou des mises à jour sur nos conditions.</li>
    </ul>

    <h2>3. Partage de vos informations</h2>
    <p>
        Nous ne partageons jamais vos données personnelles avec des tiers sans votre consentement explicite, sauf dans les cas suivants :
    </p>
    <ul>
        <li>Pour respecter une obligation légale ou une demande des autorités.</li>
        <li>Pour protéger nos droits ou prévenir des activités frauduleuses.</li>
    </ul>

    <h2>4. Conservation des données</h2>
    <p>
        Vos informations personnelles sont conservées uniquement pendant la durée nécessaire à la réalisation des services pour lesquels elles ont été collectées, sauf si une durée de conservation plus longue est requise ou permise par la loi.
    </p>

    <h2>5. Vos droits</h2>
    <p>
        Conformément au RGPD, vous disposez des droits suivants concernant vos données personnelles :
    </p>
    <ul>
        <li><strong>Droit d'accès :</strong> Vous pouvez demander une copie de vos données personnelles.</li>
        <li><strong>Droit de rectification :</strong> Vous pouvez demander la correction de données incorrectes ou incomplètes.</li>
        <li><strong>Droit de suppression :</strong> Vous pouvez demander la suppression de vos données, sauf si leur conservation est nécessaire pour des raisons légales.</li>
        <li><strong>Droit d'opposition :</strong> Vous pouvez refuser l’utilisation de vos données à des fins de marketing.</li>
        <li><strong>Droit à la portabilité :</strong> Vous pouvez demander que vos données soient transférées à un autre prestataire.</li>
    </ul>

    <h2>6. Sécurité de vos informations</h2>
    <p>
        Nous mettons en œuvre des mesures de sécurité techniques et organisationnelles pour protéger vos données contre tout accès non autorisé, perte ou divulgation. 
        Cependant, aucune méthode de transmission ou de stockage des données n’est totalement sécurisée, et nous ne pouvons garantir une sécurité absolue.
    </p>

    <h2>7. Cookies</h2>
    <p>
        Notre site utilise des cookies pour améliorer votre expérience. Vous pouvez gérer vos préférences de cookies dans les paramètres de votre navigateur.
    </p>

    <h2>8. Contact</h2>
    <p>
        Pour toute question ou demande concernant vos données personnelles, contactez-nous :
    </p>
    <ul>
        <li><strong>Email :</strong> <a href="mailto:contact@gardien-des-animaux.fr">contact@gardien-des-animaux.fr</a></li>
        <li><strong>Téléphone :</strong> +33 1 23 45 67 89 (du lundi au vendredi, de 9h à 18h).</li>
    </ul>
    <p>
        Nous nous engageons à répondre à vos demandes dans les meilleurs délais.
    </p>
</div>

<!-- Footer -->
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
