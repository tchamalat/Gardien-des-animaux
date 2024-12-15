<?php


session_start();


$DATA_RAW = file_get_contents("php://input");
$DATA_OBJ = json_decode( $DATA_RAW);

$info = (object)[];

// check if logged in
if (!isset($_SESSION['userid']))
{

    if (isset($DATA_OBJ->data_type) && $DATA_OBJ->data_type  != "login" && $DATA_OBJ->data_type == "signup" )
    {
        $info->logged_in = false;
        echo json_encode($info);
        die;
    }
    
     
}
require_once("classes/autoload.php");
$DB = new Database();

$Error = "";

//process the data
if(isset($DATA_OBJ->data_type) && $DATA_OBJ->data_type == "signup") 
{
    
    // signup
    include("includes/Signup.php");

}elseif(isset($DATA_OBJ->data_type) && $DATA_OBJ->data_type == "login") 
{

    include("includes/login.php");

}elseif(isset($DATA_OBJ->data_type) && $DATA_OBJ->data_type == "logout") 
{
    include("includes/logout.php");  

}elseif(isset($DATA_OBJ->data_type) && $DATA_OBJ->data_type == "user_info") 
{

    // user info
    include("includes/user_info.php");  
}elseif(isset($DATA_OBJ->data_type) && $DATA_OBJ->data_type == "contacts") 
{
    include("includes/contacts.php"); 

}elseif(isset($DATA_OBJ->data_type) && ($DATA_OBJ->data_type == "chats" ||  $DATA_OBJ->data_type == "chats_refresh"))
{
    include("includes/chats.php");  

}elseif(isset($DATA_OBJ->data_type) && $DATA_OBJ->data_type == "settings") 
{
    include("includes/settings.php");  

}elseif(isset($DATA_OBJ->data_type) && $DATA_OBJ->data_type == "save_settings") 
{
    include("includes/save_settings.php");  

}elseif(isset($DATA_OBJ->data_type) && $DATA_OBJ->data_type == "send_message") 
{

    //send_message
    include("includes/send_message.php");  

}elseif(isset($DATA_OBJ->data_type) && $DATA_OBJ->data_type == "delete_message") 
{

    //send_message
    include("includes/delete_message.php");  

}elseif(isset($DATA_OBJ->data_type) && $DATA_OBJ->data_type == "delete_thread") 
{

    //send_message
    include("includes/delete_thread.php");  
}




function message_left($data, $row)
{
    return  "
    <div id='message_left'>
   <div></div>
    <img id= 'prof_img' src='$row->image'>
    <b> $row->username</b> <br>
    $data->message<br><br>

    <span style ='font-size :  11px; color: black;'>".date("jS M Y H:i:s a",strtotime($data->date))."</span>
    <img id='trash' src='ui/images/trash.jpeg' onclick = 'delete_message(event)' msgid='$data->id' />

    </div>";
    
}

function message_right($data, $row)
{
    $a = "
        <div id='message_right'>
            <div>";

    if ($data->seen) {
        $a .= "<img src='ui/images/tick_orange.webp' style=''/>";
    } elseif ($data->received) {
        $a .= "<img src='ui/images/tickBlack.png' style=''/>";
    }

    $a .= "</div> 
            <img  id= 'prof_img' src='$row->image' style='float: right;'>
            <b>$row->username</b> <br>
            $data->message<br><br>
            <img src='$data->files' style='width: 100%' /> <br>

            <span style='font-size: 11px; color: black;'>". date("jS M Y H:i:s a", strtotime($data->date)) ."</span>
            <img id='trash' src='ui/images/trash.jpeg' onclick = 'delete_message(event)' msgid='$data->id' />
        </div>";

    return $a;
}



function message_controls()
{


    
        return "
                </div>
                </div>
                <span onclick = 'delete_thread(event)' style= 'color:purple; cursor: pointer;'> Supprimez toute la discussion </span>
                <div style='display: flex; width: 100%; height: 40px;'>
                    <label for='message_file'><img src='ui/icons/clip.png' style='opacity: 0.8; width: 30px; margin: 5px; cursor: pointer;'></label>
                    <input type='file' id='message_file' name='file' style='display: none' onchange='send_image(this.files)'/>
                    
                    <input id='message_text' onkeyup = 'enter_pressed(event)' style='flex: 6; border: solid thin #ccc; border-bottom: none; font-size: 14px;  padding : 4px;' type='text' placeholder='écrivez votre message'/>
                    
                    <input style='flex: 1; cursor: pointer;' type='button' value='send' onclick='send_message(event)'/>
                </div>
                </div>";
}