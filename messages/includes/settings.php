<?php
sleep(1);

$sql = "select * from users where userid = :userid limit 1";
$id = $_SESSION[ 'userid'];
$data = $DB->read($sql, ['userid' => $id]);

$mydata = "";

if(is_array($data)) 
{

    $data = $data[0];

    // verifier si l'image existe
    $image  = ($data->gender == "Male")? "ui/images/user_male.jpg" : "ui/images/user_female.png";
    if (file_exists($data->image)){
       $image = $data->image;
    }

    $gender_male = "";
    $gender_female = "";

    if($data->gender == "Male")
    {
        $gender_male = "checked";
    }else {

    $gender_female = "checked";
    }

    $mydata ='
                
                <style type="text/css">

                            @keyframes appear {
                                0% {opacity: 0; transform: translateY(50px) rotate(5deg); transform-origin: 100% 100%;}
                                100% {opacity: 1; transform: translateY(0px) rotate(0deg); transform-origin: 100% 100%;}
                            }

                    form {
                        text-align: left;
                        margin: auto;
                        padding: 10px;
                        width: 100%;
                        max-width: 400px;
                        background: linear-gradient(135deg, #fffaf0, #fbe0c7);
                        border-radius: 10px;
                        box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
                    }
                    input[type=text], input[type=password], input[type=button] {
                        padding: 10px;
                        margin: 10px;
                        width: 200px;
                        border-radius: 5px;
                        border: solid 1px grey;
                        font-size: 14px;
                        transition: all 0.3s ease;
                    }

                    input[type=text]:focus, input[type=password]:focus {
                        border: solid 1px #ffcc99;
                        outline: none;
                        box-shadow: 0px 2px 4px rgba(255, 204, 153, 0.5);
                    }

                    input[type=button] {
                        width: 220px;
                        cursor: pointer;
                        background: linear-gradient(90deg, #ffb366, #e69847);
                        color: white;
                        border: none;
                        transition: all 0.3s ease;
                    }

                    input[type=button]:hover {
                        background: linear-gradient(90deg, #e69847, #ffb366);
                        box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.2);
                    }

                    input[type=radio] {
                        transform: scale(1.2);
                        cursor: pointer;
                    }

                    #error {
                        text-align: center; 
                        padding: 0.5em; 
                        background-color: #ecaf91; 
                        color: white; 
                        display: none;
                        border-radius: 5px;
                        margin-bottom: 15px;
                    }

                    .dragging {
                        border: dashed 2px #aaa;
                    }           

                </style>
                
                <div id="error">Erreur</div>
                <div style="display:flex; animation: appear 1s ease; gap: 15px;">
                    <div style="text-align: center;">
                        <span style="font-size: 12px;">Faite glisser et déposer une image</span><br>
                        <img ondragover="handle_drag_and_drop(event)" 
                             ondrop="handle_drag_and_drop(event)" 
                             ondragleave="handle_drag_and_drop(event)" 
                             src="'.$image.'" 
                             style="width: 200px; height: 200px; margin: 10px; border-radius: 10px; box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);" />
                        <label for="change_image_input" id="change_image_button" 
                               style="background-color: #9b9a80; display: inline-block; padding: 1em; border-radius: 5px; cursor: pointer; color: white;">
                            Changer profil
                        </label>
                        <input type="file" onchange="upload_profile_image(this.files)" id="change_image_input" style="display:none;">

                        
                         <label id="delete_image_button" onclick="delete_profile_image()" style="background-color: #d9534f; color: white; padding: 1em; border-radius: 5px; border: none; cursor: pointer; margin-top: 10px;">
                        
                          Supprimer profil
                        </label>
                    </div>

                    <form id="myform">
                        <input type="text" name="username" placeholder="Username" value="'.$data->username.'"><br>
                        <input type="text" name="email" placeholder="Email" value="'.$data->email.'"><br>
                        <div style="padding: 10px;">
                            <br>Genre:<br>
                            <input type="radio" value="Male" name="gender" '.$gender_male.'> Homme<br>
                            <input type="radio" value="female" name="gender" '.$gender_female.'> Femme<br> 
                        </div>
                        <input type="password" name="password" placeholder="Password" value="'.$data->password.'"><br>
                        <input type="password" name="password2" placeholder="Retype Password" value="'.$data->password.'"><br>
                        <input type="button" value="Save Settings" id="save_settings_button" onclick="collect_data(event)"><br> 
                    </form>
                </div>
                ';

$info->message = $mydata;
$info->data_type = "settings";
echo json_encode($info);

die;
} else {

$info->message = $Error = "Aucun contact n'a été trouvé";
$info->data_type = "error";
echo json_encode($info);
}
?>
