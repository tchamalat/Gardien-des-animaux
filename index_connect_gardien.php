<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gardien des Animaux - Propriétaires</title>
    <style>
        /* Styles globaux */
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
            overflow-x: hidden;
        }

        header {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 10;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
            background: rgba(255, 255, 255, 0.95);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        header img {
            height: 80px;
        }

        header .auth-buttons {
            display: flex;
            gap: 15px;
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
            background: url('images/premierplan.png') no-repeat center center;
            background-size: cover;
        }

        .hero h1 {
            color: orange;
            font-size: 2.5em;
            text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.5);
            text-align: center;
        }

        .proprietaires-section {
            max-width: 1200px;
            margin: 50px auto;
            padding: 30px;
            background: rgba(255, 255, 255, 0.9);
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
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
        }

        .proprietaire-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 250px;
        }

        .proprietaire-card img {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
        }

        .proprietaire-card p {
            margin: 10px 0;
            font-size: 1em;
        }

        .proprietaire-card .distance {
            font-weight: bold;
            color: orange;
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

        #chatButton {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background-color: orange;
            color: white;
            border: none;
            border-radius: 50%;
            width: 60px;
            height: 60px;
            font-size: 24px;
            cursor: pointer;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
    </style>
</head>
<body>

<header>
    <img src="images/logo.png" alt="Logo Gardien des Animaux">
    <div class="auth-buttons">
        <button class="btn" onclick="window.location.href='profil_gardien.php'">Mon Profil</button>
        <button class="btn" onclick="window.location.href='mes_reservations.php'">Mes réservations</button>
        <button class="btn" onclick="window.location.href='discussion_gardien.php'">Discussion</button>
    </div>
</header>

<section class="hero">
    <h1>Bienvenue, Gardien</h1>
</section>

<section class="proprietaires-section">
    <h2>Propriétaires Disponibles</h2>
    <div class="proprietaires-list">
        <!-- Les propriétaires seront ajoutés dynamiquement ici -->
    </div>
</section>

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
    function fetchProprietaires() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition((position) => {
                fetch('index_connect_gardien.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        latitude: position.coords.latitude,
                        longitude: position.coords.longitude,
                    }),
                })
                .then(response => response.text())
                .then(data => {
                    document.querySelector('.proprietaires-list').innerHTML = data;
                })
                .catch(error => console.error('Erreur:', error));
            }, (error) => {
                console.error('Erreur de géolocalisation:', error.message);
            });
        }
    }

    document.addEventListener('DOMContentLoaded', fetchProprietaires);
</script>

</body>
</html>
