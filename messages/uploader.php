<?php
session_start();

$info = (object)[];

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['userid'])) {
    if (isset($DATA_OBJ->data_type) && $DATA_OBJ->data_type != "login" && $DATA_OBJ->data_type != "signup") {
        $info->logged_in = false;
        echo json_encode($info);
        die;
    }
}

require_once("classes/autoload.php");
$DB = new Database();

$data_type = "";
if (isset($_POST['data_type'])) {
    $data_type = $_POST['data_type'];
}

$destination = "";
if (isset($_FILES['file']) && $_FILES['file']['name'] != "") {
    $allowed = ["image/jpeg", "image/png"];

    // Vérifier le type et les erreurs du fichier
    if ($_FILES['file']['error'] == 0 && in_array($_FILES['file']['type'], $allowed)) {
        // Configuration du dossier de téléchargement
        $upload_folder = __DIR__ . "/uploads/"; // Chemin absolu sur le serveur
        $base_url = "https://gardien-des-animaux.fr/messages/uploads/"; // URL publique

        if (!file_exists($upload_folder)) {
            mkdir($upload_folder, 0777, true); // Créer le dossier si nécessaire
        }

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

// Gérer les actions spécifiques selon le `data_type`
if ($data_type == "change_profile_image") {
    if ($destination != "") {
        $id = $_SESSION['userid'];
        $query = "UPDATE users SET image = :image WHERE userid = :userid LIMIT 1";
        $DB->write($query, ['image' => $public_url, 'userid' => $id]);
    }
} elseif ($data_type == "delete_profile_image") {
    if (isset($_SESSION['userid'])) {
        $id = $_SESSION['userid'];
        $query = "SELECT image FROM users WHERE userid = :userid LIMIT 1";
        $result = $DB->read($query, ['userid' => $id]);

        if ($result && isset($result[0]->image)) {
            $imagePath = str_replace("https://gardien-des-animaux.fr/messages/uploads/", __DIR__ . "/uploads/", $result[0]->image);

            if (file_exists($imagePath)) {
                unlink($imagePath); // Supprimer le fichier
            }

            $query = "UPDATE users SET image = NULL WHERE userid = :userid LIMIT 1";
            $DB->write($query, ['userid' => $id]);

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
} elseif ($data_type == "send_image") {
    $arr['userid'] = $_POST['userid'] ?? null;

    if ($arr['userid']) {
        $arr['userid'] = addslashes($_POST['userid']);
    }

    $arr['message'] = "";
    $arr['date'] = date("Y-m-d H:i:s");
    $arr['sender'] = $_SESSION['userid'];
    $arr['msgid'] = get_random_string_max(60);
    $arr['file'] = $public_url;

    $arr2['sender'] = $_SESSION['userid'];
    $arr2['receiver'] = $arr['userid'];

    $sql = "SELECT * FROM messages WHERE (sender = :sender AND receiver = :receiver) OR (receiver = :sender AND sender = :receiver) LIMIT 1";
    $result2 = $DB->read($sql, $arr2);

    if (is_array($result2)) {
        $arr['msgid'] = $result2[0]->msgid;
    }

    $query = "INSERT INTO messages (sender, receiver, message, date, msgid, files) VALUES (:sender, :userid, :message, :date, :msgid, :file)";
    $DB->write($query, $arr);
}

function get_random_string_max($length) {
    $array = array_merge(range(0, 9), range('a', 'z'), range('A', 'Z'));
    $text = "";
    $length = rand(4, $length);
    for ($i = 0; $i < $length; $i++) {
        $random = rand(0, count($array) - 1);
        $text .= $array[$random];
    }
    return $text;
}
