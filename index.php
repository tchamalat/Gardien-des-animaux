<?php
include 'config.php'; 
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gardien des Animaux</title>
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
                <button class="btn" onclick="window.location.href='create_account.php'">Créer un compte</button>
                <button class="btn" onclick="window.location.href='login.html'">Je me connecte</button>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero">
        <h1 class="texte">Bienvenue sur Gardien des Animaux</h1>
        <button onclick="redirectToSearch()">Trouver un gardien</button>
    </section>

    <!-- Section Gardien -->
    <section class="gardiens">
        <h2 class="texte">Découvrez nos gardiens disponibles :</h2>
        <p class="texte">Ils sont prêts à offrir amour, soins et attention à vos animaux.</p>
        <div id="gardiens-container" class="gardiens-container">
            <p class="texte">Chargement des gardiens en cours... Merci de patienter.</p>
        </div>
    </section>
    <script>
        async function fetchGardiens() {
            const gardiensContainer = document.getElementById('gardiens-container');

            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(async (position) => {
                    const { latitude, longitude } = position.coords;

                    // Appel à l'API fetch_gardiens.php
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
                            <div class="gardien">
                                <img src="images/${gardien.profile_picture || 'default.jpg'}" alt="${gardien.prenom}">
                                <p>${gardien.prenom} (${gardien.nom_utilisateur}) - ${gardien.distance.toFixed(2)} km</p>
                            </div>
                        `).join('');
                    }
                }, (error) => {
                    gardiensContainer.innerHTML = `<p>Erreur de géolocalisation : ${error.message}</p>`;
                });
            } else {
                gardiensContainer.innerHTML = `<p>La géolocalisation n'est pas prise en charge par votre navigateur.</p>`;
            }
        }

        // Charger les gardiens dès que la page est prête
        document.addEventListener('DOMContentLoaded', fetchGardiens);
    </script>

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
            console.log(Latitude: ${userLatitude}, Longitude: ${userLongitude});
        }

        function redirectToSearch() {
            if (userLatitude && userLongitude) {
                window.location.href = search_page_index.php?latitude=${userLatitude}&longitude=${userLongitude};
            } else {
                alert("La localisation n'est pas disponible.");
            }
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
        document.addEventListener('DOMContentLoaded', getLocation);
    </script>

</body>
</html>
