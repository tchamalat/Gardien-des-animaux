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

    $stmtAbonnements = $pdo->query("SELECT COUNT(*) as total FROM Abonnement");
    $totalAbonnements = $stmtAbonnements->fetch()['total'];

    $stmtAnimaux = $pdo->query("SELECT COUNT(*) as total FROM Animal");
    $totalAnimaux = $stmtAnimaux->fetch()['total'];
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
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            margin: 0;
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

        .stats-cards {
            display: flex;
            justify-content: space-between; 
            align-items: center; 
            gap: 20px;
            margin-bottom: 30px;
            flex-wrap: nowrap;
        }

        .stat-card {
            flex: 1; 
            background-color: orange;
            color: white;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            max-width: 250px; 
        }


        .stat-card h3 {
            margin: 0;
            font-size: 1.5rem;
        }

        canvas {
            max-width: 100%;
            margin: 20px auto;
            display: block;
            height: 400px;
        }
    </style>
</head>
<body>

<!-- Barre latérale -->
<div class="sidebar">
    <h2>Menu Admin</h2>
    <ul>
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
    <div class="stats-cards">
        <div class="stat-card">
            <h3><?= $totalUsers ?></h3>
            <p>Utilisateurs</p>
        </div>
        <div class="stat-card">
            <h3><?= $totalReservations ?></h3>
            <p>Réservations</p>
        </div>
        <div class="stat-card">
            <h3><?= $totalAnimaux ?></h3>
            <p>Animaux</p>
        </div>
    </div>

    <canvas id="adminChart"></canvas>
</div>

<script>
    // Données pour le graphique
    const data = {
        labels: ['Utilisateurs', 'Réservations', 'Abonnements', 'Animaux'],
        datasets: [{
            label: 'Statistiques',
            data: [<?= $totalUsers ?>, <?= $totalReservations ?>, <?= $totalAbonnements ?>, <?= $totalAnimaux ?>],
            backgroundColor: ['#f4840c', '#e96d0c', '#ffa726', '#fb8c00'],
            borderColor: ['#d45a00', '#d45a00', '#f57c00', '#ef6c00'],
            borderWidth: 1
        }]
    };

    const config = {
        type: 'bar',
        data: data,
        options: {
            responsive: true,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    };

    // Rendu du graphique
    const ctx = document.getElementById('adminChart').getContext('2d');
    new Chart(ctx, config);
</script>

</body>
</html>
