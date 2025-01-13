<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer un compte - Gardien des Animaux</title>
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
            background: url('images/premierplan.png') no-repeat center center fixed;
            background-size: cover;
            min-height: 100vh;
            overflow-x: hidden;
        }
        header {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 150px; /* Ajuster la hauteur du header si nécessaire */
            z-index: 10;
            display: flex;
            align-items: center;
            justify-content: center; /* Centrer horizontalement */
            padding: 20px;
            background: transparent; 
            box-shadow: none; 
        }
        header .header-slogan {
            font-size: 1.5em;
            color: orange;
            font-weight: bold;
            text-align: center;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.8); /* Ombre pour une meilleure lisibilité */
            position: absolute; /* Permet de positionner indépendamment */
            top: 50%; /* Centrer verticalement */
            left: 50%; /* Centrer horizontalement */
            transform: translate(-50%, -50%); /* Corrige le décalage dû au positionnement */
        }
        header img {
            height: 150px;
            max-width: 160px;
        }

        header.scrolled .header-slogan {
            opacity: 0;
            transform: translateY(-20px);
        }

        .auth-buttons .btn {
            background-color: orange;
            color: white;
            padding: 8px 15px;
            border: none;
            border-radius: 8px;
            font-size: 0.9em;
            cursor: pointer;
            text-decoration: none;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }
        .auth-buttons {
            position: absolute;
            top: 20px;
            right: 20px;
        }
        .auth-buttons .btn:hover {
            background-color: #ff7f00;
            transform: translateY(-3px);
        }

        .form-container {
            margin: 250px auto 50px; /* Ajustement pour centrer */
            width: 90%;
            max-width: 400px;
            background: rgba(255, 255, 255, 0.9);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            color: #333;
        }

        .form-container h2 {
            text-align: center;
            margin-bottom: 20px;
            color: orange;
            font-size: 1.5em;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            font-size: 0.9em;
        }

        .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 1em;
        }

        .form-group input[type="radio"] {
            display: inline-block;
            width: auto;
            margin-right: 10px;
        }

        .form-group small {
            color: red;
            display: none;
        }

        .btn {
            display: block;
            width: 100%;
            background-color: orange;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 8px;
            font-size: 1.2em;
            cursor: pointer;
            text-align: center;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .btn:hover {
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
        .role-selection {
            display: flex;
            justify-content: space-between;
            gap: 10px;
            margin-top: 10px;
        }

        .role-option {
            background-color: #fff;
            color: #333;
            padding: 10px 20px;
            border: 2px solid orange;
            border-radius: 8px;
            font-size: 1em;
            text-align: center;
            cursor: pointer;
            flex: 1;
            transition: all 0.3s ease;
        }

        .role-option:hover {
            background-color: orange;
            color: #fff;
            transform: translateY(-3px);
        }

        .role-option.active {
            background-color: orange;
            color: white;
            border-color: #ff7f00;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
        }

        input[type="radio"] {
            display: none; /* Cachez les boutons radio */
        }

        input[type="radio"]:checked + .role-option {
            background-color: orange;
            color: white;
            border-color: #ff7f00;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);
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
                <small id="emailError" style="color: red; display: none;"></small>
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
                <div class="role-selection">
                    <input type="radio" id="gardien" name="role" value="0" required>
                    <label for="gardien" class="role-option">Gardien</label>

                    <input type="radio" id="proprietaire" name="role" value="1" required>
                    <label for="proprietaire" class="role-option">Propriétaire</label>
                </div>
            </div>
            <button type="submit" class="btn">Créer un compte</button>
        </form>

        <div id="message"></div> 
    </div>
    <script>
        // Fonction de validation pour le numéro de téléphone
        function validatePhoneNumber(phone) {
            const phoneRegex = /^[0-9]{10}$/;
            return phoneRegex.test(phone);
        }
        // Fonction de validation pour l'email
        function validateEmail(email) {
            const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.(com|fr)$/;
            return emailRegex.test(email);
        }

        // Écouteur d'événements pour le formulaire d'inscription
        document.getElementById('registerForm').addEventListener('submit', function(event) {
            event.preventDefault(); // Empêche le rechargement de la page

            const formData = new FormData(this);
            const email = formData.get('email');
            const emailError = document.getElementById('emailError');

            const phone = formData.get('telephone');
            if (!validatePhoneNumber(phone)) {
                alert("Le numéro de téléphone doit contenir exactement 10 chiffres.");
                return;
            }

            // Vérification de l'email
            if (!validateEmail(email)) {
                emailError.style.display = 'inline';
                emailError.textContent = "L'adresse e-mail doit être au format xxx.xxx@xxx.fr ou xxx.xxx@xxx.com ou xxxxxx@xxx.fr ou xxxxxx@xxx.com.";
                return;
            } else {
                emailError.style.display = 'none';
            }

            // Vérification des mots de passe
            const password = formData.get('password');
            const confirmPassword = formData.get('confirm_password');
            const passwordError = document.getElementById('passwordError');

            if (password !== confirmPassword) {
                passwordError.style.display = 'inline';
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
                    window.location.href = "confirmation.php";
                } else {
                    document.getElementById('message').innerHTML = data;
                }
            })
            .catch(error => console.error('Erreur:', error));
        });
    </script>
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
    <script>
        document.querySelectorAll('.role-selection input[type="radio"]').forEach((radio) => {
            radio.addEventListener('change', function () {
                document.querySelectorAll('.role-option').forEach((label) => {
                    label.classList.remove('active'); // Retirer la classe active de toutes les options
                });
                this.nextElementSibling.classList.add('active'); // Ajouter la classe active à l'option sélectionnée
            });
        });
    </script>
    <script>
        // Fonction de validation pour un mot de passe sécurisé
        function validatePassword(password) {
            const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&.])[A-Za-z\d@$!%*?&.]{8,}$/;
            return passwordRegex.test(password);
        }

        document.getElementById('registerForm').addEventListener('submit', function(event) {
            event.preventDefault(); // Empêche le rechargement de la page
            const formData = new FormData(this);

            // Vérification du mot de passe
            const password = formData.get('password');
            const confirmPassword = formData.get('confirm_password');
            const passwordError = document.getElementById('passwordError');

            if (password !== confirmPassword) {
                passwordError.style.display = 'inline';
                passwordError.textContent = "Les mots de passe ne correspondent pas.";
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
    <script>
        document.getElementById('registerForm').addEventListener('submit', function(event) {
            const submitButton = document.querySelector('.btn[type="submit"]');
            submitButton.disabled = true;
            submitButton.textContent = "Veuillez patienter..."; // Change le texte du bouton
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
