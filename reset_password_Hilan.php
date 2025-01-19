<?php
// Connexion à la base de données
$servername = "nom_du_serveur";
$username = "votre_utilisateur";
$password = "votre_mot_de_passe";
$dbname = "gardiendb";

$conn = new mysqli($servername, $username, $password, $dbname);

// Vérification de la connexion
if ($conn->connect_error) {
    die("Erreur de connexion : " . $conn->connect_error);
}

// Fonction pour envoyer un email de confirmation
function envoyerEmailConfirmation($destinataire) {
    $subject = "Confirmation de changement de mot de passe";
    $message = "Bonjour,\n\nVotre mot de passe a été modifié avec succès.\n\nMerci,\nL'équipe.";
    
    $headers = "From: votre_email@gmail.com\r\n";
    return mail($destinataire, $subject, $message, $headers);
}

// Vérification de l'email dans l'URL
if (isset($_GET['email']) && filter_var($_GET['email'], FILTER_VALIDATE_EMAIL)) {
    $email = $_GET['email'];

    // Vérification de l'existence de l'email
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $new_password = $_POST['password'];

            // Validation du mot de passe
            if (strlen($new_password) >= 6) {
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

                // Mise à jour du mot de passe
                $update_stmt = $conn->prepare("UPDATE users SET password = ? WHERE email = ?");
                $update_stmt->bind_param("ss", $hashed_password, $email);

                if ($update_stmt->execute()) {
                    echo "<p>Votre mot de passe a été réinitialisé avec succès.</p>";

                    // Envoi de l'email de confirmation
                    if (envoyerEmailConfirmation($email)) {
                        echo "<p>Un email de confirmation a été envoyé.</p>";
                    } else {
                        echo "<p>Erreur lors de l'envoi de l'email de confirmation.</p>";
                    }
                } else {
                    echo "<p>Erreur lors de la mise à jour du mot de passe.</p>";
                }
                $update_stmt->close();
            } else {
                echo "<p>Le mot de passe doit contenir au moins 6 caractères.</p>";
            }
        }
    } else {
        echo "<p>Aucun utilisateur trouvé avec cet email.</p>";
    }
    $stmt->close();
} else {
    echo "<p>URL invalide ou email non fourni.</p>";
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réinitialiser votre mot de passe</title>
    <!-- Ajoutez votre style ici -->
</head>
<body>
    <form method="POST">
        <input type="password" name="password" placeholder="Entrez un nouveau mot de passe" required>
        <input type="submit" value="Réinitialiser le mot de passe">
    </form>
</body>
</html>
