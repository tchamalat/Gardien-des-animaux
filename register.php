<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $prenom = $_POST['prenom'];
    $nom = $_POST['nom'];
    $nom_utilisateur = $_POST['username'];
    $mail = $_POST['email'];
    $numero_telephone = preg_replace('/\D/', '', $_POST['telephone']); // Supprimer tous les caractères non numériques
    $adresse = $_POST['adresse'];
    $ville = $_POST['ville'];
    $mot_de_passe = md5($_POST['password']); // Hachage MD5 du mot de passe
    $role = $_POST['role'];

    // Vérification de l'unicité de l'email et du numéro de téléphone
    $stmt = $conn->prepare("SELECT id FROM creation_compte WHERE mail = ? OR REPLACE(REPLACE(REPLACE(numero_telephone, ' ', ''), '-', ''), '.', '') = ?");
    $stmt->bind_param("ss", $mail, $numero_telephone);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Message d'erreur si l'email ou le numéro de téléphone existe déjà
        echo "<p style='color: red;'>L'adresse e-mail ou le numéro de téléphone est déjà utilisé.</p>";
    } else {
        // Création du compte si l'email et le numéro de téléphone sont uniques
        $stmt = $conn->prepare("INSERT INTO creation_compte (prenom, nom, nom_utilisateur, mail, numero_telephone, adresse, ville, mot_de_passe, role) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssssi", $prenom, $nom, $nom_utilisateur, $mail, $numero_telephone, $adresse, $ville, $mot_de_passe, $role);

        if ($stmt->execute()) {
            echo "success"; // Réponse de succès pour la redirection
        } else {
            echo "<p style='color: red;'>Erreur lors de la création du compte. Veuillez réessayer.</p>";
        }
    }
    $stmt->close();
}
$conn->close();
?>
