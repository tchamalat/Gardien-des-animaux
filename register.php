<?php
include 'config.php'; // Inclusion du fichier de configuration pour la base de données

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupération des données du formulaire
    $prenom = $_POST['prenom'] ?? '';
    $nom = $_POST['nom'] ?? '';
    $nom_utilisateur = $_POST['username'] ?? '';
    $mail = $_POST['email'] ?? '';
    $numero_telephone = preg_replace('/\D/', '', $_POST['telephone'] ?? ''); // Supprime les caractères non numériques
    $adresse = $_POST['adresse'] ?? '';
    $ville = $_POST['ville'] ?? '';
    $mot_de_passe = $_POST['password'] ?? '';
    $role = $_POST['role'] ?? '';

    // Validation des données
    $password_pattern = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&.])[A-Za-z\d@$!%*?&.]{8,}$/';
    if (!preg_match($password_pattern, $mot_de_passe)) {
        echo "<p style='color: red;'>Le mot de passe doit contenir au moins 8 caractères, une majuscule, une minuscule, un chiffre et un caractère spécial.</p>";
        exit();
    }
    if (!preg_match('/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.(com|fr)$/', $mail)) {
        echo "<p style='color: red;'>L'adresse e-mail doit être au format xxx@xxx.com ou xxx@xxx.fr.</p>";
        exit();
    }
    if (!preg_match('/^[0-9]{10}$/', $numero_telephone)) {
        echo "<p style='color: red;'>Le numéro de téléphone doit contenir exactement 10 chiffres.</p>";
        exit();
    }
    if (!preg_match('/^[a-zA-Z0-9._-]{3,}$/', $nom_utilisateur)) {
        echo "<p style='color: red;'>Le nom d'utilisateur doit contenir au moins 3 caractères alphanumériques, tirets ou underscores.</p>";
        exit();
    }

    // Hashage du mot de passe
    $mot_de_passe_hache = password_hash($mot_de_passe, PASSWORD_DEFAULT);

    // Vérification des doublons (email, téléphone, nom d'utilisateur)
    $stmt = $conn->prepare("
        SELECT mail, numero_telephone, nom_utilisateur 
        FROM creation_compte 
        WHERE mail = ? OR REPLACE(REPLACE(REPLACE(numero_telephone, ' ', ''), '-', ''), '.', '') = ? OR nom_utilisateur = ?
    ");
    $stmt->bind_param("sss", $mail, $numero_telephone, $nom_utilisateur);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo "<p style='color: red;'>E-mail, numéro de téléphone ou nom d'utilisateur déjà utilisé.</p>";
        $stmt->close();
        exit();
    }
    $stmt->close();

    // Insertion dans la base de données avec une transaction
    $conn->begin_transaction();
    try {
        $stmt = $conn->prepare("
            INSERT INTO creation_compte (prenom, nom, nom_utilisateur, mail, numero_telephone, adresse, ville, mot_de_passe, role) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->bind_param("ssssssssi", $prenom, $nom, $nom_utilisateur, $mail, $numero_telephone, $adresse, $ville, $mot_de_passe_hache, $role);

        if ($stmt->execute()) {
            $conn->commit();
            echo "success"; // Succès
        } else {
            $conn->rollback();
            echo "<p style='color: red;'>Erreur lors de la création du compte. Veuillez réessayer.</p>";
        }
    } catch (Exception $e) {
        $conn->rollback();
        echo "<p style='color: red;'>Erreur : " . $e->getMessage() . "</p>";
    }

    $stmt->close();
    $conn->close();
}
?>
