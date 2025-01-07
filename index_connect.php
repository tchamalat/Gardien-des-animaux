<?php  
include 'config.php'; 
session_start();

// Gestion des requêtes AJAX pour les gardiens
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);

    if (isset($input['latitude'], $input['longitude'], $_SESSION['role']) && $_SESSION['role'] == 1) { 
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

        $gardiens = [];
        while ($gardien = $gardiens_result->fetch_assoc()) {
            $gardiens[] = $gardien;
        }
        echo json_encode($gardiens);
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
        /* Styles similaires à index.php */
        <?php include 'styles.css'; ?>
    </style>
</head>
<body>
    <!-- Header -->
    <header>
        <div class="header-container">
            <img src="images/logo.png" alt="Logo Gardien des Animaux">
            <div class="auth-buttons">
                <button class="btn" onclick="window.location.href='search_page.php'">Trouver un gardien</button>
                <button class="btn" onclick="window.location.href='discussion_gardien.php'">Discussion</button>
                <?php
                if (isset($_SESSION['role'])) {
                    if ($_SESSION['role'] == 0) {
                        echo '<button class="btn" onclick="window.location.href=\'profil_gardien.php\'">Mon Profil</button>';
                    } elseif ($_SESSION['role'] == 1) {
                        echo '<button class="btn" onclick="window.location.href=\'profil.php\'">Mon Profil</button>';
                    }
                }
                ?>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero">
        <h1 class="texte">Bienvenue sur Gardien des Animaux</h1>
    </section>

    <!-- Section Gardien -->
    <?php if ($_SESSION['role'] == 1): ?>
    <section class="gardiens">
        <h2 class="texte">Découvrez nos gardiens disponibles :</h2>
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

                    const response = await fetch('index_connect.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ latitude, longitude })
                    });

                    const gardiens = await response.json();

                    if (gardiens.length > 0) {
                        gardiensContainer.innerHTML = gardiens.map(gardien => `
                            <div class="gardien-card">
                                <img src="images/${gardien.profile_picture || 'default.jpg'}" alt="${gardien.prenom}">
                                <h3>${gardien.prenom}</h3>
                                <p>${gardien.nom_utilisateur}</p>
                                <p>Distance : ${gardien.distance.toFixed(2)} km</p>
                            </div>
                        `).join('');
                    } else {
                        gardiensContainer.innerHTML = `<p class="texte">Aucun gardien trouvé près de chez vous.</p>`;
                    }
                });
            } else {
                gardiensContainer.innerHTML = `<p class="texte">La géolocalisation n'est pas prise en charge par votre navigateur.</p>`;
            }
        }

        document.addEventListener('DOMContentLoaded', fetchGardiens);
    </script>
    <?php endif; ?>

    <!-- Avis Section -->
    <section class="avis-section">
        <h2 class="texte">Ce que disent nos utilisateurs</h2>
        <p class="texte">Vos retours sont précieux et aident à améliorer nos services.</p>
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
