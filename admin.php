<?php
session_start();
include 'config.php';

// Logout Handling
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: login.html");
    exit();
}

// Database Connection
try {
    $pdo = new PDO('mysql:host=localhost;dbname=gardiendb', 'gardien', 'G@rdien-des-chiens');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

// Fetch Data
try {
    $stmtUsers = $pdo->query("SELECT COUNT(*) as total FROM creation_compte");
    $totalUsers = $stmtUsers->fetch()['total'];

    $stmtReservations = $pdo->query("SELECT COUNT(*) as total FROM reservation");
    $totalReservations = $stmtReservations->fetch()['total'];

    $stmtAbonnements = $pdo->query("SELECT COUNT(*) as total FROM Abonnement");
    $totalAbonnements = $stmtAbonnements->fetch()['total'];

    // Monthly Data for Graphs
    $stmtUserEvolution = $pdo->query("
        SELECT MONTH(date_creation) AS mois, COUNT(*) AS total
        FROM creation_compte
        GROUP BY mois
        ORDER BY mois ASC
    ");
    $userEvolution = $stmtUserEvolution->fetchAll(PDO::FETCH_ASSOC);

    $stmtReservationEvolution = $pdo->query("
        SELECT MONTH(date_debut) AS mois, COUNT(*) AS total
        FROM reservation
        GROUP BY mois
        ORDER BY mois ASC
    ");
    $reservationEvolution = $stmtReservationEvolution->fetchAll(PDO::FETCH_ASSOC);

    $stmtAbonnementEvolution = $pdo->query("
        SELECT MONTH(date_debut_abo) AS mois, COUNT(*) AS total
        FROM Abonnement
        GROUP BY mois
        ORDER BY mois ASC
    ");
    $abonnementEvolution = $stmtAbonnementEvolution->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erreur lors de la récupération des statistiques : " . $e->getMessage());
}

function transformDataForChart($data) {
    $labels = [];
    $values = [];
    $months = range(1, 12); // 1 = January, 12 = December

    foreach ($months as $month) {
        $found = false;
        foreach ($data as $row) {
            if ((int)$row['mois'] === $month) {
                $labels[] = date("F", mktime(0, 0, 0, $month, 1));
                $values[] = $row['total'];
                $found = true;
                break;
            }
        }
        if (!$found) {
            $labels[] = date("F", mktime(0, 0, 0, $month, 1));
            $values[] = 0; // Default to 0 if no data exists for the month
        }
    }

    return ['labels' => $labels, 'values' => $values];
}

$userChartData = transformDataForChart($userEvolution);
$reservationChartData = transformDataForChart($reservationEvolution);
$abonnementChartData = transformDataForChart($abonnementEvolution);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord Admin</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.8); /* Ombre pour lisibilité */
            transition: opacity 0.5s ease, transform 0.5s ease;
        }

        header.scrolled .header-slogan {
            opacity: 0;
            transform: translateY(-20px);
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
            position: fixed; /* Fixe le menu dans la position définie */
            top: 20px; /* Distance par rapport au haut de la page */
            right: 20px; /* Distance par rapport au côté droit de la page */
            display: inline-block;
            z-index: 100; /* Assure que le menu reste au-dessus des autres éléments */
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

        .menu-dropdown:hover .dropdown-content {
            display: block; /* Changement : s'assure que le menu s'affiche */
        }

        .menu-dropdown .dropdown-content {
            display: none;
            position: absolute; /* Positionné par rapport au parent */
            top: 100%; /* S'assure que le menu est en dessous du bouton */
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
            margin-top: auto;
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
        .chart-container {
            margin-top: 20px;
        }

        canvas {
            max-width: 100%;
            height: auto;
        }
    </style>
    <script>
        window.addEventListener('scroll', () => {
            const header = document.querySelector('header');
            if (window.scrollY > 50) {
                header.classList.add('scrolled');
            } else {
                header.classList.remove('scrolled');
            }
        });
    </script>
</head>
<body>
<!-- Header -->
<header>
    <img src="images/logo.png" alt="Logo Gardien des Animaux">
    <h1 class="header-slogan">Tableau de Bord Administrateur</h1>
</header>

<!-- Dashboard Content -->
<div class="dashboard-container">
    <div class="dashboard-header">
        <h2>Bienvenue <?php echo htmlspecialchars($_SESSION['admin']); ?> !</h2>
    </div>

    <div class="stats-cards">
        <!-- User Stats -->
        <div class="stats-card">
            <h3>Utilisateurs</h3>
            <p>Nombre total : <?php echo $totalUsers; ?></p>
            <div class="chart-container">
                <canvas id="usersChart"></canvas>
            </div>
        </div>

        <!-- Reservation Stats -->
        <div class="stats-card">
            <h3>Réservations</h3>
            <p>En cours : <?php echo $totalReservations; ?></p>
            <div class="chart-container">
                <canvas id="reservationsChart"></canvas>
            </div>
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

<!-- Charts Script -->
<script>
    // Data from PHP
    const userChartData = <?php echo json_encode($userChartData); ?>;
    const reservationChartData = <?php echo json_encode($reservationChartData); ?>;
    const abonnementChartData = <?php echo json_encode($abonnementChartData); ?>;

    // User Chart
    new Chart(document.getElementById('usersChart'), {
        type: 'line',
        data: {
            labels: userChartData.labels,
            datasets: [{
                label: 'Utilisateurs',
                data: userChartData.values,
                borderColor: 'orange',
                backgroundColor: 'rgba(255, 165, 0, 0.2)',
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: true }
            }
        }
    });

    // Reservation Chart
    new Chart(document.getElementById('reservationsChart'), {
        type: 'line',
        data: {
            labels: reservationChartData.labels,
            datasets: [{
                label: 'Réservations',
                data: reservationChartData.values,
                borderColor: 'blue',
                backgroundColor: 'rgba(0, 0, 255, 0.2)',
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: true }
            }
        }
    });
</script>
</body>
</html>
