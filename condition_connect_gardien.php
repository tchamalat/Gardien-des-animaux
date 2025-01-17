<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Conditions Générales - Gardien des Animaux</title>
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

        .terms-container {
            max-width: 800px;
            margin: 150px auto;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
        }

        .terms-container h2 {
            font-size: 1.8em;
            color: orange;
            text-align: center;
            margin-bottom: 20px;
        }

        .terms-container p,
        .terms-container ul {
            font-size: 1.1em;
            line-height: 1.6;
            color: #555;
            margin-bottom: 20px;
        }

        .terms-container ul {
            padding-left: 20px;
            list-style: none;
        }

        .terms-container ul li {
            position: relative;
            padding-left: 25px;
            margin-bottom: 10px;
        }

        .terms-container ul li:before {
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
        document.addEventListener('DOMContentLoaded', () => {
            const header = document.querySelector('header');
            window.addEventListener('scroll', () => {
                if (window.scrollY > 50) {
                    header.classList.add('scrolled');
                } else {
                    header.classList.remove('scrolled');
                }
            });
        });
    </script>
</head>
<body>

<!-- Header -->
<header>
    <img src="images/logo.png" alt="Logo Gardien des Animaux">
    <h1 class="header-slogan">Un foyer chaleureux même en votre absence</h1>
    <div class="auth-buttons">
        <button class="btn" onclick="window.location.href='index_connect_gardien.php'">Accueil</button>
    </div>
</header>

<!-- Section des Conditions Générales -->
<div class="terms-container">
    <h2>Conditions Générales d'Utilisation et de Service</h2>
    <p>Bienvenue sur <strong>Gardien-des-animaux.fr</strong>. Ces conditions générales régissent l'utilisation de notre plateforme et de nos services. En utilisant notre site, vous acceptez de respecter ces conditions.</p>
    
    <!-- Sections détaillées -->
    <h2>1. Objet</h2>
    <p>
        <strong>Gardien-des-animaux.fr</strong> est une plateforme dédiée à la mise en relation entre les propriétaires d’animaux et des gardiens qualifiés. Nos services visent à garantir le bien-être des animaux et à offrir une expérience fiable et personnalisée.
    </p>

    <h2>2. Inscription et Utilisation</h2>
    <ul>
        <li>Les utilisateurs doivent fournir des informations exactes et à jour lors de leur inscription.</li>
        <li>Les propriétaires doivent partager des détails sur leurs animaux, y compris leur état de santé et leurs besoins spécifiques.</li>
        <li>Les gardiens s'engagent à respecter les instructions fournies et à assurer le bien-être des animaux confiés.</li>
    </ul>

    <h2>3. Paiements et Annulations</h2>
    <p>
        Tous les paiements effectués via notre plateforme sont sécurisés. Les politiques d'annulation visent à protéger à la fois les propriétaires et les gardiens. En cas de litige, notre équipe intervient comme médiateur.
    </p>

    <h2>4. Responsabilité</h2>
    <p>
        <strong>Gardien-des-animaux.fr</strong> agit uniquement comme intermédiaire. Les utilisateurs sont responsables de leurs engagements et de leurs actions.
    </p>

    <h2>5. Confidentialité</h2>
    <p>
        Vos données personnelles sont protégées conformément à notre <a href="confidentialite_connect_gardien.php">Politique de Confidentialité</a>.
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
