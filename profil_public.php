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
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ajouter_animal'])) {
    if (isset($_POST['nom_animal']) && is_array($_POST['nom_animal'])) {
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
            } else {
                die("Erreur de téléchargement de la photo : " . $photos_animaux['error'][$i]);
            }
        }

        $message = "Animaux ajoutés avec succès !";
    } else {
        $message = "Erreur : Aucun animal à ajouter.";
    }
}

// Gérer la mise à jour des animaux
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_animal'])) {
    $id_animal = $_POST['id_animal'];
    $nom_animal = $_POST['nom_animal'];
    $photo_content = null;

    // Vérifier si une nouvelle photo a été téléchargée
    if (!empty($_FILES['photo_animal']['tmp_name']) && $_FILES['photo_animal']['error'] === UPLOAD_ERR_OK) {
        $photo_tmp_name = $_FILES['photo_animal']['tmp_name'];
        $photo_content = file_get_contents($photo_tmp_name);
    }

    if ($photo_content) {
        $sql_update = "UPDATE Animal SET prenom_animal = ?, url_photo = ? WHERE id_animal = ?";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param("ssi", $nom_animal, $photo_content, $id_animal);
    } else {
        $sql_update = "UPDATE Animal SET prenom_animal = ? WHERE id_animal = ?";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param("si", $nom_animal, $id_animal);
    }

    if ($stmt_update->execute()) {
        $message = "Animal mis à jour avec succès !";
    } else {
        $message = "Erreur lors de la mise à jour : " . $stmt_update->error;
    }

    $stmt_update->close();
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
</head>
<body>

<h3>Mes Animaux</h3>
<div id="animal-list">
    <?php while ($row = $result_animaux->fetch_assoc()): ?>
        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id_animal" value="<?php echo $row['id_animal']; ?>">

            <label>Nom :</label>
            <input type="text" name="nom_animal" value="<?php echo htmlspecialchars($row['prenom_animal']); ?>" required>

            <label>Photo :</label>
            <input type="file" name="photo_animal" accept="image/*">

            <?php if ($row['url_photo']): ?>
                <div>
                    <img src="data:image/jpeg;base64,<?php echo base64_encode($row['url_photo']); ?>" alt="Photo de l'animal">
                </div>
            <?php endif; ?>

            <button type="submit" name="update_animal">Modifier</button>
        </form>
    <?php endwhile; ?>
</div>

</body>
</html>
