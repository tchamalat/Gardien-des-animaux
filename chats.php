<?php
 
 $arr['userid'] = "null"; 
if (isset($DATA_OBJ->find->userid)) {

$arr['userid']= $DATA_OBJ->find->userid;

}

$sql = "select * from users where userid = :userid  limit 1";
$result = $DB->read($sql,$arr);

if(is_array($result)){
    //user found

        $row = $result[0];

           
        $image  = ($row->gender == "Male")? "ui/images/user_male.jpg" : "ui/images/user_female.png";
        if (file_exists($row->image)){
           $image = $row->image;
        }

        $row->image =$image;

        $mydata = "Maintenant vous pouvez discuter avec : <br>
        <div id='active_contact'>
                <img src='$image'>
                $row->username
            </div>";

        $messages = "

       
        <div id='messages_holder_parent' style = ' height : 630px; '>
        <div id='messages_holder' style = ' height : 480px; overflow-y : scroll;'>
        <div id='message_container'>";


        

        $messages .= "
                     
                
                </div>
                </div>

                 <div style='display:flex;  with: 100%; height: 40px;'> 
                 <label for = 'file'><img src = 'ui/icons/clip.png' style='opacity : 0.8; width : 30px ; margin:5px; cursor: pointer;' ></label>
                 <input type = 'file' id = 'file'name = 'file' style='display: none' />
                 <input style='flex:6; border: solud thin #ccc; border-bottom : none ;font-size : 14px; padding = 4px' type='text' placeholder='ecrivez votre message '/>
                  <input style='flex:1; cursor : pointer;' type='button' value='send'/>
                 </div>
                 </div>";
                              


        $info->user = $mydata;
        $info->messages = $messages;
        $info->data_type = "chats";
        echo json_encode( $info);

   

}else{
    // user not found
    $info->message = "Ce contact n'a pas ete trouvé";
    $info->data_type = "chats";
    echo json_encode($info);
}


 


?>

        
       
