z<?php
include 'config.php'; 
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gardien des Animaux</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        header {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 10;
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: none; /* Retirer tout fond */
        }

        .header-container {
            width: 100%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: none; /* Retirer tout fond */
        }

        body {
            margin: 0;
            padding: 0;
            min-height: 100vh;
            background: url('images/premierplan.jpg') no-repeat center center fixed; 
            background-size: cover;
            color: #fff;
        }

        .hero {
            position: relative;
            width: 100%;
            height: 100vh; /* Hauteur complète de l'écran */
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: hidden;
        }    

        .hero img {
            width: 100%;
            height: 100vh;
        object-fit: cover;
        }

        footer {
            background-color: transparent;
            color: #fff;
        }


        .gardien p {
            margin: 5px 0;
        }

        .gardien p.distance {
            font-size: 0.9em;
            color: #555;
        }
        header img {
            height: 100px; /* Ajuster la taille si nécessaire */
            max-width: 200px;
            display: block;
            background: none; /* Pas de fond pour le logo */
            padding: 0;
            border-radius: 0;
            box-shadow: none;
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
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
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

            console.log(`Latitude: ${userLatitude}, Longitude: ${userLongitude}`); // Ajout pour vérifier les coordonnées

            fetch('fetch_gardiens.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ latitude: userLatitude, longitude: userLongitude }),
            })
            .then(response => response.json())
            .then(data => {
                console.log(data); // Ajout pour vérifier la réponse du serveur
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
    <form id="locationForm" action="search_page_index.php" method="GET" style="display: none;">
        <input type="hidden" name="latitude" id="latitude">
        <input type="hidden" name="longitude" id="longitude">
    </form>
    <script>
        function redirectToSearch() {
            const form = document.getElementById('locationForm');
            if (userLatitude && userLongitude) {
                document.getElementById('latitude').value = userLatitude;
                document.getElementById('longitude').value = userLongitude;
                form.submit();
            } else {
                alert("La localisation n'est pas disponible.");
            }
        }
    </script>
    
            <button class="btn btn-hero" onclick="window.location.href='search_page_index.php'">Trouver un gardien</button>
        </div>
    </section>

    <!-- Section Gardien -->
    <section class="gardiens">
        <h2>Découvrez nos gardiens disponibles :</h2>
        <p>Ils sont prêts à offrir amour, soins et attention à vos animaux.</p>
        <div id="gardiens-container">
            <p>Chargement des gardiens en cours... Merci de patienter.</p>
        </div>
    </section>

    <!-- Avis Section -->
    <section class="avis-section">
        <h2>Ce que disent nos utilisateurs</h2>
        <p>Vos retours sont précieux et aident à améliorer nos services.</p>
        <div class="avis-list">
            <?php
            $query = "SELECT avis.review, avis.rating, avis.date_created, creation_compte.nom_utilisateur 
                      FROM avis 
                      JOIN creation_compte ON avis.user_id = creation_compte.id 
                      ORDER BY avis.date_created DESC LIMIT 3";
            $result = $conn->query($query);

            while ($row = $result->fetch_assoc()) {
                echo "<div class='avis'>";
                echo "<p><strong>" . htmlspecialchars($row['nom_utilisateur']) . " :</strong></p>";
                echo "<p>“" . htmlspecialchars($row['review']) . "”</p>";
                echo "<span>" . htmlspecialchars($row['rating']) . " / 5 <img src='images/star.png' alt='étoile'></span>";
                echo "</div>";
            }
            ?>
        </div>
        <button class="voir-plus" onclick="window.location.href='leave_review.php'">
            Laisser un avis
        </button>
    </section>


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


</body>
</html>
