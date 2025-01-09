<?php
session_start();
include 'config.php';

$servername = "localhost";
$username = "gardien";
$password = "G@rdien-des-chiens";
$dbname = "gardiendb";

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

    $stmtReservations = $pdo->query("SELECT COUNT(*) as total FROM reservation WHERE statut = 'en cours'");
    $totalReservations = $stmtReservations->fetch()['total'];

    $stmtAbonnements = $pdo->query("SELECT COUNT(*) as total FROM Abonnement WHERE statut = 'actif'");
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

        .menu-list {
            margin-top: 30px;
            list-style: none;
            padding: 0;
        }

        .menu-list li {
            margin-bottom: 15px;
        }

        .menu-list .btn {
            display: inline-block;
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

        .menu-list .btn:hover {
            background-color: #ff7f00;
            transform: translateY(-3px);
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

<!-- Header -->
<header>
    <img src="images/logo.png" alt="Logo Gardien des Animaux">
    <h1 class="header-slogan">Tableau de Bord Administrateur</h1>
    <div class="auth-buttons">
        <button class="btn" onclick="window.location.href='?logout=1'">Déconnexion</button>
    </div>
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

    <ul class="menu-list">
        <li><a class="btn" href="manage_abonnements.php">Gérer les Abonnements</a></li>
        <li><a class="btn" href="manage_utilisateurs.php">Gérer les Utilisateurs</a></li>
        <li><a class="btn" href="manage_reservations.php">Gérer les Réservations</a></li>
        <li><a class="btn" href="manage_avis.php">Gérer les Avis</a></li>
        <li><a class="btn" href="manage_animaux.php">Gérer les Animaux</a></li>
        <li><a class="btn" href="manage_faq.php">Gérer la FAQ</a></li>
        <li><a class="btn" href="manage_paiements.php">Gérer les Paiements</a></li>
        <li><a class="btn" href="manage_hebergements.php">Gérer les Hébergements</a></li>
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
