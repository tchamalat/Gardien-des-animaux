<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sécurité - Gardien des Animaux</title>
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

        .security-container {
            max-width: 800px;
            margin: 150px auto;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
        }

        .security-container h2 {
            font-size: 1.8em;
            color: orange;
            text-align: center;
            margin-bottom: 20px;
        }

        .security-container p, .security-container li {
            font-size: 1.1em;
            line-height: 1.6;
            color: #555;
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
    <h1 class="header-slogan">Votre sécurité, notre priorité</h1>
    <div class="auth-buttons">
        <button class="btn" onclick="window.location.href='index_connect_gardien.php'">Accueil</button>
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
        Notre plateforme offre un espace de messagerie sécurisé, vous permettant de discuter et de clarifier tous les détails avec votre gardien avant de confirmer une réservation.
    </p>

    <h2>3. Paiements protégés</h2>
    <p>
        Toutes les transactions effectuées via notre plateforme sont sécurisées grâce à des systèmes de paiement certifiés.
    </p>

    <h2>4. Protection des données personnelles</h2>
    <p>
        Vos informations personnelles sont stockées de manière sécurisée et ne sont jamais partagées avec des tiers sans votre consentement explicite.
    </p>

    <h2>5. Assurance et assistance</h2>
    <p>
        En cas de problème ou de litige, notre équipe d’assistance est disponible pour vous aider et trouver une solution.
    </p>

    <h3>Besoin d'aide ?</h3>
    <p>
        Si vous avez des questions ou des inquiétudes concernant la sécurité, contactez-nous à : 
        <a href="mailto:contact@gardien-des-animaux.fr">contact@gardien-des-animaux.fr</a>.
    </p>
</div>
<!-- Footer -->
<footer>
    <div class="footer-links">
        <div>
            <h4>En savoir plus :</h4>
            <ul>
                <li><a href="securite_connect_gardien.php">Sécurité</a></li>
                <li><a href="aide_connect_gardien.php">Centre d'aide</a></li>
            </ul>
        </div>
        <div>
            <h4>A propos de nous :</h4>
            <ul>
                <li><a href="confidentialite_connect_gardien.php">Politique de confidentialité</a></li>
                <li><a href="contact_connect_gardien.php">Nous contacter</a></li>
            </ul>
        </div>
        <div>
            <h4>Conditions Générales :</h4>
            <ul>
                <li><a href="conditions_connect_gardien.php">Conditions d'utilisateur et de Service</a></li>
            </ul>
        </div>
    </div>
</footer>

</body>
</html>
