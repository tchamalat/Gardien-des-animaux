<?php  
include 'config.php'; // Connexion à la base de données
session_start();

// Gestion des requêtes AJAX pour la discussion et les gardiens
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    // Garder la logique pour récupérer les gardiens
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
            echo '<div class="gardien">';
            echo '<img src="images/' . htmlspecialchars($gardien['profile_picture']) . '" alt="' . htmlspecialchars($gardien['prenom']) . '">';
            echo '<p><strong>' . htmlspecialchars($gardien['prenom']) . '</strong> (' . htmlspecialchars($gardien['nom_utilisateur']) . ')</p>';
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
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Styles pour la fenêtre de discussion */
        #chatButton {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background-color: #f5a623;
            color: white;
            border: none;
            border-radius: 50%;
            width: 60px;
            height: 60px;
            font-size: 24px;
            cursor: pointer;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        #chatWindow {
            display: none;
            position: fixed;
            bottom: 90px;
            right: 20px;
            width: 300px;
            height: 400px;
            background-color: white;
            border: 1px solid #ccc;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            display: flex;
            flex-direction: column;
        }

        #chatHeader {
            background-color: #f5a623;
            color: white;
            padding: 10px;
            text-align: center;
        }

        #chatMessages {
            flex: 1;
            padding: 10px;
            overflow-y: auto;
            background-color: #f9f9f9;
        }

        #chatInput {
            display: flex;
            border-top: 1px solid #ccc;
        }

        #chatInput input {
            flex: 1;
            padding: 10px;
            border: none;
            outline: none;
        }

        #chatInput button {
            padding: 10px;
            background-color: #f5a623;
            color: white;
            border: none;
            cursor: pointer;
        }
    </style>
</head>
<body>

    <!-- Header -->
    <header>
        <div class="header-container">
            <img src="images/logo.png" alt="Logo Gardien des Animaux">
            <div class="auth-buttons">
                <?php
                if (isset($_SESSION['role'])) {
                    if ($_SESSION['role'] == 0) {
                        echo '<button class="btn" onclick="window.location.href=\'profil_gardien.php\'">Mon Profil</button>';
                    } elseif ($_SESSION['role'] == 1) {
                        echo '<button class="btn" onclick="window.location.href=\'profil.php\'">Mon Profil</button>';
                    }
                } else {
                    echo '<button class="btn" onclick="window.location.href=\'login.php\'">Mon Profil</button>';
                }
                ?>
                <button class="btn" onclick="window.location.href='mes_reservations.php'">Mes réservation</button>
                <button class="btn" onclick="window.location.href='discussion.php'">Discussion</button>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero">
        <img src="images/premierplan.png" alt="Un foyer chaleureux">
    </section>

    <!-- Avis Section -->
    <section class="avis-section">
        <h3>Avis</h3>
        <div class="avis-list">
            <?php
            $query = "SELECT avis.review, avis.rating, avis.date_created, creation_compte.nom_utilisateur 
                      FROM avis 
                      JOIN creation_compte ON avis.user_id = creation_compte.id 
                      ORDER BY avis.date_created DESC LIMIT 3";
            $result = $conn->query($query);

            while ($row = $result->fetch_assoc()) {
                echo "<div class='avis'>";
                echo "<p>" . htmlspecialchars($row['nom_utilisateur']) . " :</p>";
                echo "<p>" . htmlspecialchars($row['review']) . "</p>";
                echo "<span>" . htmlspecialchars($row['rating']) . " / 5 <img src='images/star.png' alt='étoile'></span>";
                echo "</div>";
            }
            ?>
        </div>
        <button class="voir-plus" onclick="window.location.href='leave_review.php'">Laisser un avis</button>
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
        // Récupération des gardiens en fonction de la localisation
        function getLocationAndFetchGardiens() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    (position) => {
                        fetch('index_connect.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                            },
                            body: JSON.stringify({
                                latitude: position.coords.latitude,
                                longitude: position.coords.longitude,
                            }),
                        })
                            .then(response => response.text())
                            .then(data => {
                                document.querySelector('.gardien-list').innerHTML = data;
                            })
                            .catch(error => console.error('Erreur :', error));
                    },
                    (error) => {
                        alert("Impossible de récupérer votre position. Vérifiez les autorisations de votre navigateur.");
                    }
                );
            } else {
                alert("La géolocalisation n'est pas supportée par votre navigateur.");
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 1): ?>
            getLocationAndFetchGardiens();
            <?php endif; ?>
        });
    </script>

    <script>
</body>
</html>

<script>
function getUserLocation() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function (position) {
            const latitude = position.coords.latitude;
            const longitude = position.coords.longitude;

            // Envoyer les coordonnées au serveur
            fetch('save_location.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ latitude: latitude, longitude: longitude })
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    console.log('Location saved successfully:', data.message);
                } else {
                    console.error('Error saving location:', data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }, function (error) {
            console.error('Error retrieving location:', error.message);
        });
    } else {
        console.error('Geolocation is not supported by this browser.');
    }
}

// Appeler la fonction après que l'utilisateur se connecte
window.onload = getUserLocation;
</script>
