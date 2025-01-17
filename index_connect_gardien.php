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
            color: #333;
            background: url('images/premierplan.png') no-repeat center center fixed;
            background-size: cover;
        }

        header {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 10;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 20px;
            background: none; /* Supprimez la couleur de fond */
            box-shadow: none; /* Supprimez l'ombre */
        }

        header img {
            height: 150px;
            max-width: 170px;
        }


        header .auth-buttons {
            display: flex;
            gap: 15px;
        }
	    @media (max-width: 600px) { 
        		.auth-buttons {
            		flex-direction: column; 
        	    	gap: 15px; 
    		    }
	    }

        header .auth-buttons .btn {
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

        header .auth-buttons .btn:hover {
            background-color: #ff7f00;
            transform: translateY(-3px);
        }

        .hero {
            height: 60vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: none;
        }

        .hero h1 {
            color: orange;
            font-size: 2.5em;
            text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.5);
            background: none;
        }

        .proprietaires-section {
            max-width: 1200px;
            margin: 40px auto;
            padding: 20px;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
        }

        .proprietaires-section h2 {
            font-size: 1.8em;
            color: orange;
            text-align: center;
            margin-bottom: 20px;
        }

        .proprietaires-list {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
        }

        .proprietaire-card {
            background: #f9f9f9;
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .proprietaire-card img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            object-fit: cover;
            margin-bottom: 10px;
        }

        .proprietaire-card p {
            margin: 5px 0;
        }

        .proprietaire-card .distance {
            font-size: 0.9em;
            color: gray;
        }

        .contact-btn {
            margin-top: 10px;
            background-color: orange;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 8px;
            font-size: 1em;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .contact-btn:hover {
            background-color: #ff7f00;
            transform: translateY(-3px);
        }

        ul {
            margin-top: 20px;
            padding-left: 20px;
            list-style: none;
        }

        ul li {
            position: relative;
            padding-left: 25px;
            margin-bottom: 10px;
        }

        ul li:before {
            content: '✔';
            position: absolute;
            left: 0;
            top: 0;
            color: orange;
            font-weight: bold;
        }

        footer {
            background: rgba(0, 0, 0, 0.85);
            color: #fff;
            padding: 20px;
            margin-top: 50px;
        }

        footer .footer-links {
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
        }

        footer .footer-links h4 {
            color: orange;
            margin-bottom: 10px;
        }

        footer .footer-links ul {
            list-style: none;
            padding: 0;
        }

        footer .footer-links a {
            color: white;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        footer .footer-links a:hover {
            color: orange;
        }
    </style>
</head>
<body>

<!-- Header -->
<header>
    <img src="images/logo.png" alt="Logo Gardien des Animaux">
    <div class="auth-buttons">
        <button class="btn" onclick="window.location.href='profil_gardien.php'">Mon Profil</button>
        <button class="btn" onclick="window.location.href='mes_reservations.php'">Mes réservations</button>
        <button class="btn" onclick="window.location.href='messages/index.php'">Discussion</button>
    </div>
</header>

<!-- Hero Section -->
<section class="hero">
    <h1>Bienvenue, Gardien</h1>
</section>

<!-- Section Propriétaires Disponibles -->
<section class="proprietaires-section">
    <h2>Propriétaires Disponibles</h2>
    <div class="proprietaires-list">
        <p>Chargement des propriétaires en cours...</p>
    </div>
</section>
    
<script>
    async function fetchProprietaires() {
        const proprietairesList = document.querySelector('.proprietaires-list');
        proprietairesList.innerHTML = '<p>Chargement des propriétaires en cours...</p>';
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(async (position) => {
                const { latitude, longitude } = position.coords;
                try {
                    const response = await fetch('fetch_proprietaires.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ latitude, longitude })
                    });

                    const data = await response.json();

                    if (data.error) {
                        proprietairesList.innerHTML = `<p>Erreur : ${data.error}</p>`;
                    } else {
                        if (data.length === 0) {
                            proprietairesList.innerHTML = '<p>Aucun propriétaire trouvé près de chez vous.</p>';
                        } else {
                            proprietairesList.innerHTML = data.map(proprietaire => `
                                <div class="proprietaire-card">
                                    <img src="${proprietaire.profile_picture}" alt="${proprietaire.prenom}">
                                    <p><strong>${proprietaire.prenom}</strong> (${proprietaire.nom_utilisateur})</p>
                                    <p>${proprietaire.ville}</p>
                                    <p class="distance">Distance : ${proprietaire.distance.toFixed(2)} km</p>
                                    <button class="contact-btn" onclick="window.location.href='messages/index.php?user_id=${proprietaire.id}'">Contacter</button>
                                </div>
                            `).join('');
                        }
                    }
                } catch (error) {
                    console.error('Erreur lors de la récupération des propriétaires :', error);
                    proprietairesList.innerHTML = '<p>Erreur lors du chargement des propriétaires. Veuillez réessayer plus tard.</p>';
                }
            }, (error) => {
                proprietairesList.innerHTML = `<p>Erreur de géolocalisation : ${error.message}</p>`;
            });
        } else {
            proprietairesList.innerHTML = '<p>La géolocalisation n\'est pas prise en charge par votre navigateur.</p>';
        }
    }

    document.addEventListener('DOMContentLoaded', fetchProprietaires);
</script>

<!-- Footer -->
<footer>
    <div class="footer-links">
        <div>
            <h4>En savoir plus :</h4>
            <ul>
                <li><a href="securite_connect_gardien.php">Sécurité</a></li>
                <li><a href="aide_connect_gardien.php">Centre d'aide</a></li>
            </ul>
        </div>
        <div>
            <h4>A propos de nous :</h4>
            <ul>
                <li><a href="confidentialite_connect_gardien.php">Politique de confidentialité</a></li>
                <li><a href="contact_connect_gardien.php">Nous contacter</a></li>
            </ul>
        </div>
        <div>
            <h4>Conditions Générales :</h4>
            <ul>
                <li><a href="conditions_connect_gardien.php">Conditions d'utilisateur et de Service</a></li>
            </ul>
        </div>
    </div>
</footer>

</body>
</html>
