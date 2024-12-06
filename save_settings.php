<?php
    
    $info = (object)[];

    $data = [];
    $data['userid'] = $_SESSION['userid'];

    $data['username'] = $DATA_OBJ->username ;
    if(empty($DATA_OBJ->username))
    {
        $Error .= "S'il vous plait entrer un nom valide . <br>";
    }else
    {
        if (strlen($DATA_OBJ->username) < 3)
        {
            $Error .= "le nom d'utilisateur doit au moins avoir 3 characters . <br> ";
        }

        if (!preg_match("/^[a-z A-Z]*$/", $DATA_OBJ->username) )
        {
            $Error .= "S'il vous plait entrer un nom valide . <br> ";
        }
    }

    $data['email'] = $DATA_OBJ->email ;

    if(empty($DATA_OBJ->email))
    {
        $Error .= "S'il vous plait entrer un email valide . <br>";
    }else
    {

        if (!preg_match("/([\w\-]+\@[\w\-]+\.[\w\-]+)/", $DATA_OBJ->email) )
        {
            $Error .= "S'il vous plait entrer un email valide . <br> ";
        }
    }

    $data['gender'] = isset($DATA_OBJ->gender) ? $DATA_OBJ->gender : null ;

    if(empty($DATA_OBJ->gender))
    {
        $Error .= "S'il vous plait selectionner un genre  . <br>";
    }else
    {

        if ( $DATA_OBJ->gender != "Male" && $DATA_OBJ->gender != "female" ) 
        {
            $Error .= "S'il vous plait selectionner un genre valide . <br> ";
        }
    }

    $data['password'] = $DATA_OBJ->password ;
    $password = $DATA_OBJ->password2 ;
    if(empty($DATA_OBJ->password))
    {
        $Error .= "S'il vous plait entrer un mot de passe valide . <br>";
    }else
    {
        if ($DATA_OBJ->password != $DATA_OBJ->password2)
        {
            $Error .= "s'il vous plait le mot de passe doit etre le meme . <br> ";
        }

        if (strlen($DATA_OBJ->password) < 8)
        {
            $Error .= "le Mot de passe doit au moins avoir 8 characters . <br> ";
        }

       
    }
    

   
    if ($Error == "")
    {
        $query = "update users set username = :username ,gender = :gender,email = :email,password = :password where userid = :userid limit 1";
        $result = $DB->write($query,$data);

        if ($result)
        {
            
            $info->message = $Error = "Tes donnees sont sauvegarder";
            $info->data_type = "save_settings";
            echo json_encode($info);

        }
        else
        {
            
            $info->message = $Error = "Tes donnees ne sont pas sauvegarder";
            $info->data_type = "save_settings";
            echo json_encode($info);
        }

    }else
    {
        $info->message = $Error;
        $info->data_type = "save_settings";
        echo json_encode($info);

    }
    
