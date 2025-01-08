<?php
    
    $info = (object)[];
    
    $data = [];
//valide info
   

    $data['email'] = $DATA_OBJ->email ;

    if (empty($DATA_OBJ->email))
    {
        $Error = "s'ils vous plait entrer un email valide";
    }

    if (empty($DATA_OBJ->password))
    {
        $Error = "s'ils vous plait entrer un mot de passe valide";
    }


   
    
    if ($Error == "")
    {


        $query = "select * from users where email = :email limit 1";
        $result = $DB->read($query,$data);

        if (is_array($result))
        {
            
            $result = $result[0];
            if($result->password ==  $DATA_OBJ->password )
            {
                $_SESSION['userid'] = $result->userid;
                $info->message = $Error = "Vous etes connecté avec succes";
                $info->data_type = "info";
                echo json_encode($info);

            }else
            {
                $info->message = $Error = "Mot de passe incorrect";
                $info->data_type = "error";
                echo json_encode($info);
        
            }
            

        }
        else
        {
            
            $info->message = $Error = "Email incorrect";
            $info->data_type = "error";
            echo json_encode($info);
        }

    }else
    {
        $info->message = $Error;
        $info->data_type = "error";
        echo json_encode($info);

    }

