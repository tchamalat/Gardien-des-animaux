<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'lib/src/PHPMailer.php';
require 'lib/src/SMTP.php';
require 'lib/src/Exception.php';

// Connexion à la base de données
$servername = "localhost";
$username = "gardien";
$password = "G@rdien-des-chiens";
$dbname = "gardiendb";  // Remplacez par le nom de votre base de données

// Création de la connexion
$conn = new mysqli($servername, $username, $password, $dbname);

// Vérification de la connexion
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fonction pour envoyer un email
function envoyerEmailConfirmation($destinataire, $email) {
    $subject = "Confirmation de changement de mot de passe";
    $reset_link = "https://gardien-des-animaux.fr/messages/reset_password.php?email=" . urlencode($email);

    $message = "
        <p>Bonjour,</p>
        <p>Votre mot de passe a été modifié avec succès. Si vous n'êtes pas à l'origine de cette action, veuillez réinitialiser votre mot de passe en cliquant sur le lien suivant :</p>
        <p><a href='$reset_link'>$reset_link</a></p>
        <p>Merci,<br>L'équipe MyChat</p>
    ";

    // Configuration de PHPMailer
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'dan.bensimon44@gmail.com'; // Remplacez par votre email Gmail
        $mail->Password = 'ltiw cegp hnjh hdup'; // Remplacez par votre mot de passe d'application Gmail
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('noreply@gardien-des-animaux.fr', 'Gardien des Animaux');
        $mail->addAddress($destinataire);

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $message;

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Erreur d'envoi d'email : " . $mail->ErrorInfo);
        return false;
    }
}

// Vérification de l'email dans l'URL
if (isset($_GET['email']) && filter_var($_GET['email'], FILTER_VALIDATE_EMAIL)) {
    $email = $_GET['email'];

    // Vérification de l'existence de l'email dans la base de données
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?"); // Utilisation des requêtes préparées
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    // Si l'email existe
    if ($result->num_rows > 0) {
        // Traitement du changement de mot de passe
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $new_password = $_POST['password'];

            // Validation du mot de passe
            if (strlen($new_password) >= 6) { // Critères de sécurité

                // Mise à jour du mot de passe dans la base de données
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT); // Hachage sécurisé
                $update_stmt = $conn->prepare("UPDATE users SET password = ? WHERE email = ?");
                $update_stmt->bind_param("ss", $hashed_password, $email);
                if ($update_stmt->execute()) {
                    echo "<p>Votre mot de passe a été réinitialisé avec succès.</p>";

                    // Envoi de l'email de confirmation
                    if (envoyerEmailConfirmation($email, $email)) {
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
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f1e1; /* beige clair */
            color: #d77f29; /* orange */
            padding: 50px;
        }
        .container {
            background-color: #fff; /* blanc */
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 15px rgba(0,0,0,0.1);
            text-align: center;
        }
        input[type="password"] {
            padding: 10px;
            margin: 10px;
            border: 1px solid #d77f29;
            border-radius: 5px;
            width: 80%;
        }
        input[type="submit"] {
            background-color: #d77f29;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            width: 85%;
        }
        input[type="submit"]:hover {
            background-color: #b86a1e;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Réinitialiser votre mot de passe</h2>
        <form method="POST">
            <input type="password" name="password" placeholder="Entrez un nouveau mot de passe" required><br>
            <input type="submit" value="Réinitialiser le mot de passe">
        </form>
    </div>
</body>
</html>
