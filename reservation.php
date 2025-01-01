<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réservation</title>
    <style>
        /* Styles globaux */
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

        header h1 {
            color: orange; /* "Réservation" en orange */
            font-size: 2em;
        }

        .auth-buttons {
            display: flex;
            gap: 15px;
        }

        .auth-buttons .btn {
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

        .auth-buttons .btn:hover {
            background-color: #ff7f00;
            transform: translateY(-3px);
        }

        .reservation-container {
            max-width: 600px;
            margin: 150px auto;
            background: rgba(255, 255, 255, 0.9);
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
        }

        .reservation-container h1, .reservation-container h2 {
            text-align: center;
            color: orange;
            margin-bottom: 20px;
        }

        .reservation-details {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        .reservation-details p {
            margin: 10px 0;
            font-size: 1em;
        }

        .reservation-form label {
            font-weight: bold;
            margin-bottom: 5px;
            display: block;
        }

        .reservation-form input, .reservation-form select {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1em;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .reservation-form button {
            background-color: orange;
            color: white;
            border: none;
            padding: 15px;
            font-size: 1.2em;
            border-radius: 8px;
            cursor: pointer;
            width: 100%;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .reservation-form button:hover {
            background-color: #ff7f00;
            transform: translateY(-3px);
        }

        .confirmation-message {
            background-color: #e6ffe6;
            color: #2c7a2c;
            border: 1px solid #2c7a2c;
            border-radius: 10px;
            padding: 20px;
            margin: 20px auto;
            text-align: center;
            font-size: 1.2em;
        }

        footer {
            padding: 20px;
            background: rgba(0, 0, 0, 0.85);
            color: #fff;
            text-align: center;
            margin-top: 50px;
        }

        .footer-links {
            display: flex;
            justify-content: space-around;
            margin-top: 20px;
        }

        .footer-links div h4 {
            color: orange; /* "En savoir plus", "À propos de nous", etc. en orange */
            margin-bottom: 10px;
        }

        .footer-links ul {
            list-style: none;
            text-align: left;
        }

        .footer-links a {
            color: white; /* Liens en blanc */
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .footer-links a:hover {
            color: #ff7f00;
        }
    </style>
</head>
<body>

<header>
    <h1>Réservation</h1>
    <div class="auth-buttons">
        <button class="btn" onclick="window.location.href='logout.php'">Déconnexion</button> 
        <button class="btn" onclick="window.location.href='index_connect.php'">Accueil</button>
    </div>
</header>

<div class="reservation-container">
    <h1>Faire une réservation</h1>

    <?php if (!empty($message_confirmation)): ?>
        <div class="confirmation-message">
            <?php echo $message_confirmation; ?>
        </div>
    <?php endif; ?>

    <?php if (isset($gardien_info)): ?>
        <h2>Informations sur le gardien</h2>
        <div class="reservation-details">
            <p><strong>Nom :</strong> <?php echo htmlspecialchars($gardien_info['nom_utilisateur']); ?></p>
            <p><strong>Ville :</strong> <?php echo htmlspecialchars($gardien_info['ville']); ?></p>
            <p><strong>Service :</strong> <?php echo htmlspecialchars($gardien_info['service']); ?></p>
            <p><strong>Budget :</strong> <?php echo htmlspecialchars($gardien_info['budget_min']); ?>€ - <?php echo htmlspecialchars($gardien_info['budget_max']); ?>€</p>
        </div>
    <?php endif; ?>

    <form action="reservation.php" method="POST" class="reservation-form">
        <label for="date_debut">Date de début :</label>
        <input type="date" name="date_debut" id="date_debut" required>

        <label for="date_fin">Date de fin :</label>
        <input type="date" name="date_fin" id="date_fin" required>

        <label for="heure_debut">Heure de début :</label>
        <input type="time" name="heure_debut" id="heure_debut" required>

        <label for="heure_fin">Heure de fin :</label>
        <input type="time" name="heure_fin" id="heure_fin" required>

        <label for="lieu">Lieu :</label>
        <input type="text" name="lieu" id="lieu" required>

        <label for="type">Type de service :</label>
        <select name="type" id="type" required>
            <option value="Hébergement">Hébergement</option>
            <option value="Promenade">Promenade</option>
            <option value="Garde à domicile">Garde à domicile</option>
        </select>

        <button type="submit">Confirmer la réservation</button>
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
