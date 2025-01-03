<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recherche de Gardien</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Gardien des Animaux</h1>
        <div class="auth-buttons">
            <!-- Boutons de redirection -->
            <button class="btn" onclick="window.location.href='create_account.php'">Créer un compte</button>
            <button class="btn" onclick="window.location.href='login.html'">Je me connecte</button>
            <button class="btn" onclick="window.location.href='index.php'">Accueil</button>
        </div>
    </header>

    <!-- Conteneur principal de la recherche -->
    <div class="search-container">
        <h2>Trouvez un gardien pour vos animaux</h2>
        
    <form action="resultats_recherche_index.php" method="GET">
        <input type="hidden" name="latitude" value="<?php echo htmlspecialchars($_GET['latitude'] ?? ''); ?>">
        <input type="hidden" name="longitude" value="<?php echo htmlspecialchars($_GET['longitude'] ?? ''); ?>">
    
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


