<?php
//print_r($_POST);
session_start();

$info = (object)[];

// Vérifiez si l'utilisateur est connecté
if (!isset($_SESSION['userid'])) {
    if (isset($DATA_OBJ->data_type) && $DATA_OBJ->data_type != "login" && $DATA_OBJ->data_type == "signup") {
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

    if ($_FILES['file']['error'] == 0 && in_array($_FILES['file']['type'], $allowed)) {
        // Bon type de fichier, traitement du téléchargement
        $upload_dir = $_SERVER['DOCUMENT_ROOT'] . "/messages/uploads/"; // Chemin absolu
        $base_url = "https://gardien-des-animaux.fr/messages/uploads/"; // URL publique

        // Créer le dossier des uploads s'il n'existe pas
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        // Déplacer le fichier téléchargé
        $filename = time() . "_" . basename($_FILES['file']['name']); // Préfixer avec un timestamp pour éviter les doublons
        $destination = $upload_dir . $filename;
        $file_url = $base_url . $filename;

        if (move_uploaded_file($_FILES['file']['tmp_name'], $destination)) {
            $info->message = "Votre image a été téléchargée avec succès.";
            $info->file_url = $file_url;
            $info->data_type = $data_type;
            echo json_encode($info);
        } else {
            $info->error = "Échec du téléchargement de l'image.";
            echo json_encode($info);
            die;
        }
    } else {
        $info->error = "Fichier invalide ou erreur lors du téléchargement.";
        echo json_encode($info);
        die;
    }
}

if ($data_type == "change_profile_image") {
    if ($destination != "") {
        // Sauvegarder dans la base de données
        $id = $_SESSION['userid'];
        $query = "UPDATE users SET image = '$destination' WHERE userid = '$id' LIMIT 1";
        $DB->write($query, []);
    }
} elseif ($data_type == "delete_profile_image") {
    // Suppression de l'image de profil
    if (isset($_SESSION['userid'])) {
        $id = $_SESSION['userid'];

        // Obtenir le chemin actuel de l'image
        $query = "SELECT image FROM users WHERE userid = :userid LIMIT 1";
        $result = $DB->read($query, ['userid' => $id]);

        if ($result && isset($result[0]->image)) {
            $imagePath = $result[0]->image;

            // Supprimer l'image du serveur
            if (file_exists($imagePath)) {
                unlink($imagePath); // Supprimer le fichier image
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
} elseif ($data_type == "send_image") {
    $arr['userid'] = $_POST['userid'] ?? null;

    if (isset($_POST['userid'])) {
        $arr['userid'] = addslashes($_POST['userid']);
    }

    $arr['message'] = "";
    $arr['date'] = date("Y-m-d H:i:s");
    $arr['sender'] = $_SESSION['userid'];
    $arr['msgid'] = get_random_string_max(60);
    $arr['file'] = $file_url ?? ""; // Utilisez l'URL publique pour l'image

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
