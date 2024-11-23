<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recherche de Gardien</title>
    <link rel="stylesheet" href="styles.css">
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // Vérifier si la géolocalisation est disponible
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    function (position) {
                        // Remplir automatiquement les champs cachés avec latitude et longitude
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
            <!-- Boutons de redirection -->
            <button class="btn" onclick="window.location.href='index_connect.php'">Accueil</button>
        </div>
    </header>

    <!-- Conteneur principal de la recherche -->
    <div class="search-container">
        <h2>Trouvez un gardien pour vos animaux</h2>
        <form action="resultats_recherche.php" method="GET">
            <!-- Type de service -->
            <div class="form-group">
                <label for="service">Type de service</label>
                <select name="service" id="service">
                    <option value="garde">Garde</option>
                    <option value="promenade">Promenade</option>
                </select>
            </div>

            <!-- Type d'animal -->
            <div class="form-group">
                <label for="animal">Type d'animal</label>
                <select name="animal" id="animal">
                    <option value="chien">Chien</option>
                    <option value="chat">Chat</option>
                    <option value="oiseau">Oiseau</option>
                </select>
            </div>

            <!-- Nombre d'animaux -->
            <div class="form-group">
                <label for="nombre">Nombre d'animaux</label>
                <select name="nombre" id="nombre">
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3+</option>
                </select>
            </div>

            <!-- Rayon de recherche autour -->
            <div class="form-group">
                <label for="rayon">Rayon autour de vous (km)</label>
                <input type="number" name="rayon" id="rayon" value="20" min="1">
            </div>

            <!-- Champs cachés pour latitude et longitude -->
            <input type="hidden" id="latitude" name="latitude">
            <input type="hidden" id="longitude" name="longitude">

            <!-- Budget -->
            <div class="form-group">
                <label for="budget_min">Budget minimum (€)</label>
                <input type="number" name="budget_min" placeholder="Minimum" value="20">
                <input type="number" name="budget_max" placeholder="Maximum" value="40">
            </div>

            <!-- Bouton de recherche -->
            <button type="submit" class="search-btn">Recherche</button>
        </form>
    </div>
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
                    <li>Télécharger l'app</li>
                </ul>
            </div>
        </div>
    </footer>

</body>
</html>
