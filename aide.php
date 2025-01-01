<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Centre d'aide - Gardien des Animaux</title>
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
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: none;
        }

        header img {
            height: 80px;
        }

        header .header-slogan {
            font-size: 1.5em;
            color: orange;
            font-weight: bold;
            flex: 1;
            text-align: center;
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

        .help-center-container {
            max-width: 800px;
            margin: 50px auto;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
        }

        .help-center-container h2 {
            font-size: 1.8em;
            color: orange;
            text-align: center;
            margin-bottom: 20px;
        }

        .help-center-container p, .help-center-container li {
            font-size: 1.1em;
            line-height: 1.6;
            color: #555;
        }

        .faq h3 {
            font-size: 1.4em;
            color: #333;
            margin-top: 20px;
        }

        .faq p {
            margin-top: 10px;
            font-size: 1.1em;
        }

        .faq a {
            color: orange;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .faq a:hover {
            color: #ff7f00;
        }

        ul {
            margin-top: 20px;
            padding-left: 20px;
            list-style: none;
        }

        ul li {
            position: relative;
            padding-left: 25px;
            margin-bottom: 10px;
        }

        ul li:before {
            content: '✔';
            position: absolute;
            left: 0;
            top: 0;
            color: orange;
            font-weight: bold;
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
</head>
<body>

<!-- Header -->
<header>
    <img src="images/logo.png" alt="Logo Gardien des Animaux">
    <h1 class="header-slogan">Besoin d'aide ? Nous sommes là pour vous.</h1>
    <div class="auth-buttons">
        <button class="btn" onclick="window.location.href='index.php'">Accueil</button>
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
            Oui, vous pouvez annuler une réservation via votre espace utilisateur. Consultez nos <a href="conditions.php">Conditions Générales</a> pour connaître les politiques d'annulation applicables.
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
        <li><strong>Formulaire de contact :</strong> <a href="contact.php">Cliquez ici pour accéder au formulaire</a>.</li>
    </ul>
</div>

<!-- Footer -->
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
