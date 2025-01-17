<!DOCTYPE html>
<html>
    <head>
        <title> My Chat </title>
    </head>
    <style type="text/css">

@font-face {
                font-family: headFont;
                src: url(ui/fonts/Summer-Vibes-OTF.otf);
            }

            @font-face {
                font-family: myFont;
                src: url(ui/fonts/OpenSans-Regular.ttf);
            }

            /* Styles généraux */
            body {
                margin: 0;
                padding: 0;
                font-family: myFont;
                background-color: #fdf5e6; /* Beige clair pour le fond */
                color: #4a4a4a; /* Gris foncé pour le texte */
            }

            a {
                color: #ff8c42; /* Orange atténué */
                text-decoration: none;
                transition: color 0.3s;
            }

            a:hover {
                color: #d45a1f; /* Orange plus foncé */
            }

            #wrapper {
                max-width: 900px;
                min-height: 500px;
                margin: 40px auto;
                background-color: #fffaf0; /* Beige clair */
                border: 1px solid #f0e5d8; /* Bordure subtile */
                border-radius: 10px;
                box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
                overflow: hidden;
            }

            #header {
                background-color: #ff8c42; /* Orange principal */
                font-size: 40px;
                text-align: center;
                font-family: headFont;
                padding: 20px 0;
                color: white;
                border-bottom: 5px solid #d45a1f; /* Bordure orange plus foncée */
                display: flex;
                align-items: center; /* Centrer verticalement */
                justify-content: center; /* Centrer horizontalement */
                gap: 15px; /* Espacement entre les éléments */
            }

            #header img {
                width: 50px; /* Taille de l'image */
                height: 50px;
                border-radius: 50%; /* Bordure arrondie (facultatif) */
                object-fit: cover; /* Pour s'assurer que l'image s'adapte bien */
            }

            #header div {
                font-size: 20px;
                font-family: myFont;
            }

            #error {
                text-align: center;
                padding: 10px;
                margin: 20px;
                background-color: #fce4d6; /* Rouge atténué */
                color: #d45a1f; /* Rouge foncé */
                border-radius: 5px;
                display: none;
            }

            form {
                margin: auto;
                padding: 20px;
                width: 100%;
                max-width: 400px;
                font-size: 16px;
            }

            input[type=text],
            input[type=password],
            input[type=button] {
                padding: 10px;
                margin: 10px 0;
                width: 100%;
                border-radius: 5px;
                border: 1px solid #dcdcdc;
                font-size: 14px;
                box-sizing: border-box;
            }

            input[type=text]:focus,
            input[type=password]:focus {
                outline: none;
                border-color: #ff8c42;
                box-shadow: 0 0 5px rgba(255, 140, 66, 0.4);
            }

            input[type=button] {
                background-color: #ff8c42;
                color: white;
                border: none;
                cursor: pointer;
                transition: background-color 0.3s;
            }

            input[type=button]:hover {
                background-color: #d45a1f; /* Orange foncé */
            }

            small {
                display: block;
                color: #4a4a4a;
                margin: 5px 0;
                font-size: 12px;
            }

            /* Style des boutons radio */
            input[type=radio] {
                transform: scale(1.2);
                margin-right: 10px;
                cursor: pointer;
            }

            /* Jauge de force du mot de passe */
            .strength-meter {
                height: 10px;
                width: 100%;
                background-color: #e6e6e6;
                border-radius: 5px;
                margin-top: 10px;
            }

            .strength-meter div {
                height: 100%;
                border-radius: 5px;
                transition: width 0.3s, background-color 0.3s;
            }

            .strength-weak {
                width: 33%;
                background-color: #f76c6c; /* Rouge clair */
            }

            .strength-medium {
                width: 66%;
                background-color: #ffb347; /* Orange clair */
            }

            .strength-strong {
                width: 100%;
                background-color: #4caf50; /* Vert */
            }

            /* Lien centré */
            a {
                display: block;
                text-align: center;
                margin: 20px 0;
            }

        </style>
    </head>
    <body>
        <div id="wrapper">
            <div id="header">
            <img src="ui/images/gardiens des animaix.png" alt="Chat Icon">
                My Chat
                <div>signup</div>
            </div>
            <div id="error">MEME TEXT</div>
            <form id="myform">
                <input type="text" name="username" placeholder="Username"><br>
                <input type="text" name="email" placeholder="Email"><br>
                <div>
                    <br>Genre:<br>
                    <input type="radio" value="Male" name="gender_male"> Homme<br>
                    <input type="radio" value="Female" name="gender_female"> Femme<br>
                </div>
                <input type="password" id="password" name="password" placeholder="Password"><br>
                <!-- Jauge de force -->
                <div class="strength-meter" id="strength-meter">
                    <div></div>
                </div>
                <span id="strength-text"></span>
                <small>Le mot de passe doit contenir au moins 8 caractères, une lettre majuscule, un chiffre, et un caractère spécial.</small><br>
                <input type="password" name="password2" placeholder="Retype password"><br>
                <input type="button" value="Sign up" id="signup_button"><br>
                <a href="login.php">Déjà un compte? Connectez-vous ici.</a>
            </form>
        </div>
    </body>
</html>

<script type="text/javascript">

    var myobject = {};  

  

    function _(element) {
        return document.getElementById(element);
    }

    var signup_button = _("signup_button");
    signup_button.addEventListener("click", collect_data);

    function collect_data() {

        signup_button.disabled = true;
        signup_button.value = "loading...Please Wait..";
        var myform = _("myform");
        var inputs = myform.getElementsByTagName("INPUT");

        var data = {};
        for (var i = inputs.length - 1; i >= 0; i--) {  // Correction de la syntaxe

            var key = inputs[i].name;

            switch(key) {
                case "username":
                    data.username = inputs[i].value;
                    break;

                case "email":
                    data.email = inputs[i].value;
                    break;

                case "gender_male":
                case "gender_female":
                    if (inputs[i].checked) {
                        data.gender = inputs[i].value;
                    }
                    break;

                case "password":
                    data.password = inputs[i].value;
                    break;

                case "password2":
                    data.password2 = inputs[i].value;
                    break;
            }
        }
        
        send_data(data, "signup");
        
    }

    function send_data(data, type) {
        var xml = new XMLHttpRequest();
        
        xml.onload = function() {
            if (xml.readyState === 4 || xml.status === 200) {  // Correction de la condition
                handle_result(xml.responseText);
                signup_button.disabled = false;
                signup_button.value = "Signup";

            }
        }

        data.data_type = type;  // Assigner data_type avant conversion
        var data_string = JSON.stringify(data);

        xml.open("POST", "api.php", true);
        xml.send(data_string);  // Envoyer data_string au lieu de data
    }

    function handle_result(result){

        var data = JSON.parse(result);
        if(data.data_type == "info")
        {
            window.location  = "login.php";

        }else
        {
            var error = _("error");
            error.innerHTML = data.message;
            error.style.display = "block";
            

        }
    }
    // Script pour la jauge de force du mot de passe
    const passwordInput = document.getElementById('password');
const strengthMeter = document.getElementById('strength-meter').firstElementChild;
const strengthText = document.getElementById('strength-text');

if (passwordInput) {
    passwordInput.addEventListener('input', () => {
        const value = passwordInput.value;
        let strength = 0;

        // Évaluer la force du mot de passe
        if (value.length >= 8) strength++;
        if (/[A-Z]/.test(value)) strength++;
        if (/[!@#$%^&*(),.?":{}|<>]/.test(value)) strength++;
        if (/\d/.test(value)) strength++;

        // Réinitialiser la classe et la largeur
        strengthMeter.className = '';
        strengthMeter.style.width = '0'; // Réinitialiser la largeur par défaut

        // Appliquer les mises à jour en fonction de la force
        if (strength === 0) {
            strengthText.textContent = '';
        } else if (strength === 1) {
            strengthMeter.style.width = '33%';
            strengthMeter.classList.add('strength-weak');
            strengthText.textContent = 'Faible';
        } else if (strength === 2 || strength === 3) {
            strengthMeter.style.width = '66%';
            strengthMeter.classList.add('strength-medium');
            strengthText.textContent = 'Moyen';
        } else if (strength === 4) {
            strengthMeter.style.width = '100%';
            strengthMeter.classList.add('strength-strong');
            strengthText.textContent = 'Fort';
        }
    });
}



</script>


