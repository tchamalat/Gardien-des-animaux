<?php

$arr['userid'] = "null";
if (isset($DATA_OBJ->find->userid)) {
    $arr['userid'] = $DATA_OBJ->find->userid;
}

$refresh = false;
$seen = false;
if ($DATA_OBJ->data_type == "chats_refresh") {
    $refresh = true;
    $seen = $DATA_OBJ->find->seen;
}

// Récupérer les informations de l'utilisateur cible
$sql = "SELECT * FROM users WHERE userid = :userid LIMIT 1";
$result = $DB->read($sql, $arr);

if (is_array($result)) {
    $row = $result[0];
    $image = ($row->gender == "Male") ? "ui/images/user_male.jpg" : "ui/images/user_female.png";
    if (file_exists($row->image)) {
        $image = $row->image;
    }
    $row->image = $image;

    if (!$refresh) {
        $mydata = "Maintenant vous pouvez discuter avec : <br>
        <div id='active_contact'>
            <img src='$image'>
            $row->username
        </div>";
    }

    $messages = "";
    $new_message = false;

    if (!$refresh) {
        $messages = "
        <div id='messages_holder_parent' onclick='set_seen(event)' style='height: 630px;'>
        <div id='messages_holder' style='height: 480px; overflow-y: scroll;'>
        <div id='message_container'>";
    }

    // Charger les messages entre les deux utilisateurs
    $a['sender'] = $_SESSION['userid'];
    $a['receiver'] = $arr['userid'];

    $sql = "SELECT * FROM messages 
        WHERE 
            (sender = :sender AND receiver = :receiver AND deleted_sender = 0) 
            OR 
            (receiver = :sender AND sender = :receiver AND deleted_receiver = 0) 
        ORDER BY id DESC 
        LIMIT 10";
    $result2 = $DB->read($sql, $a);

    if (is_array($result2)) {
        $result2 = array_reverse($result2); // Inverser l'ordre pour un affichage chronologique
        foreach ($result2 as $data) {
            $myuser = $DB->get_user($data->sender);

            // Marquer comme reçu et vu si nécessaire
            if ($data->receiver == $_SESSION['userid'] && $data->received == 0) {
                $new_message = true;
            }
            if ($data->receiver == $_SESSION['userid'] && $data->received == 1 && $seen) {
                $DB->write("UPDATE messages SET seen = 1 WHERE id = '$data->id' LIMIT 1");
            }
            if ($data->receiver == $_SESSION['userid']) {
                $DB->write("UPDATE messages SET received = 1 WHERE id = '$data->id' LIMIT 1");
            }

            // Générer les messages
            if ($_SESSION['userid'] == $data->sender) {
                $messages .= message_right($data, $myuser);
            } else {
                $messages .= message_left($data, $myuser);
            }
        }
    }

    if (!$refresh) {
        $messages .= message_controls();
    }

    // Préparer la réponse pour le frontend
    $info->user = $mydata ?? "";
    $info->messages = $messages;
    $info->new_message = $new_message;
    $info->data_type = $refresh ? "chats_refresh" : "chats";
    echo json_encode($info);
} else {
    // Aucun utilisateur trouvé, afficher les discussions précédentes
    $sql = "SELECT 
    MAX(id) as id, 
    msgid, 
    MAX(sender) as sender, 
    MAX(receiver) as receiver, 
    MAX(message) as message, 
    MAX(date) as date
FROM messages 
WHERE (sender = :userid OR receiver = :userid)
GROUP BY msgid 
ORDER BY id DESC 
LIMIT 10";

$a['userid'] = $_SESSION['userid'];
    $result2 = $DB->read($sql, $a);
    $mydata = "Previews Chats:<br>";
    

    if (is_array($result2)) {
        $result2 = array_reverse($result2);
        foreach ($result2 as $data) {

            $other_user = $data->sender == $_SESSION['userid'] ? $data->receiver : $data->sender;
            $myuser = $DB->get_user($other_user);

            $mydata .= "
            <div id='active_contact' onclick='start_chat(event)' userid='$other_user' style= 'cursor: pointer;'>
                <img src='$myuser->image'>
                $myuser->username
            </div>";
        }
    }

    $info->user = $mydata;
    $info->messages = "";
    $info->data_type = "chats";
    echo json_encode($info);
}
?>
