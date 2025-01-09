<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmation d'inscription - Gardien des Animaux</title>
    <style>
        /* Global Styles */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: url('images/premierplan.png') no-repeat center center fixed;
            background-size: cover;
            color: #333;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        header {
            display: flex;
            align-items: center;
            padding: 20px 40px;
            position: relative;
        }

        header img {
            height: 100px; /* Larger logo */
            margin-right: 20px;
        }

        header h1 {
            color: white;
            font-size: 2em;
            text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.6);
        }

        .form-container {
            max-width: 700px;
            margin: auto;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            padding: 40px;
            text-align: center;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
            flex: 1;
        }

        .form-container h2 {
            color: orange;
            font-size: 2.2em;
            margin-bottom: 20px;
        }

        .form-container p {
            font-size: 1.1em;
            color: #555;
            margin-bottom: 30px;
        }

        .form-container .btn {
            background-color: orange;
            color: white;
            padding: 15px 35px;
            border: none;
            border-radius: 8px;
            font-size: 1.2em;
            cursor: pointer;
            text-decoration: none;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .form-container .btn:hover {
            background-color: #ff7f00;
            transform: translateY(-3px);
        }

        footer {
            background: rgba(0, 0, 0, 0.85);
            color: #fff;
            padding: 20px;
            margin-top: auto;
            text-align: center;
        }

        footer .footer-links {
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
            max-width: 800px;
            margin: auto;
        }

        footer .footer-links div {
            text-align: left;
            margin: 10px;
        }

        footer .footer-links h4 {
            color: orange;
            margin-bottom: 10px;
        }

        footer .footer-links ul {
            list-style: none;
            padding: 0;
        }

        footer .footer-links li {
            margin-bottom: 5px;
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
    <h1>Inscription Réussie</h1>
</header>

<!-- Confirmation Message -->
<section class="form-container">
    <h2>Merci pour votre inscription !</h2>
    <p>Votre compte a été créé avec succès. Vous pouvez maintenant vous connecter.</p>
    <a class="btn" href="login.html">Se connecter</a>
</section>

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
