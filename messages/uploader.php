<?php
//print_r($_POST);
session_start();

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

$data_type = "";
if(isset($_POST['data_type'] )){
    $data_type = $_POST ['data_type'];
}


$destination ="";
if(isset($_FILES['file']) && $_FILES['file']['name'] != ""){

    $allowed[] = "image/jpeg";
    $allowed[] = "image/png";

    $_FILES['file']['type'];
    if($_FILES['file']['error'] == 0 && in_array($_FILES['file']['type'], $allowed)){

        // Configuration du dossier de téléchargement
        $upload_folder = __DIR__ . "/uploads/"; // Chemin absolu sur le serveur
        $base_url = "https://gardien-des-animaux.fr/messages/uploads/"; // URL publique


         // Déplacer le fichier
        $filename = basename($_FILES['file']['name']);
        $destination = $upload_folder . $filename;
        if (move_uploaded_file($_FILES['file']['tmp_name'], $destination)) {
            // Préparer l'URL publique pour le client
            $public_url = $base_url . $filename;
            $info->message = "Votre image a été téléchargée avec succès.";
            $info->data_type = $data_type;
            $info->file_url = $public_url; // URL à renvoyer au client
            echo json_encode($info);
        } else {
            $info->message = "Échec lors du téléchargement de l'image.";
            echo json_encode($info);
        }
        die;
    } else {
        $info->message = "Type de fichier non autorisé ou erreur détectée.";
        echo json_encode($info);
        die;
    }
}



if ($data_type == "change_profile_image"){
    
    if ($destination != ""){

        //savedatabase
        $id = $_SESSION['userid'];
        $query = "update users set image = '$destination' where userid = '$id' limit 1";
        $DB->write($query,[]);
    }

} elseif ($data_type == "delete_profile_image") {
    // Suppression de l'image de profil
    if (isset($_SESSION['userid'])) {
        $id = $_SESSION['userid'];

        // Obtenir le chemin actuel de l'image
        $query = "SELECT image FROM users WHERE userid = :userid LIMIT 1";
        $result = $DB->read($query, ['userid' => $id]);

        if ($result && isset($result[0]->image)) {
            $imagePath = str_replace("https://gardien-des-animaux.fr/messages/uploads/", __DIR__ . "/uploads/", $result[0]->image);


            // Supprimer l'image du serveur
            if (file_exists($imagePath)) {
                unlink($imagePath);  // Supprimer le fichier image
            }

            // Supprimer l'image dans la base de données
            $query = "UPDATE users SET image = NULL WHERE userid = :userid LIMIT 1";
            $DB->write($query, ['userid' => $id]);

            // Retourner une réponse de succès
            $info->success = true;
            echo json_encode($info);
        } else {
            $info->success = false;
            $info->message = "Aucune image trouvée.";
            echo json_encode($info);
        }
    } else {
        $info->success = false;
        $info->message = "Utilisateur non connecté.";
        echo json_encode($info);
    }

}elseif ($data_type == "send_image")
 {
    $arr['userid'] = $_POST['userid'] ?? null; // null natif PHP

    if (isset($_POST['userid'])) 
    {
    $arr['userid'] = addslashes( $_POST['userid']);
    }
    
    $arr['message'] = "";
    $arr['date'] = date("Y-m-d H:i:s");
    $arr['sender'] = $_SESSION['userid'];
    $arr['msgid'] = get_random_string_max(60);
    $arr['file'] = $public_url;


    $arr2['sender'] = $_SESSION['userid'];
    $arr2['receiver'] = $arr['userid'];

    $sql = "select * from messages where (sender = :sender && receiver = :receiver) ||  (receiver = :sender && sender = :receiver) limit 1";
    $result2 = $DB->read($sql, $arr2);

    if (is_array($result2)) {
        $arr['msgid'] = $result2[0]->msgid;
    }

    $query = "insert into messages (sender, receiver, message, date, msgid, files) values (:sender, :userid, :message, :date, :msgid, :file)";
    $DB->write($query, $arr);
 }

 function get_random_string_max($length) {
    $array = array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z', 'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');
    $text = "";
    $length = rand(4, $length);
    for ($i = 0; $i < $length; $i++) {
        $random = rand(0, 61);
        $text .= $array[$random];
    }
    return $text;
}
