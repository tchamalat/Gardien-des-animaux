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

// Récupération des statistiques
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

// Préparation des données pour les graphiques
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
            $values[] = 0; // Valeur par défaut
        }
    }

    return ['labels' => $labels, 'values' => $values];
}

$userChartData = transformDataForChart($userEvolution);
$reservationChartData = transformDataForChart($reservationEvolution);
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
            background-color: #f5f5f5;
        }

        .sidebar {
            width: 250px;
            height: 100vh;
            background-color: #2c3e50;
            color: white;
            position: fixed;
            top: 0;
            left: 0;
            padding: 20px;
            display: flex;
            flex-direction: column;
        }

        .sidebar h2 {
            font-size: 1.5rem;
            color: orange;
            margin-bottom: 30px;
            text-align: center;
        }

        .sidebar ul {
            list-style: none;
            padding: 0;
        }

        .sidebar ul li {
            margin: 10px 0;
        }

        .sidebar ul li a {
            color: white;
            text-decoration: none;
            padding: 10px 15px;
            border-radius: 5px;
            display: block;
            transition: background-color 0.3s;
        }

        .sidebar ul li a:hover {
            background-color: #34495e;
        }

        .dashboard-container {
            margin-left: 270px;
            padding: 20px;
        }

        .chart-container {
            margin: 20px 0;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.1);
        }

        canvas {
            max-width: 100%;
            height: 400px;
        }
    </style>
</head>
<body>

<!-- Barre latérale -->
<div class="sidebar">
    <h2>Menu Admin</h2>
    <ul>
        <li><a href="manage_abonnements.php">Gérer les Abonnements</a></li>
        <li><a href="manage_utilisateurs.php">Gérer les Utilisateurs</a></li>
        <li><a href="manage_reservations.php">Gérer les Réservations</a></li>
        <li><a href="manage_avis.php">Gérer les Avis</a></li>
        <li><a href="manage_animaux.php">Gérer les Animaux</a></li>
        <li><a href="manage_faq.php">Gérer la FAQ</a></li>
        <li><a href="manage_paiements.php">Gérer les Paiements</a></li>
        <li><a href="manage_hebergements.php">Gérer les Hébergements</a></li>
        <li><a href="?logout=1">Déconnexion</a></li>
    </ul>
</div>

<!-- Contenu principal -->
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

<script>
    // Données pour le graphique des utilisateurs
    const userChartData = <?php echo json_encode($userChartData); ?>;
    const reservationChartData = <?php echo json_encode($reservationChartData); ?>;

    // Graphique des utilisateurs
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
        }
    });

    // Graphique des réservations
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
        }
    });
</script>

</body>
</html>
