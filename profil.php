<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Profil - Gardien des Animaux</title>
    <style>
        /* Styles globaux */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
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
            height: 100px; /* Agrandissement du logo */
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
        }

        header .auth-buttons .btn:hover {
            background-color: #ff7f00;
        }

        .profile-container {
            margin: 150px auto 50px auto;
            max-width: 600px;
            background: rgba(255, 255, 255, 0.9);
            border-radius: 10px;
            padding: 20px;
            color: #333;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .profile-container h2 {
            color: orange;
            text-align: center;
            margin-bottom: 20px;
        }

        .profile-info {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 20px;
        }

        .profile-picture img {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
        }

        .profile-details {
            width: 100%;
        }

        .profile-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }

        .profile-item label {
            font-weight: bold;
            color: #555;
        }

        .btn-modern {
            display: inline-block;
            background-color: #f5a623;
            color: white;
            padding: 12px 25px;
            border: none;
            border-radius: 10px;
            font-size: 1em;
            font-weight: bold;
            cursor: pointer;
            text-align: center;
            text-decoration: none;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .btn-modern:hover {
            background-color: #ff7f00;
            transform: translateY(-3px);
        }

        .profile-actions {
            text-align: center;
            margin-top: 20px;
        }

        .btn-delete-account {
            background-color: red;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            display: block;
            margin: 20px auto;
            cursor: pointer;
        }

        .btn-delete-account:hover {
            background-color: darkred;
        }

        footer {
            padding: 20px;
            background: rgba(0, 0, 0, 0.8);
            color: #fff;
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
    <img src="images/logo.png" alt="Logo Gardien des Animaux">
    <div class="auth-buttons">
        <button class="btn" onclick="window.location.href='index_connect.php'">Accueil</button>
    </div>
</header>

<div class="profile-container">
    <?php if ($message): ?>
        <div class="alert-message"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>

    <h2>Mon profil</h2>

    <div class="profile-info">
        <div class="profile-picture">
            <img id="profile-img" src="display_image.php" alt="Photo de profil">
        </div>
        <form action="upload_image.php" method="POST" enctype="multipart/form-data" class="profile-form">
            <input type="file" id="profile-picture-input" name="profilePicture" accept="image/*" style="display: none;" onchange="previewProfileImage(event)">
            <button type="button" class="btn-modern" onclick="document.getElementById('profile-picture-input').click();">Changer la photo</button>
            <button type="submit" class="btn-modern">Enregistrer</button>
        </form>
    </div>

    <div class="profile-details">
        <div class="profile-item"><label>Nom d'utilisateur :</label><span><?php echo htmlspecialchars($nom_utilisateur); ?></span></div>
        <div class="profile-item"><label>Nom :</label><span><?php echo htmlspecialchars($nom); ?></span></div>
        <div class="profile-item"><label>Prénom :</label><span><?php echo htmlspecialchars($prenom); ?></span></div>
        <div class="profile-item"><label>Adresse mail :</label><span><?php echo htmlspecialchars($mail); ?></span></div>
        <div class="profile-item"><label>Numéro de téléphone :</label><span><?php echo htmlspecialchars($numero_telephone); ?></span></div>
        <div class="profile-item"><label>Adresse :</label><span><?php echo htmlspecialchars($adresse); ?></span></div>
        <div class="profile-item"><label>Ville :</label><span><?php echo htmlspecialchars($ville); ?></span></div>
    </div>

    <div class="profile-actions">
        <button class="btn-modern" onclick="window.location.href='historique.php'">HISTORIQUE</button>
        <button class="btn-modern" onclick="window.location.href='profil_public.php'">MON PROFIL PUBLIC</button>
    </div>

    <form method="POST" action="delete_account.php">
        <button class="btn-delete-account" type="submit" name="delete_account">Supprimer mon compte</button>
    </form>
</div>

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

<script>
function previewProfileImage(event) {
    const reader = new FileReader();
    const imgElement = document.getElementById('profile-img');

    reader.onload = function(){
        if (reader.readyState === 2) {
            imgElement.src = reader.result; 
        }
    }
    reader.readAsDataURL(event.target.files[0]);
}
</script>
</body>
</html>
