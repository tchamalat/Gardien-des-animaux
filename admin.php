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
        /* Vos styles existants */
        /* Ajout d'un espace pour les graphiques */
        .chart-container {
            margin-top: 20px;
        }

        canvas {
            max-width: 100%;
            height: auto;
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
        <h2>Bienvenue <?php echo htmlspecialchars($_SESSION['admin']); ?> !</h2>
    </div>

    <div class="stats-cards">
        <!-- Carte Utilisateurs -->
        <div class="stats-card">
            <h3>Utilisateurs</h3>
            <p>Nombre total : <?php echo $totalUsers; ?></p>
            <div class="chart-container">
                <canvas id="usersChart"></canvas>
            </div>
        </div>

        <!-- Carte Réservations -->
        <div class="stats-card">
            <h3>Réservations</h3>
            <p>En cours : <?php echo $totalReservations; ?></p>
            <div class="chart-container">
                <canvas id="reservationsChart"></canvas>
            </div>
        </div>

        <!-- Carte Abonnements -->
        <div class="stats-card">
            <h3>Abonnements</h3>
            <p>Actifs : <?php echo $totalAbonnements; ?></p>
            <div class="chart-container">
                <canvas id="subscriptionsChart"></canvas>
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

<!-- Script pour les graphiques -->
<script>
    // Données fictives pour les graphiques
    const userStats = [10, 20, 50, 100, <?php echo $totalUsers; ?>]; // Remplacer par des données réelles
    const reservationStats = [5, 15, 25, 30, <?php echo $totalReservations; ?>];
    const subscriptionStats = [2, 5, 10, 15, <?php echo $totalAbonnements; ?>];

    // Graphique Utilisateurs
    const ctxUsers = document.getElementById('usersChart').getContext('2d');
    new Chart(ctxUsers, {
        type: 'line',
        data: {
            labels: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai'],
            datasets: [{
                label: 'Utilisateurs',
                data: userStats,
                borderColor: 'orange',
                fill: false,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false }
            }
        }
    });

    // Graphique Réservations
    const ctxReservations = document.getElementById('reservationsChart').getContext('2d');
    new Chart(ctxReservations, {
        type: 'line',
        data: {
            labels: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai'],
            datasets: [{
                label: 'Réservations',
                data: reservationStats,
                borderColor: 'blue',
                fill: false,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false }
            }
        }
    });

    // Graphique Abonnements
    const ctxSubscriptions = document.getElementById('subscriptionsChart').getContext('2d');
    new Chart(ctxSubscriptions, {
        type: 'line',
        data: {
            labels: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai'],
            datasets: [{
                label: 'Abonnements',
                data: subscriptionStats,
                borderColor: 'green',
                fill: false,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false }
            }
        }
    });
</script>
</body>
</html>

