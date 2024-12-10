<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nom_animal'])) {
    $noms_animaux = $_POST['nom_animal'];
    $photos_animaux = $_FILES['photo_animal'];

    for ($i = 0; $i < count($noms_animaux); $i++) {
        $nom_animal = $noms_animaux[$i];
        
        if ($photos_animaux['error'][$i] === UPLOAD_ERR_OK) {
            $photo_tmp_name = $photos_animaux['tmp_name'][$i];
            $photo_content = file_get_contents($photo_tmp_name);

            $sql = "INSERT INTO Animal (id_utilisateur, prenom_animal, url_photo) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("iss", $user_id, $nom_animal, $photo_content);
            $stmt->execute();
        }
    }

    $_SESSION['message'] = "Les animaux ont été ajoutés avec succès.";
} else {
    $_SESSION['message'] = "Erreur lors de l'ajout des animaux.";
}

header("Location: profil_public.php");
exit();
?>
