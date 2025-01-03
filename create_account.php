<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer un compte - Gardien des Animaux</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            color: #fff;
            background: url('images/premierplan.png') no-repeat center center fixed;
            background-size: cover;
            min-height: 100vh;
            overflow-x: hidden;
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
            height: 60px;
            max-width: 120px;
        }

        .header-slogan {
            font-size: 1.2em;
            color: #fff;
            text-shadow: 1px 1px 4px rgba(0, 0, 0, 0.5);
            text-align: center;
            flex: 1;
            margin: 0 20px;
        }

        .auth-buttons .btn {
            background-color: orange;
            color: white;
            padding: 8px 15px;
            border: none;
            border-radius: 8px;
            font-size: 0.9em;
            cursor: pointer;
            text-decoration: none;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .auth-buttons .btn:hover {
            background-color: #ff7f00;
            transform: translateY(-3px);
        }

        .form-container {
            margin: 150px auto 50px; /* Ajustement pour centrer */
            width: 90%;
            max-width: 400px;
            background: rgba(255, 255, 255, 0.9);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            color: #333;
        }

        .form-container h2 {
            text-align: center;
            margin-bottom: 20px;
            color: orange;
            font-size: 1.5em;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            font-size: 0.9em;
        }

        .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 1em;
        }

        .form-group input[type="radio"] {
            display: inline-block;
            width: auto;
            margin-right: 10px;
        }

        .form-group small {
            color: red;
            display: none;
        }

        .btn {
            display: block;
            width: 100%;
            background-color: orange;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 8px;
            font-size: 1.2em;
            cursor: pointer;
            text-align: center;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .btn:hover {
            background-color: #ff7f00;
            transform: translateY(-3px);
        }

        footer {
            padding: 20px;
            background: rgba(0, 0, 0, 0.8);
            color: #fff;
            text-align: center;
        }

        .footer-links {
            display: flex;
            justify-content: space-around;
        }

        .footer-links ul {
            list-style: none;
        }

        .footer-links a {
            color: #fff;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .footer-links a:hover {
            color: orange;
        }
    </style>
</head>
<body>
    <header>
        <div class="header-container">
            <img src="images/logo.png" alt="Logo Gardien des Animaux">
            <h1 class="header-slogan">Un foyer chaleureux même en votre absence</h1>
            <div class="auth-buttons">
                <button class="btn" onclick="window.location.href='index.php'">Accueil</button>
            </div>
        </div>
    </header>

    <div class="form-container">
        <h2>Création de compte :</h2>
        <div id="error-message" style="color: red; display: none;"></div>
        <form id="registerForm" method="POST" action="register.php" novalidate>
            <div class="form-group">
                <label for="prenom">Prénom :</label>
                <input type="text" id="prenom" name="prenom" required>
            </div>
            <div class="form-group">
                <label for="nom">Nom :</label>
                <input type="text" id="nom" name="nom" required>
            </div>
            <div class="form-group">
                <label for="username">Nom d'utilisateur :</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="email">Adresse mail :</label>
                <input type="email" id="email" name="email" required>
                <small id="emailError">Adresse e-mail invalide.</small>
            </div>
            <div class="form-group">
                <label for="telephone">Numéro de téléphone :</label>
                <input type="tel" id="telephone" name="telephone" pattern="[0-9]{10}" required>
            </div>
            <div class="form-group">
                <label for="password">Mot de passe :</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="confirm_password">Confirmation du mot de passe :</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
                <small id="passwordError">Les mots de passe ne correspondent pas.</small>
            </div>
            <div class="form-group">
                <label>Rôle :</label>
                <input type="radio" id="gardien" name="role" value="0" required>
                <label for="gardien">Gardien</label>
                <input type="radio" id="proprietaire" name="role" value="1" required>
                <label for="proprietaire">Propriétaire</label>
            </div>
            <button type="submit" class="btn">Créer un compte</button>
        </form>
    </div>

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
