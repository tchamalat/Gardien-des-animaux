
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

        #wrapper{

            max-width: 900px;
            min-height: 500px;
            max-height: 630px;
            display: flex;
            margin: auto;
            color: white;
            font-family: myFont;
            font-size: 13px;
        }
        #left_pannel{

            min-height: 500px;
            background-color: #27344b;
            flex: 1;
            text-align: center;
          
            
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
          background-color: #404b56;
          border-bottom: solid thin #ffffff55;
          cursor: pointer;
          padding: 5px;
          transition: all 1s ease ;
          
        }
        #left_pannel label:hover{

             background-color: #778593;
            
}
    

        #left_pannel label img{

            float: right;
            width: 25px;

          
        }


        #right_pannel{

            min-height: 500px;
            flex: 4;
            
        }

        

        #header {

            background-color: #485b6c;
            height: 70px;
            font-size: 40px;
            text-align: center;
            font-family: headFont;
            position : relative;
            
        }

        #inner_left_pannel{
            background-color: #383e48;
            flex: 1;
            min-height: 430px;
            text-align: center;
            max-height: 530px;
        }

        

        #inner_right_pannel{

            background-color: #f2f7f8;
            flex:2;
            min-height: 430px;
            transition: all 2s ease;
            max-height: 530px;
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
        }

        
        #active_contact img{

            width: 70px;
            height: 70px;
            float: left;
            margin: 2px;
        }
        #message_container {
    display: flex;
    flex-direction: column;
    width: 100%;
}

        #message_left {
            margin: 10px;
            padding: 2px 10px;
            background-color: beige;
            color: black;
            box-shadow: 0px 0px 10px #aaa;
            width: 60%;
            position: relative;
            border-radius: 20px;
            align-self: flex-start; /* Aligner le message à gauche */
            min-width: 200px;
            border-top-right-radius: 30%; 
            word-wrap: break-word; /* Coupe les mots longs pour éviter le débordement */
            overflow-wrap: break-word;
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
    padding: 2px 10px;
    background-color: #e9a220;
    color: #262626;
    box-shadow: 0px 0px 10px #aaa;
    width: 60%;
    position: relative;
    border-radius: 20px;
    align-self: flex-end; /* Aligner le message à droite */
    min-width: 200px;
    border-top-left-radius: 30%;
    word-wrap: break-word; /* Coupe les mots longs pour éviter le débordement */
    overflow-wrap: break-word;
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
    
    function handle_result(result,type) {
       
        
       if(result.trim() != "")
       {
        var inner_right_pannel = _("inner_right_pannel");
        inner_right_pannel.style.overflow = "visible";
        
            var obj =JSON.parse(result);
            if(typeof(obj.logged_in) != "undefined" && !obj.logged_in){

                window.location = "login.php";
            }else{

                switch(obj.data_type)
                 {
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
                            var messages_holder= _("messages_holder");
                            messages_holder.innerHTML = obj.messages;
                            console.log("Nouveau message :", obj.new_message);
                            if(typeof obj.new_message != 'undefined'){
                                if(obj.new_message){
                                    received_audio.play();

                                    setTimeout(function(){

                                    messages_holder.scrollTo(0, messages_holder.scrollHeight);
                                    var message_text = _("message_text");
                                    message_text.focus();

                                    },100);
                                }
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
                            
                            setTimeout(function(){

                                messages_holder.scrollTo(0, messages_holder.scrollHeight);
                                var message_text = _("message_text");
                                message_text.focus();

                            },100);

                            
                            if(typeof obj.new_message != 'undefined'){
                                if(obj.new_message){
                                    received_audio.play();
                                }
                            }

                            break;
                            
                        case "settings":

                            var inner_left_pannel = _("inner_left_pannel");
                            inner_left_pannel.innerHTML = obj.message;
                            break;

                        case "send_image":
                            alert(obj.message);
                            break;

                        case "save_settings":
                            alert(obj.message);
                            get_data({},"user_info");
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

        if(CURRENT_CHAT_USER != "")
        {

           
            get_data({
                userid: CURRENT_CHAT_USER,
                seen: SEEN_STATUS

            }, "chats_refresh");

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
    var ext = filename.substr(ext_start + 1,3);
    if(!(ext == "jpg" || ext == "JPG")){
        alert("cette image n'est pas prise en charge!\nPrenez une image.jpg");
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
    var ext = filename.substr(ext_start + 1,3);
    if(!(ext == "jpg" || ext == "JPG")){
        alert("cette image n'est pas prise en charge!\nPrenez une image.jpg ");
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
</script>

</body>
</html>