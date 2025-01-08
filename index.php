
<!DOCTYPE html>
<html>
    <head>
        <title> My Chat </title>
    </head>
    

    <style type="text/css">

@font-face{
            font-family: headFont;
            src: url(ui/fonts/Fredoka-VariableFont_wdth,wght.ttf);
        }

        @font-face{
            font-family: myFont;
            src: url(ui/fonts/OpenSans-Regular.ttf);
        }

        body {
            background: linear-gradient(135deg, #f7f3eb, #ffe4c1);
            margin: 0;
            padding: 0;
            font-family: myFont;
        }

        #wrapper {
            max-width: 900px;
            min-height: 500px;
            max-height: 630px;
            display: flex;
            margin: 20px auto;
            color: #333;
            font-size: 13px;
            border: 1px solid #e0d6cc;
            border-radius: 15px;
            box-shadow: 0px 6px 12px rgba(0, 0, 0, 0.1);
        }
        #left_pannel {
    min-height: 500px;
    background: linear-gradient(180deg, #ffe4c1, #f7f3eb); /* Dégradé beige/orange clair */
    flex: 1;
    text-align: center;
    border-right: 2px solid #e0d6cc;
    border-top-left-radius: 15px;
    border-bottom-left-radius: 15px;
}
        #profile_image {
            width: 50%;
            border: solid thin #d9c2a6;
            border-radius: 50%;
            margin: 10px;
        }

        #left_pannel label {
            width: 100%;
            height: 20px;
            display: block;
            background: linear-gradient(90deg, #ffcc99, #e9a75d);
            border-bottom: solid thin #e6b67a;
            cursor: pointer;
            padding: 5px;
            transition: all 0.5s ease;
            color: #4a4a4a;
            font-weight: bold;
            text-shadow: 1px 1px 2px #fff;
        }
        #left_pannel label:hover {
            background: linear-gradient(90deg, #e9a75d, #ffcc99);
            box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.2);
        }

        #left_pannel label img {
            float: right;
            width: 25px;
        }

        #right_pannel {
            min-height: 500px;
            flex: 4;
            background-color: #fff;
            border-top-right-radius: 15px;
            border-bottom-right-radius: 15px;
            box-shadow: inset 0px 4px 8px rgba(0, 0, 0, 0.05);
        }

        #header {
            background: linear-gradient(90deg, #ffcc99, #e9a75d);
            height: 70px;
            font-size: 40px;
            text-align: center;
            font-family: headFont;
            line-height: 70px;
            color: #804000;
            border-bottom: 2px solid #e0d6cc;
            text-shadow: 2px 2px 4px #fff;
        }

        #inner_left_pannel{
            background-color: #ffe9d6;
            flex: 1;
            min-height: 430px;
            text-align: center;
            max-height: 530px;
            border-radius: 10px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        }

        #inner_right_pannel {
    background-color: #f2f7f8;
    background-image: url('ui/images/gardiens des animaix.png');  /* Remplacez par l'URL de votre image */
    background-size: cover; /* Cela permet à l'image de couvrir toute la zone */
    background-position: center; /* Centre l'image */
    background-repeat: no-repeat; /* Empêche la répétition de l'image */
    
    flex: 2;
    min-height: 430px;
    max-height: 530px;
    transition: all 2s ease;
    border-radius: 10px;
    box-shadow: inset 0px 4px 8px rgba(0, 0, 0, 0.05);

    /* Ajout d'un filtre pour rendre l'image plus discrète */
    background-blend-mode: overlay;
    background-color: rgba(242, 247, 248, 0.7);  /* Ajoute un léger fond transparent pour garder une bonne lisibilité */
}


        #radio_contacts:checked ~ #inner_right_pannel{
            flex: 0;
        }

        #radio_settings:checked ~ #inner_right_pannel{
            flex: 0;
        }

        #contact {
            width: 100px;
            height: 120px;
            margin: 10px;
            display: inline-block;
            vertical-align: top;
            overflow: hidden;
            border-radius: 10px;
            box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.1);
        }

        #contact img{
            width: 100px;
            height: 100px;
        }

        #active_contact {
            height: 70px;
            margin: 10px;
            border: solid thin #aaa;
            padding: 2px;
            background-color: #eee;
            color: #444;
            border-radius: 10px;
        }

        #active_contact img{
            width: 70px;
            height: 70px;
            float: left;
            margin: 2px;
            border-radius: 50%;
        }

        #message_container {
            display: flex;
            flex-direction: column;
            width: 100%;
        }

        #message_left {
            margin: 10px;
            padding: 10px;
            background-color: #f8f1e7;
            color: #5a5a5a;
            box-shadow: 0px 2px 8px rgba(0, 0, 0, 0.1);
            width: 60%;
            position: relative;
            border-radius: 15px;
            align-self: flex-start;
            border-top-right-radius: 30%;
            word-wrap: break-word;
        }

        #message_left #prof_img  {
            width: 60px;
            height: 60px;
            float: left;
            margin: 2px;
            border-radius: 50%;
            border: solid 2px white;
        }

        #message_left div {
            width: 20px;
            height: 20px;
            background-color: #34474f;
            border: solid 2px white;
            border-radius: 50%;
            position: absolute;
            left: -10px;
            top: 20px;
        }

        #message_right {
            margin: 10px;
            padding: 10px;
            background-color: #ffe4c1;
            color: #4a4a4a;
            box-shadow: 0px 2px 8px rgba(0, 0, 0, 0.1);
            width: 60%;
            position: relative;
            border-radius: 15px;
            align-self: flex-end;
            border-top-left-radius: 30%;
            word-wrap: break-word;
        }

        #message_right #prof_img {
            width: 60px;
            height: 60px;
            float: left;
            margin: 2px;
            border-radius: 50%;
            border: solid 2px white;
        }

        #message_right div img {
            width: 20px;
            height: 20px;
            float: none;
            margin: 0px;
            border-radius: 50%;
            border: none;
        }

        #message_right #trash {
            width: 10px;
            height: 10px;
            position : absolute;
            top : 10px;
            left: -10px;
            cursor: pointer;
        }

        #message_left #trash {
            width: 10px;
            height: 10px;
            position : absolute;
            top : 10px;
            right: -10px;
            cursor: pointer;
        }

        #message_right div {
            width: 20px;
            height: 20px;
            background-color: #34474f;
            border: solid 2px white;
            border-radius: 50%;
            position: absolute;
            right: -10px;
            top: 20px;
        }

        .loader_on {
            position : absolute;
            width: 30%;
        }

        .loader_off {
            display: none;
        }

        .image_on {
            position : absolute;
            height: 450px;
            width: 450px;
            margin: auto;
            z-index: 10;
            top: 50px;
            left: 50px;
        }

        .image_off {
            display: none;
        }

        .indicator {
            width: 20px;
            height: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            position: absolute;
            top: 10px;
            right: -25px;
        }

    </style>
    <body>
        <div id="wrapper">
            <div id="left_pannel">
                <div id="user_info"  style="padding: 10px;">
                <img id="profile_image" src="ui/images/user_male.jpg" >
                <br>
                <span id= "username" > username </span>
                <br>
                <span id= "email" style="font-size: 12px;opacity: 0.5;">email@gmail.com</span>
                <br>
                <br>
                <br>
                <div>
                        <label id="label_chats" for="radio_chats">Chat <img src="ui/icons/chat.png"></label>
                        <label id="label_contacts" for="radio_contacts">Contacts<img src="ui/icons/contacts.png"></label>
                        <label id="label_settings"  for="radio_settings">Settings<img src="ui/icons/settings.png"></label>
                        <label id="logout"  for="radio_logout">Logout<img src="ui/icons/logout.png"></label>
                    </div>
                </div>

                
            </div>
            <div id="right_pannel">
                <div id="header">
                <div id = "loader_holder" class="loader_on"><img style= "width : 70px;" src="ui/icons/giphy.gif"></div>
                <div  id="image_viewer" class="image_off" onclick="close_image(event)"></div>
                My Chat
            </div>
                <div id="container" style="display: flex;">
                
                <div id="inner_left_pannel">
                   

                </div>
                     <input type="radio" id="radio_chats" name="myradio" style="display: none">
                     <input type="radio" id="radio_contacts" name="myradio" style="display: none">
                     <input type="radio" id="radio_settings" name="myradio" style="display: none">

                     
                 <div id="inner_right_pannel"></div>
            </div>
        </div>
        </div>

 

<script type="text/javascript">

    
    var sent_audio =new Audio("message-send.mp3");
    var received_audio =new Audio("message-received.mp3");
    var CURRENT_CHAT_USER = "";
    var SEEN_STATUS = false;
    function _(element){

        return document.getElementById(element);
    }

    
   
    var label_contacts = _("label_contacts");
    label_contacts.addEventListener("click",get_contacts);

    var label_chats = _("label_chats");
    label_chats.addEventListener("click",get_chats);

    var label_settings = _("label_settings");
    label_settings.addEventListener("click",get_settings);

    var logout = _("logout");
    logout.addEventListener("click",logout_user);

    function get_data(find,type)
    {
        var xml =new XMLHttpRequest();
        var loader_holder = _("loader_holder");
        loader_holder.className = "loader_on";

        xml.onload =function () {

            if(xml.readyState == 4 || xml.status == 200){

                loader_holder.className = "loader_off";
                //alert(xml.responseText);
                handle_result(xml.responseText,type);
            }


        }

        var data = {};
        data.find = find;
        data.data_type = type;
        data =JSON.stringify(data);

        xml.open("POST", "api.php", true);
        xml.send(data);


    }
    
    function handle_result(result, type) {
        
    if (result.trim() != "") {
        var inner_right_pannel = _("inner_right_pannel");
        inner_right_pannel.style.overflow = "visible";
        
        var obj = JSON.parse(result);
        if (typeof obj.logged_in != "undefined" && !obj.logged_in) {
            window.location = "login.php";
        } else {
            switch (obj.data_type) {
                case "user_info":
                    var username = _("username");
                    var email = _("email");
                    var profile_image = _("profile_image");

                    username.innerHTML = obj.username;
                    email.innerHTML = obj.email;
                    profile_image.src = obj.image;
                    break;

                case "contacts":
                    var inner_left_pannel = _("inner_left_pannel");
                    inner_left_pannel.innerHTML = obj.message;
                    inner_right_pannel.style.overflow = "hidden";
                    break;

                    case "chats_refresh":
                    var SEEN_STATUS = false;
                    var messages_holder = _("messages_holder");
                    

                    // Sauvegarder la position de défilement actuelle
                    var isScrolledToBottom = messages_holder.scrollTop + messages_holder.clientHeight >= messages_holder.scrollHeight - 10;

                    // Créer un conteneur temporaire pour les nouveaux messages
                    const tempDiv = document.createElement("div");
                    tempDiv.innerHTML = obj.messages; // Convertir la réponse en éléments DOM
                    const newMessages = Array.from(tempDiv.children);

                    newMessages.forEach(message => {
                        const msgid = message.getAttribute("msgid");

                        // Vérifier si le message est déjà dans le DOM via son msgid
                        const existingMessage = messages_holder.querySelector(`[msgid='${msgid}']`);

                        if (existingMessage) {
                            // Mettre à jour l'icône (si le message est vu ou reçu)
                            const existingIcon = existingMessage.querySelector("div img");  // L'icône de statut
                            const newIcon = message.querySelector("div img");  // Nouvelle icône de statut du message

                            // Si l'icône a changé, mettre à jour l'icône existante
                            if (existingIcon && newIcon) {
                                existingIcon.src = newIcon.src;
                            }
                        } else {
                            // Si le message n'existe pas encore, on l'ajoute au DOM
                            messages_holder.appendChild(message);
                        }
                    });

                    // Ajuster le défilement si l'utilisateur était déjà en bas
                    if (isScrolledToBottom) {
                        messages_holder.scrollTop = messages_holder.scrollHeight;
                    }

                    // Si un nouveau message est reçu, jouer un son et ajuster le défilement
                    if (typeof obj.new_message !== "undefined" && obj.new_message) {
                        received_audio.play();
                        setTimeout(function () {
                            if (isScrolledToBottom) {
                                messages_holder.scrollTo(0, messages_holder.scrollHeight);
                            }
                            var message_text = _("message_text");
                            message_text.focus();
                        }, 100);
                    }
                    break;


                case "send_message":
                    sent_audio.play();
                case "chats":
                    var SEEN_STATUS = false;
                    var inner_left_pannel = _("inner_left_pannel");
                    inner_left_pannel.innerHTML = obj.user;
                    inner_right_pannel.innerHTML = obj.messages;

                    var messages_holder = _("messages_holder");
                    setTimeout(function () {
                        messages_holder.scrollTo(0, messages_holder.scrollHeight);
                        var message_text = _("message_text");
                        message_text.focus();
                    }, 100);

                    if (typeof obj.new_message != "undefined") {
                        if (obj.new_message) {
                            received_audio.play();
                        }
                    }
                    break;

                case "settings":
                    var inner_left_pannel = _("inner_left_pannel");
                    inner_left_pannel.innerHTML = obj.message;
                    inner_right_pannel.style.overflow = "hidden";
                    break;

                case "send_image":
                    alert(obj.message);
                    break;

                case "save_settings":
                    alert(obj.message);
                    get_data({}, "user_info");
                    get_settings(true);
                    break;
            }
        }
    }
}

    

    function logout_user ()
    {
        var answer =confirm(" etes vous sur de vouloir vous deconnecter ?? ");
        if (answer){
        get_data({},"logout");
        }
    }

    get_data({},"user_info");
    get_data({}, "contacts");

    var radio_contacts = _("radio_contacts");
    radio_contacts.checked =true;

    function get_contacts(e)
    {

        get_data({}, "contacts");
    }
    
    function get_chats(e)
    {

        get_data({}, "chats");
    }

    
    function get_settings(e)
    {

        get_data({}, "settings");
    }

    
    function send_message(e)
    {

        
        var message_text = _("message_text");
        if(message_text.value.trim() == ""){
        alert("s'il vous plait ecrivez quelques choses");
        
        return;
        }

        get_data({
            message:message_text.value.trim(),
            userid:CURRENT_CHAT_USER

        }, "send_message");

       
    }

    function enter_pressed(e)
    {
        if(e.keyCode == 13)
        {
            send_message(e);
        }
        SEEN_STATUS = true;
    }

    setInterval(function(){

        var radio_chats = _("radio_chats");
        var radio_contacts = _("radio_contacts");
        var radio_settings = _("radio_settings");

        if(CURRENT_CHAT_USER != "" && radio_chats.checked)
        {

           
            get_data({
                userid: CURRENT_CHAT_USER,
                seen: SEEN_STATUS

            }, "chats_refresh");

        }

        
        if( radio_contacts.checked)
        {

           
            get_data({}, "contacts");

        }

        
        
    },5000);

    function set_seen(e){

         SEEN_STATUS = true;
    }

    function delete_message(e){

        if(confirm(" etes vous sur de vouloir supprimer ce message ?? "))
        {

            var msgid = e.target.getAttribute("msgid");
            get_data({
                rowid: msgid
            }, "delete_message");

            
            get_data({
                userid: CURRENT_CHAT_USER,
                seen: SEEN_STATUS

            }, "chats_refresh");
            // Supprimer le message visuellement avant d'appeler l'API
        var messageElement = document.querySelector(`[msgid='${msgid}']`);
        if (messageElement) {
            messageElement.remove();
        }

        get_data({ rowid: msgid }, "delete_message");
        get_data({ userid: CURRENT_CHAT_USER, seen: SEEN_STATUS }, "chats_refresh");
        }
    }

    function delete_thread(e){

if(confirm(" etes vous sur de vouloir supprimer tout le chat ?? "))
{

    get_data({
        userid: CURRENT_CHAT_USER,
    }, "delete_thread");

    
    get_data({
        userid: CURRENT_CHAT_USER,
        seen: SEEN_STATUS

    }, "chats_refresh");
}
}
    
</script>


<script type="text/javascript">



function collect_data() {

    var  save_settings_button = _("save_settings_button");
    save_settings_button.disabled = true;
    save_settings_button.value = "loading...Please Wait..";

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

            case "gender":
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
    
    send_data(data, "save_settings");
    
}

function send_data(data, type) {
    var xml = new XMLHttpRequest();
    
    xml.onload = function() {
        if (xml.readyState === 4 || xml.status === 200) {  // Correction de la condition
            handle_result(xml.responseText);
            var  save_settings_button = _("save_settings_button");
            save_settings_button.disabled = false;
            save_settings_button.value = "Signup";

        }
    }

    data.data_type = type;  // Assigner data_type avant conversion
    var data_string = JSON.stringify(data);

    xml.open("POST", "api.php", true);
    xml.send(data_string);  // Envoyer data_string au lieu de data
}

function upload_profile_image(files){

    var filename = files[0].name;
    var ext_start = filename.lastIndexOf(".");
    var ext = filename.substr(ext_start + 1).toLowerCase(); // Convertir l'extension en minuscules
    if (!(ext == "jpg" || ext == "jpeg" || ext == "png" || ext == "gif" || ext == "bmp" || ext == "svg" || ext == "webp" || ext == "heic" || ext == "heif")) {
        alert("Cette image n'est pas prise en charge !\nVeuillez utiliser une image JPG, JPEG, PNG, GIF, BMP, SVG ou WEBP.");
        return;
    }

    
    var  change_image_button = _("change_image_button");
    change_image_button.disabled = true;
    change_image_button.innerHTML = "image mise a jour....";

    var myform =new FormData();
    var xml = new XMLHttpRequest();
    
    xml.onload = function() {
        if (xml.readyState === 4 || xml.status === 200) {  // Correction de la condition

            // alert(xml.responseText);
            get_data({},"user_info");
            get_settings(true);

            change_image_button.disabled = false;
            change_image_button.innerHTML = "Changer profil";

        }
    }

    myform.append('file', files[0])   ; 
    myform.append('data_type', "change_profile_image")   ;  // Assigner data_type avant conversion
    
    xml.open("POST", "uploader.php", true);
    xml.send(myform);  // Envoyer data_string au lieu de data

}


function delete_profile_image() {
    var change_image_button = _("change_image_button");
    change_image_button.disabled = true;
    change_image_button.innerHTML = "Suppression en cours...";

    var myform = new FormData();
    var xml = new XMLHttpRequest();

    xml.onload = function() {
        if (xml.readyState === 4 || xml.status === 200) {  // Correction de la condition
            var response = JSON.parse(xml.responseText);
            if (response.success) {
                // Réinitialisation de l'image à une image par défaut après suppression
                var profileImageElement = document.getElementById("profile_image");
                if (profileImageElement) {
                    profileImageElement.src = "ui/images/icone.jpg"; // Image par défaut
                    profileImageElement.alt = "Image supprimée";
                }
                alert("L'image de profil a été supprimée avec succès.");
            } else {
                alert("Une erreur est survenue lors de la suppression de l'image.");
            }
            change_image_button.disabled = false;
            change_image_button.innerHTML = "Changer profil";
        }
    };

    myform.append('data_type', "delete_profile_image");  // Spécifier l'action
    xml.open("POST", "uploader.php", true);
    xml.send(myform);
}


function handle_drag_and_drop(e){

    if(e.type == "dragover"){

        e.preventDefault ();
        e.target.className = "dragging";

    }else if(e.type == "dragleave"){

        e.target.className = "";
    
    }else if(e.type == "drop"){

        e.preventDefault ();
        e.target.className = "";

        upload_profile_image(e.dataTransfer.files);

    }
    else
    {
        e.target.className = "";
    }
}

function start_chat (e){

    var userid = e.target.getAttribute("userid");
    if(e.target.id == ""){

        userid = e.target.parentNode.getAttribute("userid");
    }
    CURRENT_CHAT_USER = userid;

    var radio_chats = _("radio_chats");
    radio_chats.checked = true ;
    get_data({userid: CURRENT_CHAT_USER}, "chats");
}

function send_image(files){
   
    var filename = files[0].name;
    var ext_start = filename.lastIndexOf(".");
    var ext = filename.substr(ext_start + 1).toLowerCase(); // Convertir l'extension en minuscules
    if (!(ext == "jpg" || ext == "jpeg" || ext == "png" || ext == "gif" || ext == "bmp" || ext == "svg" || ext == "webp" || ext == "heic" || ext == "heif")) {
        alert("Cette image n'est pas prise en charge !\nVeuillez utiliser une image JPG, JPEG, PNG, GIF, BMP, SVG ou WEBP.");
        return;
    }
    
    var myform =new FormData();
    var xml = new XMLHttpRequest();
    
    xml.onload = function() {
        if (xml.readyState === 4 || xml.status === 200) {  // Correction de la condition

            handle_result(xml.responseText, "send_image");
            get_data({
                userid: CURRENT_CHAT_USER,
                seen: SEEN_STATUS

            }, "chats_refresh");
           
        }
    }

    myform.append('file', files[0])   ; 
    myform.append('data_type', "send_image")   ;  // Assigner data_type avant conversion
    myform.append('userid', CURRENT_CHAT_USER)   ;  

    xml.open("POST", "uploader.php", true);
    xml.send(myform);  // Envoyer data_string au lieu de data
}

/**
 * Ferme le visualiseur d'image en réinitialisant l'affichage.
 * @param {Event} e - Événement déclenché lors de la fermeture de l'image.
 */
function close_image(e) {
    const image_viewer = document.getElementById("image_viewer");
    if (image_viewer) {
        // Désactive complètement l'affichage
        image_viewer.style.display = "none";
        image_viewer.innerHTML = ""; // Supprime le contenu
    }
}

/**
 * Ferme le visualiseur d'image en réinitialisant l'affichage.
 * @param {Event} e - Événement déclenché lors de la fermeture de l'image.
 */
function close_image(e) {
    const image_viewer = document.getElementById("image_viewer");
    if (image_viewer) {
        image_viewer.style.display = "none";
        image_viewer.innerHTML = ""; // Supprime le contenu
    }
}

/**
 * Affiche une image dans un visualiseur esthétique avec une notification cliquable pour télécharger.
 * @param {Event} e - Événement déclenché par le clic sur une image.
 * @param {boolean} allowDownload - Permet d'activer ou non la notification pour télécharger l'image.
 */
function image_show(e, allowDownload = true) {
    const image = e.target.src;
    if (!image) {
        console.warn("Aucune source d'image valide trouvée.");
        return;
    }

    const image_viewer = document.getElementById("image_viewer");
    if (image_viewer) {
        // Réinitialiser le contenu
        image_viewer.innerHTML = "";

        // Créer une image avec un style esthétique
        const imgElement = document.createElement("img");
        imgElement.src = image;
        imgElement.style.width = "45%"; // Réduit la largeur à 50% de la fenêtre
        imgElement.style.maxWidth = "500px"; // Ne dépasse jamais 600px de largeur
        imgElement.style.borderRadius = "10px";
        imgElement.style.boxShadow = "0 4px 15px rgba(0, 0, 0, 0.3)";
        imgElement.alt = "Image Preview";

        // Ajouter des animations pour le conteneur
        image_viewer.style.display = "flex";
        image_viewer.style.justifyContent = "center";
        image_viewer.style.alignItems = "center";
        image_viewer.style.flexDirection = "column";
        image_viewer.style.backgroundColor = "rgba(0, 0, 0, 0.8)";
        image_viewer.style.position = "fixed";
        image_viewer.style.top = "0";
        image_viewer.style.left = "0";
        image_viewer.style.width = "100%";
        image_viewer.style.height = "100%";
        image_viewer.style.zIndex = "1000";

        /**
 * Affiche une notification cliquable pour télécharger l'image.
 * @param {string} message - Le texte de la notification.
 * @param {string} imageUrl - L'URL de l'image à télécharger.
 */
function show_notification(message, imageUrl) {
    const notification = document.createElement("div");
    notification.style.position = "absolute";
    notification.style.bottom = "20px";
    notification.style.left = "50%";
    notification.style.transform = "translateX(-50%)";
    notification.style.padding = "10px 20px";
    notification.style.backgroundColor = "rgba(0, 0, 0, 0.8)";
    notification.style.color = "white";
    notification.style.fontSize = "16px";
    notification.style.borderRadius = "5px";
    notification.style.cursor = "pointer";
    notification.innerHTML = message;

    // Ajoute l'événement de téléchargement
    notification.addEventListener("click", function () {
        const link = document.createElement("a");
        link.href = imageUrl;
        link.download = imageUrl.substring(imageUrl.lastIndexOf("/") + 1); // Utilise le nom du fichier dans l'URL
        link.click();
    });

    // Ajoute la notification à l'écran
    const image_viewer = document.getElementById("image_viewer");
    image_viewer.appendChild(notification);
}


        // Ajoute l'image dans le visualiseur
        image_viewer.appendChild(imgElement);

        // Applique la classe pour afficher le visualiseur
        image_viewer.className = "image_on";

        // Si téléchargement activé, afficher une notification cliquable
        if (allowDownload) {
            show_notification("Télécharger l'image", image);
        }
    }
}

</script>



</body>
</html>