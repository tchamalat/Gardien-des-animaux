<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $prenom = $_POST['prenom'];
    $nom = $_POST['nom'];
    $nom_utilisateur = $_POST['username'];
    $mail = $_POST['email'];
    if (!preg_match('/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.(com|fr)$/', $mail)) {
        echo "<p style='color: red;'>L'adresse e-mail doit être au format xxx.xxx@xxx.fr ou xxx.xxx@xxx.com.</p>";
        exit();
    }
    $numero_telephone = preg_replace('/\D/', '', $_POST['telephone']); 
    if (!preg_match('/^[0-9]{10}$/', $numero_telephone)) {
        echo "<p style='color: red;'>Le numéro de téléphone doit contenir exactement 10 chiffres.</p>";
        exit();
    }
    $adresse = $_POST['adresse'];
    $ville = $_POST['ville'];
    $mot_de_passe = md5($_POST['password']); 
    $role = $_POST['role'];

    // Vérification de l'unicité de l'email, du numéro de téléphone et du nom d'utilisateur
    $stmt = $conn->prepare("SELECT mail, numero_telephone, nom_utilisateur FROM creation_compte WHERE mail = ? OR REPLACE(REPLACE(REPLACE(numero_telephone, ' ', ''), '-', ''), '.', '') = ? OR nom_utilisateur = ?");
    $stmt->bind_param("sss", $mail, $numero_telephone, $nom_utilisateur);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->bind_result($existing_mail, $existing_phone, $existing_username);
        while ($stmt->fetch()) {
            if ($existing_mail === $mail) {
                echo "<p style='color: red;'>L'adresse e-mail est déjà utilisée.</p>";
                exit();
            }
            if ($existing_phone === $numero_telephone) {
                echo "<p style='color: red;'>Le numéro de téléphone est déjà utilisé.</p>";
                exit();
            }
            if ($existing_username === $nom_utilisateur) {
                echo "<p style='color: red;'>Le nom d'utilisateur est déjà pris.</p>";
                exit();
            }
        }
    }

    // Création du compte si l'email, le numéro de téléphone et le nom d'utilisateur sont uniques
    $stmt = $conn->prepare("INSERT INTO creation_compte (prenom, nom, nom_utilisateur, mail, numero_telephone, adresse, ville, mot_de_passe, role) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssssi", $prenom, $nom, $nom_utilisateur, $mail, $numero_telephone, $adresse, $ville, $mot_de_passe, $role);

    if ($stmt->execute()) {
        echo "success"; // Réponse de succès pour la redirection
    } else {
        echo "<p style='color: red;'>Erreur lors de la création du compte. Veuillez réessayer.</p>";
    }

    $stmt->close();
}
$conn->close();
?>
