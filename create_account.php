<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer un compte - Gardien des Animaux</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <div class="header-container">
            <img src="images/logo.png" alt="Logo Gardien des Animaux">
            <h1 class="header-slogan">Un foyer chaleureux même en votre absence</h1>
        <div class="auth-buttons">
            <button class="btn" onclick="window.location.href='index.php'">Accueil</button>
        </div>
        </div>
    </header>
    <div class="form-container">
        <h2>Création de compte :</h2>

        <form id="registerForm" method="POST" novalidate>
            <div class="form-group">
                <label for="prenom">Prénom :</label>
                <input type="text" id="prenom" name="prenom" required>
            </div>

            <div class="form-group">
                <label for="nom">Nom :</label>
                <input type="text" id="nom" name="nom" required>
            </div>

            <div class="form-group">
                <label for="username">Nom d'utilisateur :</label>
                <input type="text" id="username" name="username" required>
            </div>

            <div class="form-group">
                <label for="email">Adresse mail :</label>
                <input type="email" id="email" name="email" required>
            </div>

            <div class="form-group">
                <label for="telephone">Numéro de téléphone :</label>
                <input type="tel" id="telephone" name="telephone" pattern="[0-9]{10}" required>
            </div>

            <div class="form-group">
                <label for="adresse">Adresse :</label>
                <input type="text" id="adresse" name="adresse" required>
            </div>

            <div class="form-group">
                <label for="ville">Ville :</label>
                <input type="text" id="ville" name="ville" required>
            </div>

            <div class="form-group">
                <label for="password">Mot de passe :</label>
                <input type="password" id="password" name="password" required>
            </div>

            <div class="form-group">
                <label for="confirm_password">Confirmation du mot de passe :</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
                <small id="passwordError" style="color: red; display: none;">Les mots de passe ne correspondent pas.</small>
            </div>

            <div class="form-group">
                <label>Rôle :</label>
                <input type="radio" id="role" name="role" value="0" required>
                <label for="gardien">Gardien</label>

                <input type="radio" id="role" name="role" value="1" required>
                <label for="proprietaire">Propriétaire</label>
            </div>

            <button type="submit" class="btn">Créer un compte</button>
        </form>

        <div id="message"></div> <!-- Zone d'affichage des messages -->
    </div>

    <script>
        document.getElementById('registerForm').addEventListener('submit', function(event) {
            event.preventDefault(); // Empêche le rechargement de la page

            const formData = new FormData(this);

            // Vérification des mots de passe
            const password = formData.get('password');
            const confirmPassword = formData.get('confirm_password');
            const passwordError = document.getElementById('passwordError');

            if (password !== confirmPassword) {
                passwordError.style.display = 'inline'; // Affiche un message si les mots de passe ne correspondent pas
                return;
            } else {
                passwordError.style.display = 'none';
            }

            // Envoi de la requête AJAX
            fetch('register.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                if (data.trim() === "success") {
                    window.location.href = "confirmation.php"; // Redirection vers la page de confirmation
                } else {
                    document.getElementById('message').innerHTML = data; // Affiche uniquement le message d'erreur
                }
            })
            .catch(error => console.error('Erreur:', error));
        });
    </script>

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
