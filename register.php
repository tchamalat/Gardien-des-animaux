<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $prenom = $_POST['prenom'] ?? '';
    $nom = $_POST['nom'] ?? '';
    $nom_utilisateur = $_POST['username'] ?? '';
    $mail = $_POST['email'] ?? '';
    $numero_telephone = preg_replace('/\D/', '', $_POST['telephone'] ?? '');
    $adresse = $_POST['adresse'] ?? '';
    $ville = $_POST['ville'] ?? '';
    $mot_de_passe = $_POST['password'] ?? '';
    $role = $_POST['role'] ?? '';
    $password_pattern = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&.])[A-Za-z\d@$!%*?&.]{8,}$/';

    if (!preg_match($password_pattern, $mot_de_passe)) {
        echo "<p style='color: red;'>Le mot de passe doit contenir au moins 8 caractères, une majuscule, une minuscule, un chiffre et un caractère spécial.</p>";
        exit();
    }

    if (!preg_match('/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.(com|fr)$/', $mail)) {
        echo "<p style='color: red;'>L'adresse e-mail doit être valide.</p>";
        exit();
    }

    if (!preg_match('/^[0-9]{10}$/', $numero_telephone)) {
        echo "<p style='color: red;'>Le numéro de téléphone doit contenir exactement 10 chiffres.</p>";
        exit();
    }
    $stmt = $conn->prepare("
        SELECT 
            CASE WHEN mail = ? THEN 'email' END AS email_conflict,
            CASE WHEN REPLACE(REPLACE(REPLACE(numero_telephone, ' ', ''), '-', ''), '.', '') = ? THEN 'phone' END AS phone_conflict,
            CASE WHEN nom_utilisateur = ? THEN 'username' END AS username_conflict
        FROM creation_compte
        WHERE mail = ? OR REPLACE(REPLACE(REPLACE(numero_telephone, ' ', ''), '-', ''), '.', '') = ? OR nom_utilisateur = ?
    ");
    $stmt->bind_param("ssssss", $mail, $numero_telephone, $nom_utilisateur, $mail, $numero_telephone, $nom_utilisateur);
    $stmt->execute();
    $stmt->bind_result($emailConflict, $phoneConflict, $usernameConflict);

    $conflictMessages = [];
    while ($stmt->fetch()) {
        if ($emailConflict) {
            $conflictMessages[] = "E-mail déjà utilisé.";
        }
        if ($phoneConflict) {
            $conflictMessages[] = "Numéro de téléphone déjà utilisé.";
        }
        if ($usernameConflict) {
            $conflictMessages[] = "Nom d'utilisateur déjà utilisé.";
        }
    }
    $stmt->close();

    if (!empty($conflictMessages)) {
        echo "<p style='color: red;'>" . implode("<br>", array_unique($conflictMessages)) . "</p>";
        exit();
    }
    $mot_de_passe_hache = password_hash($mot_de_passe, PASSWORD_DEFAULT);
    $conn->begin_transaction();

    try {
        $stmt = $conn->prepare("
            INSERT INTO creation_compte (prenom, nom, nom_utilisateur, mail, numero_telephone, adresse, ville, mot_de_passe, role) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->bind_param("ssssssssi", $prenom, $nom, $nom_utilisateur, $mail, $numero_telephone, $adresse, $ville, $mot_de_passe_hache, $role);

        if ($stmt->execute()) {
            $conn->commit();
            echo "success"; 
        } else {
            throw new Exception("Erreur lors de l'exécution de la requête.");
        }
    } catch (mysqli_sql_exception $e) {
        $conn->rollback(); 
        if ($e->getCode() === 1062) { 
            if (strpos($e->getMessage(), 'unique_email') !== false) {
                echo "<p style='color: red;'>E-mail déjà utilisé.</p>";
            } elseif (strpos($e->getMessage(), 'unique_phone') !== false) {
                echo "<p style='color: red;'>Numéro de téléphone déjà utilisé.</p>";
            } elseif (strpos($e->getMessage(), 'unique_username') !== false) {
                echo "<p style='color: red;'>Nom d'utilisateur déjà utilisé.</p>";
            } else {
                echo "<p style='color: red;'>Doublon détecté. Veuillez vérifier vos informations.</p>";
            }
        } else {
            echo "<p style='color: red;'>Erreur : " . $e->getMessage() . "</p>";
        }
    }

    $stmt->close();
    $conn->close();
}
?>
