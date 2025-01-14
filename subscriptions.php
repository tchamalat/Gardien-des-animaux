<?php
include 'config.php';
session_start();

// Ensure only owners can access the subscription page
if (!isset($_SESSION['role']) || $_SESSION['role'] != 1) {
    header("Location: login.html");
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Abonnements - Gardien des Animaux</title>
    <style>
        /* General Styles */
        body {
            font-family: Arial, sans-serif;
            background: url('images/premierplan.png') no-repeat center center fixed;
            background-size: cover;
            color: #333;
            margin: 0;
            padding: 0;
            text-align: center;
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
            background: transparent; /* Supprime le fond blanc */
            box-shadow: none; /* Supprime l'ombre */
        }

        header img {
            height: 80px;
        }
        header .header-slogan {
            font-size: 1.5em;
            color: orange;
            font-weight: bold;
            text-align: center;
            flex: 1;
            transition: opacity 0.5s ease, transform 0.5s ease;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.8); /* Ombre pour une meilleure lisibilité */
        }

        header.scrolled .header-slogan {
            opacity: 0;
            transform: translateY(-20px);
        }

        .subscriptions-container {
            margin-top: 120px;
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 20px;
        }

        .subscription-card {
            background: rgba(255, 255, 255, 0.9);
            border: 2px solid orange;
            border-radius: 10px;
            padding: 20px;
            width: 300px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
            cursor: pointer;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .subscription-card:hover {
            transform: scale(1.05);
            box-shadow: 0 6px 8px rgba(0, 0, 0, 0.3);
        }

        .subscription-card h2 {
            color: orange;
        }

        .subscription-card ul {
            list-style: none;
            padding: 0;
        }

        .subscription-card ul li {
            position: relative;
            padding-left: 20px;
            margin-bottom: 10px;
        }

        .subscription-card ul li:before {
            content: '✔';
            position: absolute;
            left: 0;
            color: orange;
            font-weight: bold;
        }

        .subscribe-btn {
            background-color: orange;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            font-size: 1em;
            cursor: pointer;
            margin-top: 20px;
        }

        .subscribe-btn:hover {
            background-color: #ff7f00;
        }

        footer {
            margin-top: 30px;
            background: rgba(0, 0, 0, 0.8);
            color: white;
            padding: 10px;
        }
    </style>
    <script>
        window.addEventListener('scroll', () => {
            const header = document.querySelector('header');
            if (window.scrollY > 50) {
                header.classList.add('scrolled');
            } else {
                header.classList.remove('scrolled');
            }
        });
    </script>
</head>
<body>
    <header>
        <img src="images/logo.png" alt="Gardien des Animaux">
    </header>

    <main>
        <h1>Choisissez Votre Abonnement</h1>
        <div class="subscriptions-container">
            <!-- Standard Subscription -->
            <div class="subscription-card">
                <h2>Abonnement Standard</h2>
                <ul>
                    <li>Réduction des tarifs</li>
                    <li>Toilettage et soins supplémentaires</li>
                    <li>Accès prioritaire aux réservations</li>
                    <li>Suivi des mouvements de l'animal</li>
                    <li>Photos et vidéos gratuites</li>
                </ul>
                <button class="subscribe-btn" onclick="subscribe('standard')">Souscrire</button>
            </div>

            <!-- Premium Subscription -->
            <div class="subscription-card">
                <h2>Abonnement Premium</h2>
                <ul>
                    <li>Équipements supplémentaires</li>
                    <li>Repas premium</li>
                    <li>Séances de massage</li>
                    <li>Activités spécifiques</li>
                    <li>Cadeaux personnalisés</li>
                </ul>
                <button class="subscribe-btn" onclick="subscribe('premium')">Souscrire</button>
            </div>
        </div>
    </main>

    <footer>
        <p>&copy; 2025 Gardien des Animaux. Tous droits réservés.</p>
    </footer>

    <script>
        function subscribe(type) {
            if (confirm(`Voulez-vous vraiment souscrire à l'abonnement ${type === 'standard' ? 'Standard' : 'Premium'} ?`)) {
                // Send subscription choice to the server (via AJAX or a form submission)
                alert(`Vous avez souscrit à l'abonnement ${type === 'standard' ? 'Standard' : 'Premium'}.`);
            }
        }
    </script>
</body>
</html>
