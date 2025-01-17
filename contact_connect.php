<?php
session_start();
$confirmationMessage = '';
if (isset($_SESSION['confirmation_message'])) {
    $confirmationMessage = $_SESSION['confirmation_message'];
    unset($_SESSION['confirmation_message']); // Supprimer le message après affichage
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nous contacter - Gardien des Animaux</title>
    <style>
        /* Styles globaux */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            color: #333;
            background: url('images/premierplan.png') no-repeat center center fixed;
            background-size: cover;
        }

        header {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 10;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 20px;
            background: transparent;
            box-shadow: none;
        }

        header img {
            height: 150px;
            max-width: 170px;
        }

        header .header-slogan {
            font-size: 1.5em;
            color: orange;
            font-weight: bold;
            text-align: center;
            flex: 1;
            transition: opacity 0.5s ease, transform 0.5s ease;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.8);
        }

        header.scrolled .header-slogan {
            opacity: 0;
            transform: translateY(-20px);
        }

        header .auth-buttons {
            display: flex;
            gap: 15px;
        }

        header .auth-buttons .btn {
            background-color: orange;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            font-size: 1em;
            cursor: pointer;
            text-decoration: none;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        header .auth-buttons .btn:hover {
            background-color: #ff7f00;
            transform: translateY(-3px);
        }

        .contact-container {
            max-width: 800px;
            margin: 150px auto;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
        }

        .contact-container h2 {
            font-size: 1.8em;
            color: orange;
            text-align: center;
            margin-bottom: 20px;
        }

        .contact-container p {
            font-size: 1.1em;
            line-height: 1.6;
            color: #555;
            margin-bottom: 20px;
        }

        .contact-form .form-group {
            margin-bottom: 15px;
        }

        .contact-form label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .contact-form input,
        .contact-form textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1em;
        }

        .contact-form textarea {
            resize: none;
        }

        .contact-form .btn {
            background-color: orange;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            font-size: 1em;
            cursor: pointer;
            text-decoration: none;
            transition: background-color 0.3s ease, transform 0.3s ease;
            width: 100%;
        }

        .contact-form .btn:hover {
            background-color: #ff7f00;
            transform: translateY(-3px);
        }

        address {
            font-style: normal;
            color: #555;
            margin-top: 10px;
        }

        footer {
            background: rgba(0, 0, 0, 0.85);
            color: #fff;
            padding: 20px;
            margin-top: 50px;
        }

        footer .footer-links {
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
        }

        footer .footer-links h4 {
            color: orange;
            margin-bottom: 10px;
        }

        footer .footer-links ul {
            list-style: none;
            padding: 0;
        }

        footer .footer-links a {
            color: white;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        footer .footer-links a:hover {
            color: orange;
        }
    </style>
    <script>
        window.addEventListener('scroll', () => {
            const header = document.querySelector('header');
            if (window.scrollY > 50) {
                header.classList.add('scrolled');
            } else {
                header.classList.remove('scrolled');
            }
        });
    </script>
</head>
<body>

<!-- Header -->
<header>
    <img src="images/logo.png" alt="Logo Gardien des Animaux">
    <h1 class="header-slogan">Une question ? Contactez-nous !</h1>
    <div class="auth-buttons">
        <button class="btn" onclick="window.location.href='index_connect.php'">Accueil</button>
    </div>
</header>

<!-- Section Nous Contacter -->
<div class="contact-container">
    <h2>Contactez-nous</h2>

    <!-- Afficher le message de confirmation -->
    <?php if (!empty($confirmationMessage)): ?>
        <div style="color: green; border: 1px solid green; padding: 10px; margin-bottom: 20px;">
            <?= htmlspecialchars($confirmationMessage); ?>
        </div>
    <?php endif; ?>

    <p>
        Vous avez une question, une suggestion ou besoin d’aide ? Notre équipe est à votre disposition pour répondre à toutes vos demandes. Voici comment nous joindre :
    </p>

    <h2>1. Par email</h2>
    <p>
        Envoyez-nous un email à : <a href="mailto:gardien-des-animaux@gmail.com">gardien-des-animaux@gmail.com</a>. Nous nous efforçons de répondre sous 24 à 48 heures.
    </p>

    <h2>2. Par téléphone</h2>
    <p>
        Appelez-nous au : <strong>+33 6 69 37 31 14</strong>. Nous sommes disponibles du lundi au vendredi, de 9h à 18h.
    </p>

    <h2>3. Formulaire de contact</h2>
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
