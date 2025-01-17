<?php 
session_start(); 

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include 'config.php';

$user_id = $_SESSION['user_id'];

$sql = "SELECT nom_utilisateur, nom, prenom, mail, numero_telephone, adresse, ville, profile_picture FROM creation_compte WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($nom_utilisateur, $nom, $prenom, $mail, $numero_telephone, $adresse, $ville, $profile_picture);
$stmt->fetch();
$stmt->close();
$conn->close();

$message = isset($_SESSION['message']) ? $_SESSION['message'] : '';
unset($_SESSION['message']); 
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Profil - Gardien des Animaux</title>
    <style>
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
            height: 100px;
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
            margin-left: 10px;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        header .auth-buttons .btn:hover {
            background-color: #ff7f00;
            transform: translateY(-3px);
        }

        .profile-container {
            margin: 150px auto;
            max-width: 900px;
            background: rgba(255, 255, 255, 0.85);
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
            text-align: center;
        }

        .profile-container h2 {
            color: orange;
            margin-bottom: 30px;
        }

        .profile-picture img {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 15px;
        }

        .profile-picture button {
            background-color: orange;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            font-size: 1em;
            margin: 10px 5px;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .profile-picture button:hover {
            background-color: #ff7f00;
            transform: translateY(-3px);
        }

        .profile-details {
            margin-top: 20px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .profile-details .profile-item {
            background: rgba(255, 255, 255, 0.9);
            border-radius: 10px;
            padding: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
        }

        .profile-details .profile-item label {
            font-weight: bold;
            color: #555;
            margin-bottom: 5px;
        }

        .profile-details .profile-item span {
            font-size: 1.1em;
            color: #333;
        }

        .profile-actions {
            margin-top: 30px;
        }

        .profile-actions .btn {
            background-color: orange;
            color: white;
            padding: 12px 25px;
            border: none;
            border-radius: 10px;
            font-size: 1em;
            font-weight: bold;
            cursor: pointer;
            margin: 10px;
            text-decoration: none;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .profile-actions .btn:hover {
            background-color: #ff7f00;
            transform: translateY(-3px);
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

<header>
    <img src="images/logo.png" alt="Logo Gardien des Animaux">
    <div class="auth-buttons">
        <button class="btn" onclick="window.location.href='index_connect.php'">Accueil</button>
        <button class="btn" onclick="window.location.href='logout.php'">Déconnexion</button> 
    </div>
</header>

<div class="profile-container">
    <h2>Mon profil</h2>
    <div class="profile-picture">
        <img id="profile-img" src="display_image.php?id=<?php echo $user_id; ?>" alt="Photo de profil">
        <form action="upload_image.php" method="POST" enctype="multipart/form-data">
            <input type="file" id="profile-picture-input" name="profilePicture" accept="image/*" style="display: none;" onchange="previewProfileImage(event)">
            <button type="button" onclick="document.getElementById('profile-picture-input').click();">Changer la photo</button>
            <button type="submit">Enregistrer</button>
        </form>
    </div>

    <div class="profile-details">
        <div class="profile-item">
            <label>Nom d'utilisateur :</label>
            <span><?php echo htmlspecialchars($nom_utilisateur); ?></span>
        </div>
        <div class="profile-item">
            <label>Nom :</label>
            <span><?php echo htmlspecialchars($nom); ?></span>
        </div>
        <div class="profile-item">
            <label>Prénom :</label>
            <span><?php echo htmlspecialchars($prenom); ?></span>
        </div>
        <div class="profile-item">
            <label>Adresse mail :</label>
            <span><?php echo htmlspecialchars($mail); ?></span>
        </div>
        <div class="profile-item">
            <label>Numéro de téléphone :</label>
            <span><?php echo htmlspecialchars($numero_telephone); ?></span>
        </div>
        <div class="profile-item">
            <label>Adresse :</label>
            <span><?php echo htmlspecialchars($adresse); ?></span>
        </div>
        <div class="profile-item">
            <label>Ville :</label>
            <span><?php echo htmlspecialchars($ville); ?></span>
        </div>
    </div>

    <div class="profile-actions">
        <a href="historique.php" class="btn">Historique</a>
        <a href="profil_public.php" class="btn">Mon Profil Public</a>
        <form method="POST" action="delete_account.php" style="display: inline;">
            <button class="btn" type="submit" name="delete_account" style="background-color: red;">Supprimer le compte</button>
        </form>
    </div>

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
