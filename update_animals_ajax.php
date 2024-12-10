<?php
session_start();
include 'config.php';

header('Content-Type: application/json');

// Activer l'affichage des erreurs pour le débogage
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Utilisateur non connecté.']);
    exit();
}

$user_id = $_SESSION['user_id'];

// Vérifier les données reçues
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['nom_animal']) || !isset($_FILES['photo_animal'])) {
        echo json_encode(['success' => false, 'message' => 'Données manquantes.']);
        exit();
    }

    $noms_animaux = $_POST['nom_animal'];
    $photos_animaux = $_FILES['photo_animal'];

    // Afficher le contenu des données reçues pour le débogage
    error_log(print_r($noms_animaux, true));
    error_log(print_r($photos_animaux, true));

    for ($i = 0; $i < count($noms_animaux); $i++) {
        $nom_animal = $noms_animaux[$i];

        if ($photos_animaux['error'][$i] === UPLOAD_ERR_OK) {
            $photo_tmp_name = $photos_animaux['tmp_name'][$i];
            $photo_content = file_get_contents($photo_tmp_name);

            $sql = "INSERT INTO Animal (id_utilisateur, prenom_animal, url_photo) VALUES (?, ?, ?)";
            $stmt = $conn->prepare($sql);
            if (!$stmt) {
                echo json_encode(['success' => false, 'message' => 'Erreur de préparation de la requête : ' . $conn->error]);
                exit();
            }

            $stmt->bind_param("iss", $user_id, $nom_animal, $photo_content);

            if (!$stmt->execute()) {
                echo json_encode(['success' => false, 'message' => 'Erreur SQL : ' . $stmt->error]);
                exit();
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Erreur de téléchargement de la photo.']);
            exit();
        }
    }

    echo json_encode(['success' => true, 'message' => 'Animaux ajoutés avec succès.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Méthode de requête invalide.']);
}
?>
