<?php
session_start();
include 'config.php';

// Gestion de la déconnexion
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: login.html");
    exit();
}

// Connexion à la base de données
try {
    $pdo = new PDO('mysql:host=localhost;dbname=gardiendb', 'gardien', 'G@rdien-des-chiens');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

// Récupération des statistiques
try {
    $stmtUsers = $pdo->query("SELECT COUNT(*) as total FROM creation_compte");
    $totalUsers = $stmtUsers->fetch()['total'];

    $stmtReservations = $pdo->query("SELECT COUNT(*) as total FROM reservation");
    $totalReservations = $stmtReservations->fetch()['total'];

    $stmtAbonnements = $pdo->query("SELECT COUNT(*) as total FROM Abonnement");
    $totalAbonnements = $stmtAbonnements->fetch()['total'];
} catch (PDOException $e) {
    die("Erreur lors de la récupération des statistiques : " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord Admin</title>
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
            padding: 20px 40px;
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
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.8);
        }

        .dashboard-container {
            max-width: 1200px;
            margin: 120px auto;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
        }

        .dashboard-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .dashboard-header h2 {
            font-size: 2em;
            color: orange;
        }

        .stats-cards {
            display: flex;
            gap: 20px;
            justify-content: space-between;
            flex-wrap: wrap;
        }

        .stats-card {
            flex: 1;
            min-width: 250px;
            background-color: orange;
            color: white;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
        }

        .stats-card h3 {
            font-size: 1.5em;
            margin-bottom: 10px;
        }

        .menu-dropdown {
            position: fixed;
            top: 20px;
            right: 40px;
            display: inline-block;
        }

        .menu-dropdown .dropdown-btn {
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

        .menu-dropdown .dropdown-btn:hover {
            background-color: #ff7f00;
        }

        .menu-dropdown .dropdown-content {
            display: none;
            position: absolute;
            right: 0;
            background-color: white;
            min-width: 200px;
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
            border-radius: 8px;
            z-index: 1;
        }

        .menu-dropdown .dropdown-content a {
            color: black;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
            border-bottom: 1px solid #ddd;
        }

        .menu-dropdown .dropdown-content a:hover {
            background-color: #f1f1f1;
        }

        .menu-dropdown:hover .dropdown-content {
            display: block;
        }

        .menu-dropdown .dropdown-content .btn-deconnexion {
            background-color: #ff0000;
            color: white;
            border: none;
            text-align: center;
            padding: 12px 16px;
            display: block;
            border-radius: 8px;
            cursor: pointer;
        }

        .menu-dropdown .dropdown-content .btn-deconnexion:hover {
            background-color: #ff4d4d;
        }
    </style>
</head>
<body>

<!-- Header -->
<header>
    <img src="images/logo.png" alt="Logo Gardien des Animaux">
    <h1 class="header-slogan">Tableau de Bord Administrateur</h1>
</header>

<!-- Contenu principal -->
<div class="dashboard-container">
    <div class="dashboard-header">
        <h2>Bienvenue, <?php echo htmlspecialchars($_SESSION['admin']); ?> !</h2>
    </div>

    <div class="stats-cards">
        <div class="stats-card">
            <h3>Utilisateurs</h3>
            <p>Nombre total : <?php echo $totalUsers; ?></p>
        </div>
        <div class="stats-card">
            <h3>Réservations</h3>
            <p>En cours : <?php echo $totalReservations; ?></p>
        </div>
        <div class="stats-card">
            <h3>Abonnements</h3>
            <p>Actifs : <?php echo $totalAbonnements; ?></p>
        </div>
    </div>
</div>

<div class="menu-dropdown">
    <button class="dropdown-btn">Menu</button>
    <div class="dropdown-content">
        <a href="manage_abonnements.php">Gérer les Abonnements</a>
        <a href="manage_utilisateurs.php">Gérer les Utilisateurs</a>
        <a href="manage_reservations.php">Gérer les Réservations</a>
        <a href="manage_avis.php">Gérer les Avis</a>
        <a href="manage_animaux.php">Gérer les Animaux</a>
        <a href="manage_faq.php">Gérer la FAQ</a>
        <a href="manage_paiements.php">Gérer les Paiements</a>
        <a href="manage_hebergements.php">Gérer les Hébergements</a>
        <button class="btn-deconnexion" onclick="window.location.href='?logout=1'">Déconnexion</button>
    </div>
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
