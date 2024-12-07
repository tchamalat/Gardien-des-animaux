<?php
session_start();
require_once 'config.php'; // Fichier de connexion à la base de données

// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Récupère l'ID de l'utilisateur connecté
$user_id = $_SESSION['user_id'];

// Gestion de l'envoi du message
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $recipient_username = $_POST['recipient_username'];
    $message_content = $_POST['message'];

    // Vérifie si le destinataire existe
    $stmt = $conn->prepare("SELECT id FROM creation_compte WHERE nom_utilisateur = ?");
    $stmt->bind_param('s', $recipient_username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $receiver_id = $row['id'];

        // Insère le message dans la table discussion
        $insert = $conn->prepare("INSERT INTO discussion (sender_id, receiver_id, message) VALUES (?, ?, ?)");
        $insert->bind_param('iis', $user_id, $receiver_id, $message_content);
        if ($insert->execute()) {
            echo "<p style='color: green;'>Message envoyé avec succès.</p>";
        } else {
            echo "<p style='color: red;'>Erreur lors de l'envoi du message.</p>";
        }
    } else {
        echo "<p style='color: red;'>Destinataire introuvable.</p>";
    }
}
?>

<!DOCTYPE html>
<html>
<?php
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
<body>
    <h2>Envoyer un message</h2>
    <form method="POST">
        <label for="recipient_username">Nom d'utilisateur du destinataire :</label><br>
        <input type="text" name="recipient_username" required><br><br>

        <label for="message">Message :</label><br>
        <textarea name="message" rows="4" cols="50" required></textarea><br><br>

        <input type="submit" value="Envoyer">
    </form>

    <h2>Messages échangés</h2>
    <?php
    // Récupère les messages où l'utilisateur est soit l'expéditeur, soit le destinataire
    $messages = $conn->prepare("
        SELECT d.*, c1.nom_utilisateur AS sender_name, c2.nom_utilisateur AS receiver_name 
        FROM discussion d
        JOIN creation_compte c1 ON d.sender_id = c1.id
        JOIN creation_compte c2 ON d.receiver_id = c2.id
        WHERE d.sender_id = ? OR d.receiver_id = ?
        ORDER BY d.timestamp DESC
    ");
    $messages->bind_param('ii', $user_id, $user_id);
    $messages->execute();
    $result = $messages->get_result();

    if ($result->num_rows > 0) {
        while ($msg = $result->fetch_assoc()) {
            echo "<p><strong>{$msg['sender_name']}</strong> à <strong>{$msg['receiver_name']}</strong> : {$msg['message']} <em>({$msg['timestamp']})</em></p>";
        }
    } else {
        echo "<p>Aucun message trouvé.</p>";
    }
    ?>
<?php
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
