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
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            color: #fff;
            min-height: 100vh;
            overflow-x: hidden;
        }

        body::before {
            content: "";
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('images/premierplan.png') no-repeat center center fixed;
            background-size: cover;
            z-index: -1;
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

        section {
            padding: 100px 20px;
            text-align: center;
        }

        .hero {
            height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
        }

        .hero button {
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

        .gardiens {
            background: transparent; /* Suppression de la bande noire */
            color: #fff;
        }
        .gardiens-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
            padding: 20px;
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
        .avis-section {
            background-color: transparent;
            padding: 50px 20px;
        }

        .avis-list {
            display: flex;
            flex-direction: column;
            gap: 30px;
	        align-items: center;
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
        }
        .avis img {
            height: 50px; /* Réduction de la taille de l'étoile */
            width: 50px;
            border-radius: 50%; /* Ajout d'un cercle autour de l'étoile */
            background-color: white; /* Fond blanc pour le cercle */
            padding: 5px; /* Ajout d'un espace autour de l'étoile */
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .avis span {
            font-size: 1em;
            color: #333;
            display: flex;
            align-items: center;
            gap: 10px; /* Espacement entre l'étoile et la note */
        }

        .avis span p {
            margin: 0; /* Suppression des marges inutiles */
            font-weight: bold;
            font-size: 1.2em; /* Taille de la note */
        }
	    .voir-plus {
	        background-color: #f5a623;
    	    color: white;
    	    padding: 15px 30px;
	        border: none;
    	    border-radius: 8px;
    	    cursor: pointer;
    	    font-size: 1.2em;
    	    transition: background-color 0.3s ease, transform 0.3s ease;
    	    margin-top: 30px;
	    }
	    .voir-plus:hover {
    	    background-color: #ff7f00;
    	    transform: translateY(-5px);
	    }
        .texte {
            color: orange; 
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
                <button class="btn" onclick="window.location.href='search_page.php'">Trouver un gardien</button>
                <button class="btn" onclick="window.location.href='discussion_gardien.php'">Discussion</button>
            </div>

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
        // Gestion de l'affichage/masquage de la fenêtre de chat
        document.getElementById('chatButton').addEventListener('click', function () {
            const chatWindow = document.getElementById('chatWindow');
            if (chatWindow.style.display === 'none' || chatWindow.style.display === '') {
                chatWindow.style.display = 'flex'; // Affiche la fenêtre
            } else {
                chatWindow.style.display = 'none'; // Masque la fenêtre
            }
        });

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
