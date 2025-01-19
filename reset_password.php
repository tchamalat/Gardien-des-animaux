<?php
// Définir le fuseau horaire pour éviter les problèmes avec NOW()
date_default_timezone_set('Europe/Paris'); 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer le token et le nouveau mot de passe
    $token = $_POST['token'];
    $new_password = md5($_POST['new_password']);

    require 'config.php';

    // Vérifier la validité du token
    $query = $conn->prepare('SELECT * FROM creation_compte WHERE reset_token = ? AND token_expiration > NOW()');
    $query->bind_param('s', $token);
    $query->execute();
    $result = $query->get_result();

    if ($result->num_rows > 0) {
        // Token valide : continuer avec la mise à jour
        $query = $conn->prepare('UPDATE creation_compte SET mot_de_passe = ?, reset_token = NULL, token_expiration = NULL WHERE reset_token = ?');
        $query->bind_param('ss', $new_password, $token);
        if ($query->execute()) {
            $message = "Votre mot de passe a été mis à jour.";
        } else {
            $message = "Erreur lors de la mise à jour du mot de passe.";
        }
    } else {
        // Token invalide ou expiré
        $message = "Le lien de réinitialisation est invalide ou a expiré.";
    }
} else if (isset($_GET['token'])) {
    // Récupérer le token depuis l'URL
    $token = $_GET['token'];
} else {
    // Aucun token fourni
    $message = "Aucun token fourni.";
    $token = null;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réinitialiser le mot de passe - Gardien des Animaux</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html, body {
            height: 100%;
            font-family: Arial, sans-serif;
            background: url('images/premierplan.png') no-repeat center center fixed;
            background-size: cover;
            color: #333;
            display: flex;
            flex-direction: column;
        }

        body {
            margin: 0;
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
            height: 130px;
            max-width: 150px;
        }

        header .header-slogan {
            font-size: 1.5em;
            color: orange;
            font-weight: bold;
            text-align: center;
            flex: 1;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.8);
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

        .form-container {
            background: rgba(255, 255, 255, 0.9);
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
            width: 90%;
            max-width: 400px;
            text-align: center;
            margin: 120px auto auto;
            flex-grow: 1;
        }

        h2 {
            color: orange;
            margin-bottom: 20px;
        }

        .form-container p {
            margin-bottom: 20px;
            color: #555;
        }

        .form-group {
            margin-bottom: 20px;
            text-align: left;
        }

        .form-group label {
            display: block;
            font-weight: bold;
            color: #333;
            margin-bottom: 5px;
        }

        .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 1em;
        }

        .btn {
            display: inline-block;
            background-color: orange;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1.2em;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .btn:hover {
            background-color: #ff7f00;
            transform: translateY(-3px);
        }

        .message {
            margin-bottom: 20px;
            padding: 10px;
            border-radius: 5px;
            font-size: 1em;
        }

        .message.success {
            background-color: #d4edda;
            color: #155724;
        }

        .message.error {
            background-color: #f8d7da;
            color: #721c24;
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
    <header>
        <img src="images/logo.png" alt="Logo Gardien des Animaux">
        <h1 class="header-slogan">Un foyer chaleureux même en votre absence</h1>
        <div class="auth-buttons">
            <button class="btn" onclick="window.location.href='index.php'">Accueil</button>
        </div>
    </header>

    <div class="form-container">
        <h2>Réinitialisation de mot de passe</h2>
        <?php if (isset($message)): ?>
            <p class="message <?php echo strpos($message, 'Erreur') === false ? 'success' : 'error'; ?>">
                <?php echo htmlspecialchars($message); ?>
            </p>
        <?php endif; ?>

        <?php if (isset($token)): ?>
            <p>Veuillez saisir un nouveau mot de passe pour votre compte.</p>
            <form action="reset_password.php" method="POST">
                <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
                <div class="form-group">
                    <label for="new_password">Nouveau mot de passe :</label>
                    <input type="password" name="new_password" id="new_password" required>
                </div>
                <button type="submit" class="btn">Mettre à jour</button>
            </form>
        <?php endif; ?>
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
