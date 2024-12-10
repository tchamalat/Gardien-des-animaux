<?php
session_start();
include 'config.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Utilisateur non connecté.']);
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

    // Récupérer la liste mise à jour des animaux
    $sql_animaux = "SELECT prenom_animal, url_photo FROM Animal WHERE id_utilisateur = ?";
    $stmt = $conn->prepare($sql_animaux);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $animals_html = '';
    while ($row = $result->fetch_assoc()) {
        $photo = base64_encode($row['url_photo']);
        $animals_html .= "
            <div class='animal-card'>
                <p><strong>Nom :</strong> " . htmlspecialchars($row['prenom_animal']) . "</p>
                <div class='animal-photo'>
                    <img src='data:image/jpeg;base64,$photo' alt='Photo de " . htmlspecialchars($row['prenom_animal']) . "'>
                </div>
            </div>";
    }

    echo json_encode(['success' => true, 'animals_html' => $animals_html]);
} else {
    echo json_encode(['success' => false, 'message' => 'Erreur lors de l\'ajout des animaux.']);
}
?>
