<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Politique de Confidentialité - Gardien des Animaux</title>
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
            height: 80px;
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

        .privacy-container {
            max-width: 800px;
            margin: 150px auto;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
        }

        .privacy-container h2 {
            font-size: 1.8em;
            color: orange;
            text-align: center;
            margin-bottom: 20px;
        }

        .privacy-container p, .privacy-container li {
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
    <h1 class="header-slogan">Votre vie privée, notre priorité</h1>
    <div class="auth-buttons">
        <button class="btn" onclick="window.location.href='index.php'">Accueil</button>
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
        Vos informations personnelles sont conservées uniquement pendant la durée nécessaire à la réalisation des services pour lesquels elles ont été collectées.
    </p>

    <h2>5. Vos droits</h2>
    <p>
        Conformément au RGPD, vous disposez des droits suivants concernant vos données personnelles :
    </p>
    <ul>
        <li><strong>Droit d'accès :</strong> Demander une copie de vos données personnelles.</li>
        <li><strong>Droit de rectification :</strong> Demander la correction de données incorrectes ou incomplètes.</li>
        <li><strong>Droit de suppression :</strong> Demander la suppression de vos données.</li>
        <li><strong>Droit d'opposition :</strong> Refuser l’utilisation de vos données à des fins de marketing.</li>
    </ul>

    <h2>6. Sécurité de vos informations</h2>
    <p>
        Nous mettons en œuvre des mesures de sécurité techniques et organisationnelles pour protéger vos données.
    </p>

    <h2>7. Cookies</h2>
    <p>
        Notre site utilise des cookies pour améliorer votre expérience. Vous pouvez gérer vos préférences dans les paramètres de votre navigateur.
    </p>

    <h2>8. Contact</h2>
    <p>
        Pour toute question ou demande concernant vos données personnelles, contactez-nous :
    </p>
    <ul>
        <li><strong>Email :</strong> <a href="mailto:contact@gardien-des-animaux.fr">contact@gardien-des-animaux.fr</a></li>
        <li><strong>Téléphone :</strong> +33 1 23 45 67 89 (du lundi au vendredi, de 9h à 18h).</li>
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
