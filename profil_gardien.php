<?php 
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include 'config.php';

$user_id = $_SESSION['user_id'];

$message = isset($_SESSION['message']) ? $_SESSION['message'] : '';
unset($_SESSION['message']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    error_log("POST disponibilites: " . $_POST['disponibilites']);
    
    $type_animal = $_POST['type_animal'];
    $nombre_animal = $_POST['nombre_animal'];
    $budget_min = $_POST['budget_min'];
    $budget_max = $_POST['budget_max'];

// Ensure budget_min and budget_max are not negative
if ($budget_min < 0 || $budget_max < 0) {
    $_SESSION['message'] = 'Les budgets minimum et maximum ne peuvent pas être négatifs.';
    header('Location: profil_gardien.php');
    exit();
}

// Ensure budget_min is not greater than budget_max
if ($budget_min > $budget_max) {
    $_SESSION['message'] = 'Le budget minimum ne peut pas être supérieur au budget maximum.';
    header('Location: profil_gardien.php');
    exit();
}

    $service = $_POST['service'];
    $disponibilites = $_POST['disponibilites']; 

    // Validate service to be either 'garde' or 'promenade'
    if ($service !== 'garde' && $service !== 'promenade') {
        $_SESSION['message'] = 'Le type de service doit être soit garde, soit promenade.';
        header('Location: profil_gardien.php');
        exit();
    }

    if (empty($disponibilites)) {
        $_SESSION['message'] = 'Veuillez sélectionner vos disponibilités.';
        header('Location: profil_gardien.php');
        exit();
    } else {
        $sql_update = "UPDATE creation_compte SET type_animal = ?, nombre_animal = ?, budget_min = ?, budget_max = ?, service = ?, disponibilites = ? WHERE id = ?";
        $stmt_update = $conn->prepare($sql_update);
        $stmt_update->bind_param("siddssi", $type_animal, $nombre_animal, $budget_min, $budget_max, $service, $disponibilites, $user_id);

        if ($stmt_update->execute()) {
            $_SESSION['message'] = 'Modifications enregistrées avec succès !';
            header('Location: profil_gardien.php');
            exit();
        } else {
            $_SESSION['message'] = 'Erreur lors de l\'enregistrement des modifications.';
            header('Location: profil_gardien.php');
            exit();
        }

        $stmt_update->close();
    }
}

$sql = "SELECT nom_utilisateur, nom, prenom, mail, numero_telephone, adresse, ville, profile_picture, type_animal, nombre_animal, budget_min, budget_max, service, disponibilites FROM creation_compte WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($nom_utilisateur, $nom, $prenom, $mail, $numero_telephone, $adresse, $ville, $profile_picture, $type_animal, $nombre_animal, $budget_min, $budget_max, $service, $disponibilites);
$stmt->fetch();
$stmt->close();
$conn->close();

$disponibilites_array = explode(',', $disponibilites);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Profil de Gardien - Gardien des Animaux</title>
    <link rel="stylesheet" href="styles.css">
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const availabilityButtons = document.querySelectorAll('.btn-availability');
            const hiddenField = document.getElementById('disponibilites');
            let selectedDays = <?= json_encode($disponibilites_array) ?>;
            availabilityButtons.forEach(button => {
                if (selectedDays.includes(button.textContent)) {
                    button.classList.add('selected');
                }
                button.addEventListener('click', function() {
                    const day = this.textContent;
                    if (this.classList.contains('selected')) {
                        this.classList.remove('selected');
                        selectedDays = selectedDays.filter(d => d !== day);
                    } else {
                        this.classList.add('selected');
                        selectedDays.push(day);
                    }
                    hiddenField.value = selectedDays.join(',');
                    console.log("Selected days: " + hiddenField.value); 
                });
            });
            hiddenField.value = selectedDays.join(',');
            console.log("Initial selected days: " + hiddenField.value); 
        });
    </script>
    <script>
        function previewProfileImage(event) {
            const reader = new FileReader();
            const imgElement = document.getElementById('profile-img');

            reader.onload = function() {
                if (imgElement) {
                    imgElement.src = reader.result;
                }
            };
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>
</head>
<body>

<header>
    <div class="header-container">
        <img src="images/logo.png" alt="Logo Gardien des Animaux">
        <h1 class="header-slogan">Un foyer chaleureux même en votre absence</h1>
        <div class="auth-buttons">
            <button class="btn" onclick="window.location.href='logout.php'">Déconnexion</button>
            <button class="btn" onclick="window.location.href='index_connect.php'">Accueil</button>
        </div>
    </div>
</header>

<section class="profile-container">
    
    <?php if ($message): ?>
        <div class="alert-message">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>

    <h2 class="profile-title">Profil du Gardien</h2>
    
    <div class="profile-picture">
        <img id="profile-img" src="display_image_gardien.php" alt="Photo de profil">
    </div>

    <form action="upload_image_gardien.php" method="POST" enctype="multipart/form-data" class="profile-form">
        <input type="file" id="profile-picture-input" name="photo_profil" accept="image/*" style="display: none;" onchange="previewProfileImage(event)">
        <button type="button" class="btn-photo" onclick="document.getElementById('profile-picture-input').click();">Changer la photo de profil</button>
        <button type="submit" class="btn-photo">Enregistrer la nouvelle photo</button>
    </form>

    <form action="profil_gardien.php" method="POST">
        <div class="profile-details">
            <div class="profile-item">
                <label>Nom d'utilisateur :</label>
                <span class="profile-value"><?= htmlspecialchars($nom_utilisateur) ?></span>
            </div>
            <div class="profile-item">
                <label>Nom :</label>
                <span class="profile-value"><?= htmlspecialchars($nom) ?></span>
            </div>
            <div class="profile-item">
                <label>Prénom :</label>
                <span class="profile-value"><?= htmlspecialchars($prenom) ?></span>
            </div>
            <div class="profile-item">
                <label>Adresse mail :</label>
                <span class="profile-value"><?= htmlspecialchars($mail) ?></span>
            </div>
            <div class="profile-item">
                <label>Numéro de téléphone :</label>
                <span class="profile-value"><?= htmlspecialchars($numero_telephone) ?></span>
            </div>
            <div class="profile-item">
                <label>Adresse :</label>
                <span class="profile-value"><?= htmlspecialchars($adresse) ?></span>
            </div>
            <div class="profile-item">
                <label>Ville :</label>
                <span class="profile-value"><?= htmlspecialchars($ville) ?></span>
            </div>

            <div class="profile-item">
                <label for="type_animal">Type d'animal :</label>
                <input type="text" id="type_animal" name="type_animal" value="<?= htmlspecialchars($type_animal) ?>" required>
            </div>
            <div class="profile-item">
                <label for="nombre_animal">Nombre d'animaux :</label>
                <input type="number" id="nombre_animal" name="nombre_animal" value="<?= htmlspecialchars($nombre_animal) ?>" required>
            </div>
            <div class="profile-item">
                <label for="budget_min">Budget minimum (en €) :</label>
                <input type="number" step="0.01" id="budget_min" name="budget_min" value="<?= htmlspecialchars($budget_min) ?>" required>
            </div>
            <div class="profile-item">
                <label for="budget_max">Budget maximum (en €) :</label>
                <input type="number" step="0.01" id="budget_max" name="budget_max" value="<?= htmlspecialchars($budget_max) ?>" required>
            </div>
            <div class="profile-item">
                <label for="service">Type de service :</label>
                <select id="service" name="service" required>
                    <option value="garde" <?= $service === "garde" ? "selected" : "" ?>>Garde</option>
                    <option value="promenade" <?= $service === "promenade" ? "selected" : "" ?>>Promenade</option>
                </select>
            </div>

            <input type="hidden" name="disponibilites" id="disponibilites" value="">
        </div>

        <button type="submit" class="btn">Enregistrer les modifications</button>
    </form>

    <form method="POST" action="delete_account.php">
        <button class="btn-delete-account" type="submit" name="delete_account">Supprimer mon compte</button>
    </form>
    
    <div class="availability-buttons">
        <h3>Disponibilités :</h3>
        <button class="btn-availability">Lu</button>
        <button class="btn-availability">Ma</button>
        <button class="btn-availability">Me</button>
        <button class="btn-availability">Je</button>
        <button class="btn-availability">Ve</button>
        <button class="btn-availability">Sa</button>
        <button class="btn-availability">Di</button>
    </div>
</section>

<footer>
    <div class="footer-links">
        <div>
            <h4>En savoir plus :</h4>
            <ul>
                <li><a href="securite.php">Sécurité</a></li>
                <li><a href="aide.php">Centre d'aide</a></li>
            </ul>
        </div>
        <div>
            <h4>A propos de nous :</h4>
            <ul>
                <li><a href="confidentialite.php">Politique de confidentialité</a></li>
                <li><a href="contact.php">Nous contacter</a></li>
            </ul>
        </div>
        <div>
            <h4>Conditions Générales :</h4>
            <ul>
                <li><a href="conditions.php">Conditions d'utilisateur et de Service</a></li>
            </ul>
        </div>
    </div>
</footer>

</body>
</html>
