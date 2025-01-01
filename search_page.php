<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recherche de Gardien</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
            background: url('images/premierplan.png') no-repeat center center fixed;
            background-size: cover;
            margin: 0;
            padding: 0;
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

        header h1 {
            color: orange;
            font-size: 1.8em;
            margin: 0;
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
            margin-left: 10px;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        header .auth-buttons .btn:hover {
            background-color: #ff7f00;
            transform: translateY(-3px);
        }

        .search-container {
            max-width: 800px;
            margin: 150px auto 50px auto;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
            text-align: center;
        }

        .search-container h2 {
            color: orange;
            margin-bottom: 20px;
        }

        .form-group {
            margin: 15px 0;
            text-align: left;
        }

        .form-group label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .form-group select,
        .form-group input {
            width: calc(100% - 20px);
            padding: 10px;
            font-size: 1em;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-sizing: border-box;
        }

        .search-btn {
            background-color: orange;
            color: white;
            padding: 12px 25px;
            border: none;
            border-radius: 10px;
            font-size: 1.1em;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.3s ease;
            margin-top: 20px;
        }

        .search-btn:hover {
            background-color: #ff7f00;
            transform: translateY(-3px);
        }

        footer {
            padding: 20px;
            background: rgba(0, 0, 0, 0.8);
            color: #fff;
        }

        .footer-links {
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
        }

        .footer-links div {
            margin: 10px;
            text-align: left;
        }

        .footer-links h4 {
            font-size: 1.2em;
            margin-bottom: 10px;
            color: orange;
        }

        .footer-links ul {
            list-style: none;
            padding: 0;
        }

        .footer-links li {
            margin-bottom: 5px;
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
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    function (position) {
                        document.getElementById("latitude").value = position.coords.latitude;
                        document.getElementById("longitude").value = position.coords.longitude;
                    },
                    function (error) {
                        console.error("Erreur de géolocalisation : ", error);
                        alert("Impossible de récupérer votre localisation. Veuillez vérifier vos paramètres.");
                    }
                );
            } else {
                alert("La géolocalisation n'est pas supportée par votre navigateur.");
            }
        });
    </script>
</head>
<body>

<header>
    <h1>Gardien des Animaux</h1>
    <div class="auth-buttons">
        <button class="btn" onclick="window.location.href='index_connect.php'">Accueil</button>
    </div>
</header>

<div class="search-container">
    <h2>Trouvez un gardien pour vos animaux</h2>
    <form action="resultats_recherche.php" method="GET">
        <div class="form-group">
            <label for="service">Type de service</label>
            <select name="service" id="service">
                <option value="garde">Garde</option>
                <option value="promenade">Promenade</option>
            </select>
        </div>

        <div class="form-group">
            <label for="animal">Type d'animal</label>
            <select name="animal" id="animal">
                <option value="chien">Chien</option>
                <option value="chat">Chat</option>
                <option value="oiseau">Oiseau</option>
            </select>
        </div>

        <div class="form-group">
            <label for="nombre">Nombre d'animaux</label>
            <select name="nombre" id="nombre">
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3+</option>
            </select>
        </div>

        <div class="form-group">
            <label for="rayon">Rayon autour de vous (km)</label>
            <input type="number" name="rayon" id="rayon" value="20" min="1">
        </div>

        <input type="hidden" id="latitude" name="latitude">
        <input type="hidden" id="longitude" name="longitude">

        <div class="form-group">
            <label for="budget_min">Budget minimum (€)</label>
            <input type="number" name="budget_min" placeholder="Minimum" value="20">
            <input type="number" name="budget_max" placeholder="Maximum" value="40">
        </div>

        <button type="submit" class="search-btn">Recherche</button>
    </form>
</div>

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
