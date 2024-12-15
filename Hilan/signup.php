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

        

        

        #inner_left_pannel{
            background-color: #383e48;
            flex: 1;
            min-height: 430px;
            text-align: center;
        }

        #inner_right_pannel{
            background-color: #f2f7f8;
            flex: 2;
            min-height: 430px;
        }

        #inner_right_pannel{

            background-color: #f2f7f8;
            flex:2;
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

        input[type=text], input[type=password], input[type=button]{

            padding: 10px;
            margin: 10px;
            width: 98%;
            border-radius: 5px;
            border: solid 1px grey;
        }

        input[type=button]{
            width: 103%;
            cursor: pointer;
            background-color: #2b5488;
            color: white;

        }

        input[type=radio]{
            transform: scale(1.2);
            cursor: pointer;

        }

        #header {

        background-color: #485b6c;
        font-size: 40px;
        text-align: center;
        font-family: headFont;
        width: 100%;
        color: white;

}
        #error {
            text-align: center; 
            padding: 0.5em; 
            background-color: #ecaf91; 
            color: white; 
            display : none;
        }


    </style>
    <body>
        <div id="wrapper">
            <div id="header">
                My Chat
            <div style="font-size: 20px;font-family: myFont;">signup</div>
            </div>
            <div id="error" style=""> MEME TEXT </div>
            <form id="myform">
                <input type="text" name="username" placeholder="Username"><br>
                <input type="text" name="email" placeholder="Email"><br>
                <div style="padding: 10px;">
                <br>Genre:<br>
                <input  type="radio"  value="Male" name="gender_male"> Homme<br>
                <input  type="radio" value="female" name="gender_female"> femme<br> 
             </div>
                <input type="password" name="password" placeholder="Password"><br>
                <input type="password" name="password2" placeholder="Retype password"><br>
                  <input type="button" value="Sign up" id="signup_button"><br> 
                <br>

                <a href="login.php" style="display: block;text-align: center;text-decoration:none">

                Deja un compte? Connectez vous ici.
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
</script>


