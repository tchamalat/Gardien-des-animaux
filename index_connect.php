<?php  
include 'config.php'; 
session_start();

// Gestion des requêtes AJAX pour la discussion et les gardiens
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);

    if (isset($input['latitude']) && isset($input['longitude']) && isset($_SESSION['role']) && $_SESSION['role'] == 1) { 
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
            height: 60px;
        }

        .auth-buttons .btn {
            background-color: orange;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            text-decoration: none;
        }

        .hero {
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .hero img {
            width: 100%;
            max-height: 300px;
            object-fit: cover;
        }

        .gardiens {
            padding: 20px;
        }

        .gardien-card {
            background: rgba(255, 255, 255, 0.9);
            padding: 15px;
            border-radius: 10px;
            margin: 10px 0;
            color: #333;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

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
            color: white;
            text-decoration: none;
        }
    </style>
</head>
<body>

    <!-- Header -->
    <header>
        <img src="images/logo.png" alt="Logo">
        <div class="auth-buttons">
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
        <img src="images/premierplan.png" alt="Un foyer chaleureux">
    </section>

    <!-- Section Gardien -->
    <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 1): ?>
    <section class="gardiens">
        <h2>Gardiens près de chez vous :</h2>
        <div class="gardien-list">
            <p>Chargement des gardiens en fonction de votre position...</p>
        </div>
    </section>
    <?php endif; ?>

    <!-- Avis Section -->
    <section class="avis-section">
        <h2>Avis</h2>
        <div class="avis-list">
            <?php
            $query = "SELECT avis.review, avis.rating, avis.date_created, creation_compte.nom_utilisateur 
                      FROM avis 
                      JOIN creation_compte ON avis.user_id = creation_compte.id 
                      ORDER BY avis.date_created DESC LIMIT 3";
            $result = $conn->query($query);

            while ($row = $result->fetch_assoc()) {
                echo "<div class='gardien-card'>";
                echo "<p><strong>" . htmlspecialchars($row['nom_utilisateur']) . "</strong> : " . htmlspecialchars($row['review']) . "</p>";
                echo "<p>Note : " . htmlspecialchars($row['rating']) . " / 5</p>";
                echo "</div>";
            }
            ?>
        </div>
        <button class="btn" onclick="window.location.href='leave_review.php'">Laisser un avis</button>
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

    <script>
        function fetchGardiens() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(position => {
                    fetch('index_connect.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            latitude: position.coords.latitude,
                            longitude: position.coords.longitude
                        })
                    })
                    .then(response => response.text())
                    .then(data => {
                        document.querySelector('.gardien-list').innerHTML = data;
                    });
                });
            }
        }

        document.addEventListener('DOMContentLoaded', fetchGardiens);
    </script>
</body>
</html>
