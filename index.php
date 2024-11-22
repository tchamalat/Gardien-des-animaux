<?php
include 'config.php'; // Connexion à la base de données
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gardien des Animaux</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        #gardiens-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
            margin-top: 20px;
        }

        .gardien {
            border: 1px solid #ddd;
            border-radius: 10px;
            width: 200px;
            padding: 15px;
            text-align: center;
            background-color: #f9f9f9;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .gardien img {
            width: 100%;
            height: auto;
            border-radius: 50%;
            margin-bottom: 10px;
        }

        .gardien p {
            margin: 5px 0;
        }

        .gardien p.distance {
            font-size: 0.9em;
            color: #555;
        }
    </style>
    <script>
        let userLatitude, userLongitude;

        function getLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(savePosition, showError);
            } else {
                alert("La géolocalisation n'est pas prise en charge par votre navigateur.");
            }
        }

        function savePosition(position) {
            userLatitude = position.coords.latitude;
            userLongitude = position.coords.longitude;

            fetch('fetch_gardiens.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ latitude: userLatitude, longitude: userLongitude }),
            })
            .then(response => response.json())
            .then(data => {
                updateGardiensList(data);
            })
            .catch(error => console.error('Erreur lors de la récupération des gardiens:', error));
        }

        function showError(error) {
            switch (error.code) {
                case error.PERMISSION_DENIED:
                    alert("Vous avez refusé la demande de géolocalisation.");
                    break;
                case error.POSITION_UNAVAILABLE:
                    alert("Les informations de localisation ne sont pas disponibles.");
                    break;
                case error.TIMEOUT:
                    alert("La demande de géolocalisation a expiré.");
                    break;
                default:
                    alert("Une erreur inconnue est survenue.");
                    break;
            }
        }

        function updateGardiensList(gardiens) {
            const gardiensContainer = document.getElementById('gardiens-container');
            gardiensContainer.innerHTML = '';

            if (gardiens.length > 0) {
                gardiens.forEach(gardien => {
                    const gardienElement = document.createElement('div');
                    gardienElement.className = 'gardien';

                    gardienElement.innerHTML = `
                        <img src="images/${gardien.profile_picture}" alt="${gardien.prenom}">
                        <p><strong>${gardien.prenom}</strong> (${gardien.nom_utilisateur})</p>
                        <p class="distance">Distance : ${gardien.distance.toFixed(2)} km</p>
                    `;

                    gardiensContainer.appendChild(gardienElement);
                });
            } else {
                gardiensContainer.innerHTML = '<p>Aucun gardien trouvé dans votre périmètre.</p>';
            }
        }
    </script>
</head>
<body onload="getLocation()">

    <!-- Header -->
    <header>
        <div class="header-container">
            <img src="images/logo.png" alt="Logo Gardien des Animaux">
            <div class="auth-buttons">
                <button class="btn" onclick="window.location.href='create_account.php'">
                    <i class="icon">➕</i> Créer un compte
                </button>
                <button class="btn" onclick="window.location.href='login.html'">
                    <i class="icon">👤</i> Je me connecte
                </button>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero">
        <img src="images/premierplan.png" alt="Un foyer chaleureux">
        <div class="hero-text">
            <button class="btn-hero" onclick="window.location.href='search_page_index.php'">Trouver un gardien</button>
        </div>
    </section>

    <!-- Section Gardien -->
    <section class="gardiens">
        <h2>Nos gardiens près de chez vous :</h2>
        <div id="gardiens-container">
            <p>Chargement des gardiens...</p>
        </div>
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
                    <li>Sécurité</li>
                    <li>Centre d'aide</li>
                </ul>
            </div>
            <div>
                <h4>A propos de nous :</h4>
                <ul>
                    <li>Politique de confidentialité</li>
                    <li>Nous contacter</li>
                </ul>
            </div>
            <div>
                <h4>Conditions Générales :</h4>
                <ul>
                    <li>Conditions de Service</li>
                </ul>
            </div>
        </div>
    </footer>

</body>
</html>
