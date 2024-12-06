<?php
include 'config.php'; // Connexion à la base de données
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nous contacter - Gardien des Animaux</title>
    <link rel="stylesheet" href="/CSS/styles.css">
</head>
<body>

<!-- Header -->
<header>
    <div class="header-container">
        <img src="images/logo.png" alt="Logo Gardien des Animaux">
        <h1 class="header-slogan">Une question ? Contactez-nous !</h1>
        <div class="auth-buttons">
            <button class="btn" onclick="window.location.href='/index.php'">Accueil</button>
        </div>
    </div>
</header>

<!-- Section Nous Contacter -->
<div class="contact-container">
    <h2>Contactez-nous</h2>
    <p>
        Vous avez une question, une suggestion ou besoin d’aide ? Notre équipe est à votre disposition pour répondre à toutes vos demandes. Voici comment nous joindre :
    </p>

    <h2>1. Par email</h2>
    <p>
        Envoyez-nous un email à : <a href="mailto:contact@gardien-des-animaux.fr">contact@gardien-des-animaux.fr</a>. Nous nous efforçons de répondre sous 24 à 48 heures.
    </p>

    <h2>2. Par téléphone</h2>
    <p>
        Appelez-nous au : <strong>+33 1 23 45 67 89</strong>. Nous sommes disponibles du lundi au vendredi, de 9h à 18h.
    </p>

    <h2>3. Formulaire de contact</h2>
    <p>
        Remplissez le formulaire ci-dessous pour nous transmettre directement votre demande.
    </p>

    <form action="send_message.php" method="POST" class="contact-form">
        <div class="form-group">
            <label for="name">Nom :</label>
            <input type="text" id="name" name="name" placeholder="Votre nom" required>
        </div>
        <div class="form-group">
            <label for="email">Email :</label>
            <input type="email" id="email" name="email" placeholder="Votre email" required>
        </div>
        <div class="form-group">
            <label for="message">Message :</label>
            <textarea id="message" name="message" placeholder="Votre message" rows="5" required></textarea>
        </div>
        <button type="submit" class="btn">Envoyer</button>
    </form>

    <h2>4. Notre adresse</h2>
    <p>
        Vous pouvez également nous écrire à l’adresse suivante :
    </p>
    <address>
        Gardien des Animaux<br>
        123 Rue de la Protection Animale<br>
        75001 Paris, France
    </address>
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
