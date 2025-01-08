<?php 
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include 'config.php';

$user_id = $_SESSION['user_id'];

$message = isset($_SESSION['message']) ? $_SESSION['message'] : '';
unset($_SESSION['message']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $type_animal = $_POST['type_animal'];
    $nombre_animal = $_POST['nombre_animal'];
    $budget_min = $_POST['budget_min'];
    $budget_max = $_POST['budget_max'];

    // Validation des budgets
    if ($budget_min < 0 || $budget_max < 0) {
        $_SESSION['message'] = 'Les budgets minimum et maximum ne peuvent pas être négatifs.';
        header('Location: profil_gardien.php');
        exit();
    }

    if ($budget_min > $budget_max) {
        $_SESSION['message'] = 'Le budget minimum ne peut pas être supérieur au budget maximum.';
        header('Location: profil_gardien.php');
        exit();
    }

    $service = $_POST['service'];

    // Validation du type de service
    if ($service !== 'garde' && $service !== 'promenade') {
        $_SESSION['message'] = 'Le type de service doit être soit garde, soit promenade.';
        header('Location: profil_gardien.php');
        exit();
    }

    $sql_update = "UPDATE creation_compte SET type_animal = ?, nombre_animal = ?, budget_min = ?, budget_max = ?, service = ? WHERE id = ?";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param("siddsi", $type_animal, $nombre_animal, $budget_min, $budget_max, $service, $user_id);

    if ($stmt_update->execute()) {
        $_SESSION['message'] = 'Modifications enregistrées avec succès !';
        header('Location: profil_gardien.php');
        exit();
    } else {
        $_SESSION['message'] = 'Erreur lors de l\'enregistrement des modifications.';
        header('Location: profil_gardien.php');
        exit();
    }

    $stmt_update->close();
}

$sql = "SELECT nom_utilisateur, nom, prenom, mail, numero_telephone, adresse, ville, profile_picture, type_animal, nombre_animal, budget_min, budget_max, service FROM creation_compte WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($nom_utilisateur, $nom, $prenom, $mail, $numero_telephone, $adresse, $ville, $profile_picture, $type_animal, $nombre_animal, $budget_min, $budget_max, $service);
$stmt->fetch();
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Profil de Gardien - Gardien des Animaux</title>
    <style>
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
            background: none;
            box-shadow: none;
        }
        header .header-slogan {
            font-size: 1.5em;
            color: orange;
            font-weight: bold;
            text-align: center;
            flex: 1;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.8); /* Ombre pour lisibilité */
            transition: opacity 0.5s ease, transform 0.5s ease;
        }

        header.scrolled .header-slogan {
            opacity: 0;
            transform: translateY(-20px);
        }
        .header-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 100%;
        }

        header img {
            height: 80px;
            position: absolute;
            left: 20px; /* Placez le logo à gauche */
            top: 20px;
        }

        header .auth-buttons {
            display: flex;
            gap: 15px;
            position: absolute;
            top: 20px;
            right: 20px; /* Positionner à droite */
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

        .profile-container {
            max-width: 900px;
            margin: 200px auto 50px; 
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
        }


        .profile-title {
            font-size: 2.5em;
            color: orange;
            text-align: center;
            margin-bottom: 30px;
        }

        .profile-picture {
            text-align: center;
            margin-bottom: 30px;
        }

        .profile-picture img {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid orange;
        }

        .profile-form {
            text-align: center;
            margin-bottom: 20px;
        }

        .btn-photo {
            background-color: orange;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 8px;
            font-size: 1em;
            cursor: pointer;
            margin: 5px;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .btn-photo:hover {
            background-color: #ff7f00;
            transform: translateY(-3px);
        }

        .info-section {
            margin-bottom: 30px;
        }

        .info-section h3 {
            font-size: 1.8em;
            color: orange;
            margin-bottom: 15px;
        }

        .info-section .info-card {
            background: #f9f9f9;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .info-section .info-card label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
        }

        .info-section .info-card span {
            display: block;
            font-size: 1.1em;
            color: #555;
        }

        .form-section {
            margin-top: 30px;
        }

        .form-section label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
        }

        .form-section input,
        .form-section select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-bottom: 15px;
            font-size: 1em;
        }

        .btn {
            display: block;
            width: 100%;
            background-color: orange;
            color: white;
            padding: 15px;
            border: none;
            border-radius: 8px;
            font-size: 1.2em;
            cursor: pointer;
            margin-top: 20px;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .btn:hover {
            background-color: #ff7f00;
            transform: translateY(-3px);
        }

        .btn-delete-account {
            display: block;
            width: 100%;
            background: linear-gradient(90deg, #ff6b6b, #e74c3c);
            color: white;
            padding: 15px;
            border: none;
            border-radius: 8px;
            font-size: 1.2em;
            cursor: pointer;
            margin-top: 20px;
            text-align: center;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
        }

        .btn-delete-account:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 8px rgba(0, 0, 0, 0.3);
            background: linear-gradient(90deg, #e74c3c, #c0392b);
        }


        footer {
            background: rgba(0, 0, 0, 0.85);
            color: #fff;
            padding: 20px;
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

        .alert-message {
            background-color: #f9f9f9;
            color: #333;
            border-left: 5px solid orange;
            padding: 10px 15px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
    </style>
</head>
<body>

<header>
    <div class="header-container">
        <img src="images/logo.png" alt="Logo Gardien des Animaux">
        <h1 class="header-slogan">Un foyer chaleureux même en votre absence</h1>
        <div class="auth-buttons">
            <button class="btn" onclick="window.location.href='logout.php'">Déconnexion</button>
            <button class="btn" onclick="window.location.href='index_connect_gardien.php'">Accueil</button>
        </div>
    </div>
</header>

<section class="profile-container">
    <?php if ($message): ?>
        <div class="alert-message">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>

    <h2 class="profile-title">Profil du Gardien</h2>
    
    <div class="profile-picture">
        <img id="profile-img" src="display_image_gardien.php" alt="Photo de profil">
    </div>

    <form action="upload_image_gardien.php" method="POST" enctype="multipart/form-data" class="profile-form">
        <input type="file" id="profile-picture-input" name="photo_profil" accept="image/*" style="display: none;" onchange="previewProfileImage(event)">
        <button type="button" class="btn-photo" onclick="document.getElementById('profile-picture-input').click();">Changer la photo de profil</button>
        <button type="submit" class="btn-photo">Enregistrer la nouvelle photo</button>
    </form>

    <div class="info-section">
        <h3>Informations personnelles</h3>
        <div class="info-card">
            <label>Nom d'utilisateur :</label>
            <span><?= htmlspecialchars($nom_utilisateur) ?></span>
        </div>
        <div class="info-card">
            <label>Nom :</label>
            <span><?= htmlspecialchars($nom) ?></span>
        </div>
        <div class="info-card">
            <label>Prénom :</label>
            <span><?= htmlspecialchars($prenom) ?></span>
        </div>
        <div class="info-card">
            <label>Email :</label>
            <span><?= htmlspecialchars($mail) ?></span>
        </div>
        <div class="info-card">
            <label>Adresse :</label>
            <span><?= htmlspecialchars($adresse) . ', ' . htmlspecialchars($ville) ?></span>
        </div>
    </div>

    <form action="profil_gardien.php" method="POST" class="form-section">
        <h3>Modifier mes préférences</h3>
        <label for="type_animal">Type d'animal :</label>
        <input type="text" id="type_animal" name="type_animal" value="<?= htmlspecialchars($type_animal) ?>" required>

        <label for="nombre_animal">Nombre d'animaux :</label>
        <input type="number" id="nombre_animal" name="nombre_animal" value="<?= htmlspecialchars($nombre_animal) ?>" required>

        <label for="budget_min">Budget minimum (en €) :</label>
        <input type="number" id="budget_min" name="budget_min" value="<?= htmlspecialchars($budget_min) ?>" required>

        <label for="budget_max">Budget maximum (en €) :</label>
        <input type="number" id="budget_max" name="budget_max" value="<?= htmlspecialchars($budget_max) ?>" required>

        <label for="service">Type de service :</label>
        <select id="service" name="service" required>
            <option value="garde" <?= $service === 'garde' ? 'selected' : '' ?>>Garde</option>
            <option value="promenade" <?= $service === 'promenade' ? 'selected' : '' ?>>Promenade</option>
        </select>

        <button type="submit" class="btn">Enregistrer les modifications</button>
    </form>

    <form method="POST" action="delete_account.php">
        <button class="btn-delete-account" type="submit" name="delete_account">Supprimer mon compte</button>
    </form>
</section>

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
