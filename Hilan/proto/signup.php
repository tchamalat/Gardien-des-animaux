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



    </style>
    <body>
        <div id="wrapper">
            <div id="header">
                My Chat
            <div style="font-size: 20px;font-family: myFont;">Login</div>
            
            </div>
            <form>
                <input type="text" name="usename" placeholder="Username"><br>
                <div style="padding: 10px;">
                <br>Genre:<br>
                <input type="radio" name="genre"> Homme<br>
                <input type="radio" name="genre"> femme<br> 
                <input type="password" name="password" placeholder="password"><br>
                <input type="password" name="password2" placeholder="Retype password"><br>
                <input type="submit" value="Sign up"> <br>

            </form>
        </div>
    </body>
</html>

<script type="text/javascript">

    function _(element){

        return document.getElementById(element);
    }
    var label = _("label_chat");
    label.addEventListener("click",function(){

        var inner_pannel = _("inner_left_pannel");
        var ajax = new XMLHttpRequest();
        ajax.onload =function(){

            if(ajax.status == 200 || ajax.readyState == 4){
                inner_pannel.innerHTML = ajax.responseText;
            }

        }

        ajax.open("POST", "file.php", true);
        ajax.send();

       

    });

        
    
    
   

</script>