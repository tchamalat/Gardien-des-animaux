<?php 
session_start(); 

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include 'config.php';

$user_id = $_SESSION['user_id'];

// Activer le débogage
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Gérer l'ajout des animaux
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
            
            if (!$stmt) {
                die("Erreur de préparation de la requête : " . $conn->error);
            }

            $stmt->bind_param("iss", $user_id, $nom_animal, $photo_content);

            if (!$stmt->execute()) {
                die("Erreur d'exécution de la requête : " . $stmt->error);
            }

            $stmt->close();
        }
    }

    $message = "Animaux ajoutés avec succès !";
}

// Gérer la mise à jour des animaux
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_animals'])) {
    $animal_ids = $_POST['animal_id'];
    $noms_animaux = $_POST['nom_animal'];
    $photos_animaux = $_FILES['photo_animal'];

    for ($i = 0; $i < count($animal_ids); $i++) {
        $animal_id = $animal_ids[$i];
        $nom_animal = $noms_animaux[$i];

        if (!empty($photos_animaux['tmp_name'][$i])) {
            $photo_tmp_name = $photos_animaux['tmp_name'][$i];
            $photo_content = file_get_contents($photo_tmp_name);
            $sql = "UPDATE Animal SET prenom_animal = ?, url_photo = ? WHERE id = ? AND id_utilisateur = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssii", $nom_animal, $photo_content, $animal_id, $user_id);
        } else {
            $sql = "UPDATE Animal SET prenom_animal = ? WHERE id = ? AND id_utilisateur = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sii", $nom_animal, $animal_id, $user_id);
        }

        $stmt->execute();
        $stmt->close();
    }

    $message = "Animaux mis à jour avec succès !";
}

// Récupérer les animaux de l'utilisateur
$sql_animaux = "SELECT id_animal, prenom_animal, url_photo FROM Animal WHERE id_utilisateur = ?";
$stmt_animaux = $conn->prepare($sql_animaux);
$stmt_animaux->bind_param("i", $user_id);
$stmt_animaux->execute();
$result_animaux = $stmt_animaux->get_result();
$stmt_animaux->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Profil Public - Gardien des Animaux</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>

<div class="profile-container">
    <h2 class="profile-title">Mes Animaux</h2>

    <?php if (isset($message)): ?>
        <div class="alert-message"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        <input type="hidden" name="update_animals" value="1">
        <?php while ($row = $result_animaux->fetch_assoc()): ?>
            <div class="animal-card">
                <input type="hidden" name="animal_id[]" value="<?php echo $row['id']; ?>">
                <label>Nom de l'animal :</label>
                <input type="text" name="nom_animal[]" value="<?php echo htmlspecialchars($row['prenom_animal']); ?>" required>
                
                <div class="animal-photo">
                    <?php if ($row['url_photo']): ?>
                        <img src="data:image/jpeg;base64,<?php echo base64_encode($row['url_photo']); ?>" alt="Photo de <?php echo htmlspecialchars($row['prenom_animal']); ?>" width="100">
                    <?php else: ?>
                        <p>Aucune photo disponible</p>
                    <?php endif; ?>
                </div>
                
                <label>Nouvelle photo :</label>
                <input type="file" name="photo_animal[]" accept="image/*">
            </div>
        <?php endwhile; ?>
        <button type="submit" class="btn">Mettre à jour les animaux</button>
    </form>
</div>

</body>
</html>
