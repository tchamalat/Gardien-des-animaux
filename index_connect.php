<?php  
include 'config.php'; 
session_start();

// Gestion des requêtes AJAX pour les gardiens
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);

    if (isset($input['latitude'], $input['longitude'], $_SESSION['role']) && $_SESSION['role'] == 1) { 
        $user_latitude = floatval($input['latitude']);
        $user_longitude = floatval($input['longitude']);
        $radius = 10;

        $gardiens_query = $conn->prepare("
            SELECT 
                id, prenom, nom_utilisateur, profile_picture, latitude, longitude,
                (6371 * ACOS(COS(RADIANS(?)) * COS(RADIANS(latitude)) * COS(RADIANS(longitude) - RADIANS(?)) + SIN(RADIANS(?)) * SIN(RADIANS(latitude)))) AS distance
            FROM creation_compte
            WHERE role = 0
            HAVING distance <= ?
            ORDER BY distance ASC
        ");
        $gardiens_query->bind_param("dddi", $user_latitude, $user_longitude, $user_latitude, $radius);
        $gardiens_query->execute();
        $gardiens_result = $gardiens_query->get_result();

        while ($gardien = $gardiens_result->fetch_assoc()) {
            echo '<div class="gardien-card">';
            echo '<img src="images/' . htmlspecialchars($gardien['profile_picture'] ?? 'default.jpg') . '" alt="' . htmlspecialchars($gardien['prenom']) . '">';
            echo '<h3>' . htmlspecialchars($gardien['prenom']) . '</h3>';
            echo '<p>' . htmlspecialchars($gardien['nom_utilisateur']) . '</p>';
            echo '<p class="distance">Distance : ' . round($gardien['distance'], 2) . ' km</p>';
            echo '</div>';
        }
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gardien des Animaux - Connecté</title>
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

        /* Header */
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
            height: 120px;
            max-width: 200px;
        }

        .auth-buttons {
            display: flex;
            gap: 15px;
        }

        .auth-buttons .btn {
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

        .auth-buttons .btn:hover {
            background-color: #ff7f00;
            transform: translateY(-3px);
        }

        /* Hero Section */
        .hero {
            height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
        }

        .hero h1 {
            color: orange;
            font-size: 2em;
        }

        .hero button {
            margin-top: 20px;
            background-color: orange;
            color: white;
            padding: 15px 30px;
            border: none;
            border-radius: 8px;
            font-size: 1.2em;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .hero button:hover {
            background-color: #ff7f00;
            transform: translateY(-3px);
        }

        /* Section Gardiens */
        .gardiens {
            background: transparent;
            padding: 50px 20px;
            text-align: center;
        }

        .gardiens h2 {
            color: orange;
            margin-bottom: 20px;
        }

        .gardiens-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
        }

        .gardien-card {
            background: rgba(255, 255, 255, 0.9);
            color: #333;
            border-radius: 8px;
            padding: 20px;
            width: 250px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .gardien-card img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
        }

        .gardien-card h3 {
            margin: 10px 0;
            font-size: 18px;
        }

        .gardien-card p {
            margin: 5px 0;
        }

        /* Avis Section */
        .avis-section {
            background-color: transparent;
            padding: 50px 20px;
            text-align: center;
        }

        .avis-section h2 {
            color: orange;
            margin-bottom: 20px;
        }

        .avis {
            background-color: #f3e3cd;
            border-radius: 20px;
            padding: 20px;
            border: 3px solid #f5a623;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
            margin-bottom: 20px;
        }

        .avis img {
            height: 50px;
            width: 50px;
            border-radius: 50%;
            background-color: white;
            padding: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .avis span {
            font-size: 1em;
            color: #333;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        /* Footer */
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

    <!-- Header -->
    <header>
        <img src="images/logo.png" alt="Logo Gardien des Animaux">
        <div class="auth-buttons">
            <button class="btn" onclick="window.location.href='search_page.php'">Trouver un gardien</button>
            <button class="btn" onclick="window.location.href='discussion_gardien.php'">Discussion</button>
            <?php
            if (isset($_SESSION['role'])) {
                if ($_SESSION['role'] == 0) {
                    echo '<button class="btn" onclick="window.location.href=\'profil_gardien.php\'">Mon Profil</button>';
                } elseif ($_SESSION['role'] == 1) {
                    echo '<button class="btn" onclick="window.location.href=\'profil.php\'">Mon Profil</button>';
                }
            }
            ?>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero">
        <h1>Bienvenue sur Gardien des Animaux</h1>
    </section>

    <!-- Section Gardien -->
    <?php if ($_SESSION['role'] == 1): ?>
    <section class="gardiens">
        <h2>Découvrez nos gardiens disponibles :</h2>
        <div id="gardiens-container" class="gardiens-container">
            <p>Chargement des gardiens en cours... Merci de patienter.</p>
        </div>
    </section>
    <script>
        async function fetchGardiens() {
            const gardiensContainer = document.getElementById('gardiens-container');
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(async (position) => {
                    const { latitude, longitude } = position.coords;
                    const response = await fetch('fetch_gardiens.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ latitude, longitude })
                    });

                    const data = await response.json();
                    if (data.error) {
                        gardiensContainer.innerHTML = `<p>Erreur : ${data.error}</p>`;
                    } else {
                        gardiensContainer.innerHTML = data.map(gardien => `
                            <div class="gardien-card">
                                <img src="images/${gardien.profile_picture || 'default.jpg'}" alt="${gardien.prenom}">
                                <h3>${gardien.prenom}</h3>
                                <p>${gardien.nom_utilisateur}</p>
                                <p>Distance : ${gardien.distance.toFixed(2)} km</p>
                            </div>
                        `).join('');
                    }
                });
            }
        }
        document.addEventListener('DOMContentLoaded', fetchGardiens);
    </script>
    <?php endif; ?>

    <!-- Avis Section -->
    <section class="avis-section">
        <h2>Ce que disent nos utilisateurs</h2>
        <div class="avis-list">
            <?php
            $query = "SELECT avis.review, avis.rating, avis.date_created, creation_compte.nom_utilisateur 
                      FROM avis 
                      JOIN creation_compte ON avis.user_id = creation_compte.id 
                      ORDER BY avis.date_created DESC LIMIT 3";
            $result = $conn->query($query);

            while ($row = $result->fetch_assoc()) {
                echo "<div class='avis'>";
                echo "<span>" . htmlspecialchars($row['nom_utilisateur']) . "</span>";
                echo "<p>" . htmlspecialchars($row['review']) . "</p>";
                echo "<span>Note : " . htmlspecialchars($row['rating']) . " / 5</span>";
                echo "</div>";
            }
            ?>
        </div>
    </section>

    <!-- Footer -->
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
</body>
</html>
