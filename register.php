<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $prenom = $_POST['prenom'];
    $nom = $_POST['nom'];
    $nom_utilisateur = $_POST['username'];
    $mail = $_POST['email'];
    $errorMessage = '';

    if (!preg_match('/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.(com|fr)$/', $mail)) {
        $errorMessage = "L'adresse e-mail doit être au format xxx.xxx@xxx.fr ou xxx.xxx@xxx.com.";
    }

    $numero_telephone = preg_replace('/\D/', '', $_POST['telephone']); 
    if (!$errorMessage && !preg_match('/^[0-9]{10}$/', $numero_telephone)) {
        $errorMessage = "Le numéro de téléphone doit contenir exactement 10 chiffres.";
    }

    $adresse = $_POST['adresse'];
    $ville = $_POST['ville'];
    $mot_de_passe = $_POST['password'];

    if (!$errorMessage && (strlen($mot_de_passe) < 8 || 
        !preg_match('/[A-Z]/', $mot_de_passe) || 
        !preg_match('/[a-z]/', $mot_de_passe) || 
        !preg_match('/[0-9]/', $mot_de_passe) || 
        !preg_match('/[!@#$%^&*()_+=\-\[\]{};:,.<>?]/', $mot_de_passe))) {
        $errorMessage = "Le mot de passe doit contenir au moins 8 caractères, une lettre majuscule, une lettre minuscule, un chiffre et un caractère spécial.";
    }

    if (!$errorMessage) {
        $mot_de_passe = md5($mot_de_passe); 
        $role = $_POST['role'];

        $stmt = $conn->prepare("SELECT mail, numero_telephone, nom_utilisateur FROM creation_compte WHERE mail = ? OR REPLACE(REPLACE(REPLACE(numero_telephone, ' ', ''), '-', ''), '.', '') = ? OR nom_utilisateur = ?");
        $stmt->bind_param("sss", $mail, $numero_telephone, $nom_utilisateur);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->bind_result($existing_mail, $existing_phone, $existing_username);
            while ($stmt->fetch()) {
                if ($existing_mail === $mail) {
                    $errorMessage = "L'adresse e-mail est déjà utilisée.";
                    break;
                }
                if ($existing_phone === $numero_telephone) {
                    $errorMessage = "Le numéro de téléphone est déjà utilisé.";
                    break;
                }
                if ($existing_username === $nom_utilisateur) {
                    $errorMessage = "Le nom d'utilisateur est déjà pris.";
                    break;
                }
            }
        }

        if (!$errorMessage) {
            $stmt = $conn->prepare("INSERT INTO creation_compte (prenom, nom, nom_utilisateur, mail, numero_telephone, adresse, ville, mot_de_passe, role) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssssssi", $prenom, $nom, $nom_utilisateur, $mail, $numero_telephone, $adresse, $ville, $mot_de_passe, $role);

            if (!$stmt->execute()) {
                $errorMessage = "Erreur lors de la création du compte. Veuillez réessayer.";
            }
        }

        $stmt->close();
    }

    if ($errorMessage) {
        echo "<script>document.addEventListener('DOMContentLoaded', function() { document.getElementById('error-message').innerText = '$errorMessage'; document.getElementById('error-message').style.display = 'block'; });</script>";
    } else {
        echo "<script>document.addEventListener('DOMContentLoaded', function() { alert('Compte créé avec succès !'); window.location.href = 'index.php'; });</script>";
    }
}
$conn->close();
?>
