<?php
session_start();
include 'config.php';

if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: login.html");
    exit();
}

try {
    $pdo = new PDO('mysql:host=localhost;dbname=gardiendb', 'gardien', 'G@rdien-des-chiens');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

try {
    $stmtUsers = $pdo->query("SELECT COUNT(*) as total FROM creation_compte");
    $totalUsers = $stmtUsers->fetch()['total'];

    $stmtReservations = $pdo->query("SELECT COUNT(*) as total FROM reservation");
    $totalReservations = $stmtReservations->fetch()['total'];
    
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

} catch (PDOException $e) {
    die("Erreur lors de la récupération des statistiques : " . $e->getMessage());
}

function transformDataForChart($data) {
    $labels = [];
    $values = [];
    $months = range(1, 12);

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
            $values[] = 0; // Default to 0
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
            display: flex;
        }

        .sidebar {
            width: 250px;
            height: 100vh;
            background-color: #2c3e50;
            color: white;
            display: flex;
            flex-direction: column;
            align-items: center;
            position: fixed;
            top: 0;
            left: 0;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.2);
        }

        .sidebar h2 {
            margin: 20px 0;
            font-size: 1.5em;
            text-align: center;
        }

        .sidebar ul {
            list-style: none;
            padding: 0;
            width: 100%;
        }

        .sidebar ul li {
            width: 100%;
            text-align: left;
        }

        .sidebar ul li a {
            text-decoration: none;
            color: white;
            display: flex;
            align-items: center;
            padding: 15px 20px;
            transition: background-color 0.3s ease, padding-left 0.3s ease;
        }

        .sidebar ul li a:hover {
            background-color: #34495e;
            padding-left: 30px;
        }

        .sidebar ul li a i {
            margin-right: 10px;
        }

        header {
            position: fixed;
            top: 0;
            left: 250px;
            width: calc(100% - 250px);
            z-index: 10;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            background-color: none;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
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
        header h1 {
            font-size: 1.8em;
            color: orange;
            font-weight: bold;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.8);
        }

        .dashboard-container {
            margin-left: 270px;
            margin-top: 100px;
            padding: 20px;
            width: calc(100% - 270px);
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

        .chart-container {
            margin: 20px 0;
            background: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        canvas {
            width: 90% !important; /* Reduce the width of the chart */
            height: 300px !important; /* Set a fixed height for the chart */
            display: block;
            margin: 0 auto; /* Center align the canvas */
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
            position: fixed; /* Fixe le footer */
            bottom: 0; /* Place le footer au bas de la page */
            left: 0;
            width: 100%; /* Prend toute la largeur de la page */
            box-shadow: 0 -2px 5px rgba(0, 0, 0, 0.2); /* Ajoute une ombre légère */
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

<!-- Sidebar -->
<div class="sidebar">
    <h2>Menu Admin</h2>
    <ul>
        <li><a href="manage_abonnements.php"><i>📅</i> Gérer les Abonnements</a></li>
        <li><a href="manage_utilisateurs.php"><i>👤</i> Gérer les Utilisateurs</a></li>
        <li><a href="manage_reservations.php"><i>📑</i> Gérer les Réservations</a></li>
        <li><a href="manage_avis.php"><i>⭐</i> Gérer les Avis</a></li>
        <li><a href="manage_animaux.php"><i>🐾</i> Gérer les Animaux</a></li>
        <li><a href="manage_faq.php"><i>❓</i> Gérer la FAQ</a></li>
        <li><a href="manage_paiements.php"><i>💳</i> Gérer les Paiements</a></li>
        <li><a href="manage_hebergements.php"><i>🏠</i> Gérer les Hébergements</a></li>
        <li><a href="?logout=1"><i>🚪</i> Déconnexion</a></li>
    </ul>
</div>

<!-- Header -->
<header>
    <h1 class="header-slogan">Tableau de Bord Administrateur</h1>
</header>

<!-- Dashboard Content -->
<div class="dashboard-container">
    <div class="chart-container">
        <h3>Évolution des Utilisateurs</h3>
        <canvas id="usersChart"></canvas>
    </div>

    <div class="chart-container">
        <h3>Évolution des Réservations</h3>
        <canvas id="reservationsChart"></canvas>
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
    // Render User Chart
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
            maintainAspectRatio: true, // Keep the charts smaller and proportional
            plugins: {
                legend: {
                    position: 'top'
                }
            },
            scales: {
                x: {
                    ticks: {
                        maxRotation: 45,
                        minRotation: 0
                    }
                },
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Render Reservations Chart
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
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    position: 'top'
                }
            },
            scales: {
                x: {
                    ticks: {
                        maxRotation: 45,
                        minRotation: 0
                    }
                },
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>

</body>
</html>
