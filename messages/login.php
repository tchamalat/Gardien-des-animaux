<!DOCTYPE html>
<html>
    <head>
        <title> My Chat </title>
    </head>
    <style type="text/css">

@font-face{
                font-family: headFont;
                src: url(ui/fonts/Summer-Vibes-OTF.otf);
            }

            @font-face{
                font-family: myFont;
                src: url(ui/fonts/OpenSans-Regular.ttf);
            }

            #wrapper{
                max-width: 900px;
                min-height: 500px;
                margin: auto;
                color: grey;
                font-family: myFont;
                font-size: 13px;
                background-color: #f4f1e1; /* Fond beige clair pour l'ensemble */
            }

            #left_pannel{
                min-height: 500px;
                background-color: #d57e3d; /* Orange pour la barre de gauche */
                flex: 1;
                text-align: center;
                padding: 20px;
            }

            #profile_image{
                width: 50%;
                border: solid thin white;
                border-radius: 50%;
                margin: 10px;
            }

            #left_pannel label{
                width: 100%;
                height: 20px;
                display: block;
                background-color: #f8c877; /* Couleur beige orangé pour les labels */
                border-bottom: solid thin #ffffff55;
                cursor: pointer;
                padding: 5px;
                transition: all 1s ease;
                color: #6c4f3d;
                font-size: 15px;
            }

            #left_pannel label:hover{
                background-color: #e08a2b; /* Orange foncé pour l'effet hover */
            }

            #left_pannel label img{
                float: right;
                width: 25px;
            }

            #right_pannel{
                min-height: 500px;
                flex: 4;
            }

            #inner_left_pannel{
                background-color: #b85c36; /* Fond orange moyen pour la partie intérieure gauche */
                flex: 1;
                min-height: 430px;
                text-align: center;
                color: white;
            }

            #inner_right_pannel{
                background-color: #fdf4d4; /* Fond beige clair pour la partie droite */
                flex: 2;
                min-height: 430px;
                transition: all 2s ease;
            }

            #radio_contacts:checked ~ #inner_right_pannel{
                flex: 0;
            }

            #radio_settings:checked ~ #inner_right_pannel{
                flex: 0;
            }

            form{
                margin: auto;
                padding: 10px;
                width: 100%;
                max-width: 400px;
            }

            input[type=text], input[type=password], input[type=submit]{
                padding: 10px;
                margin: 10px;
                width: 98%;
                border-radius: 5px;
                border: solid 1px grey;
            }

            input[type=submit]{
                width: 103%;
                cursor: pointer;
                background-color: #f08d3d; /* Orange clair pour le bouton */
                color: white;
            }

            input[type=radio]{
                transform: scale(1.2);
                cursor: pointer;
            }

            #header {
                background-color: #e08a2b; /* Orange pour l'en-tête */
                font-size: 40px;
                text-align: center;
                font-family: headFont;
                width: 100%;
                color: white;
                display: flex;
                justify-content: center;
                align-items: center;
                gap: 15px; /* Espacement entre l'image et le texte */
                padding: 20px 0;
            }

            #header img {
                width: 50px; /* Taille de l'image */
                height: 50px;
                border-radius: 50%; /* Bord arrondi pour l'image */
                object-fit: cover; /* Pour que l'image garde son aspect */
            }

            #error {
                text-align: center; 
                padding: 0.5em; 
                background-color: #f08d3d; /* Orange pour le fond de l'erreur */
                color: white; 
                display: none;
            }
        </style>
    </head>
    <body>
        <div id="wrapper">
            <div id="header">
                <!-- Image ajoutée à côté du texte -->
                <img src="ui/images/gardiens des animaix.png" alt="Chat Icon">
                My Chat
                <div style="font-size: 20px;font-family: myFont;">login</div>
            </div>
            <div id="error" style=""> MEME TEXT </div>
            <form id="myform">
                <input type="text" name="email" placeholder="Email"><br>
                <input type="password" name="password" placeholder="Password"><br>
                <input type="submit" value="Login" id="login_button"><br>

                <br>
                <a href="signup.php" style="display: block;text-align: center;text-decoration:none">
                    N'avez vous pas de compte? Inscrivez vous ici.
                </a>
                <a href="https://gardien-des-animaux.fr/forgot_password.php" style="display: block;text-align: center;text-decoration:none">
                    Mot de passe oublié? reintialisez le ici.
                </a>
            </form>
        </div>
    </body>
</html>

<script type="text/javascript">
    var myobject = {};  

    function _(element) {
        return document.getElementById(element);
    }

    var login_button = _("login_button");
    login_button.addEventListener("click", collect_data);

    function collect_data(e) {
        e.preventDefault();
        login_button.disabled = true;
        login_button.value = "loading...Please Wait..";
        var myform = _("myform");
        var inputs = myform.getElementsByTagName("INPUT");

        var data = {};
        for (var i = inputs.length - 1; i >= 0; i--) {  
            var key = inputs[i].name;
            switch(key) {
                case "email":
                    data.email = inputs[i].value;
                    break;
                case "password":
                    data.password = inputs[i].value;
                    break;
            }
        }
        
        send_data(data, "login");
    }

    function send_data(data, type) {
        var xml = new XMLHttpRequest();
        
        xml.onload = function() {
            if (xml.readyState === 4 || xml.status === 200) {  
                handle_result(xml.responseText);
                login_button.disabled = false;
                login_button.value = "Login";
            }
        }

        data.data_type = type;  
        var data_string = JSON.stringify(data);

        xml.open("POST", "api.php", true);
        xml.send(data_string);  
    }

    function handle_result(result){
        var data = JSON.parse(result);
        if(data.data_type == "info") {
            window.location  = "index.php";
        } else {
            var error = _("error");
            error.innerHTML = data.message;
            error.style.display = "block";
        }
    }
</script>

