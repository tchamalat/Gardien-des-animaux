<?php 
// Inclusion de PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'lib/src/PHPMailer.php';
require 'lib/src/SMTP.php';
require 'lib/src/Exception.php';

// Connexion à la base de données
$servername = "localhost";
$username = "gardien";
$password = "G@rdien-des-chiens";
$dbname = "gardiendb";

$conn = new mysqli($servername, $username, $password, $dbname);

// Vérification de la connexion
if ($conn->connect_error) {
    die("Erreur de connexion : " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    
    // Requête préparée pour vérifier l'existence de l'email
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Génération du lien de réinitialisation
        $reset_link = "https://gardien-des-animaux.fr/messages/reset_password.php?email=" . urlencode($email);

        // Envoi du mail via PHPMailer
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';            
            $mail->SMTPAuth = true;
            $mail->Username = 'dan.bensimon44@gmail.com';           
            $mail->Password = 'ltiw cegp hnjh hdup'; 
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('noreply@gardien-des-animaux.fr', 'Gardien des Animaux');            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = 'Réinitialisation de votre mot de passe';
            $mail->Body = "Bonjour,<br><br>Pour réinitialiser votre mot de passe, veuillez cliquer sur le lien suivant :<br><a href='$reset_link'>$reset_link</a><br><br>Cordialement,<br>Votre équipe.";

            $mail->send();
            echo "<p>Un lien pour réinitialiser votre mot de passe a été envoyé à $email.</p>";
        } catch (Exception $e) {
            echo "<p>Erreur lors de l'envoi de l'email : {$mail->ErrorInfo}</p>";
        }
    } else {
        echo "<p>Aucun compte trouvé avec cet email.</p>";
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Récupérer votre mot de passe</title>
    <!-- Ajoutez votre style ici -->
</head>
<body>
    <form method="POST">
        <input type="email" name="email" placeholder="Entrez votre adresse email" required>
        <input type="submit" value="Envoyer le lien de réinitialisation">
    </form>
</body>
</html>
