<?php
// Connexion à la base de données (exemple)
$servername = "localhost";
$username = "gardien";
$password = "G@rdien-des-chiens";
$dbname = "gardiendb"; // Remplacez par le nom de votre base de données

// Création de la connexion
$conn = new mysqli($servername, $username, $password, $dbname);

// Vérification de la connexion
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'lib/src/PHPMailer.php';
require 'lib/src/SMTP.php';
require 'lib/src/Exception.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    require 'config.php'; // Assurez-vous que le fichier de configuration est correctement inclus

    $query = $conn->prepare('SELECT * FROM users WHERE email = ?');
    $query->bind_param('s', $email);
    $query->execute();
    $result = $query->get_result();

    if ($result->num_rows > 0) {
        $reset_link = "https://gardien-des-animaux.fr/reset_password.php?email=" . urlencode($email);

        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; 
            $mail->SMTPAuth = true;
            $mail->Username = 'dan.bensimon44@gmail.com'; // Remplacez par un email valide
            $mail->Password = 'ltiw cegp hnjh hdup'; // Remplacez par un mot de passe d'application sécurisé
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;
            
            $mail->setFrom('noreply@gardien-des-animaux.fr', 'Gardien des Animaux');
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = "Réinitialisation de mot de passe";
            $mail->Body = "Cliquez sur ce lien pour réinitialiser votre mot de passe : <a href='$reset_link'>$reset_link</a>";

            $mail->send();
            $message = "Un email avec un lien de réinitialisation a été envoyé.";
        } catch (Exception $e) {
            $message = "Erreur : l'email n'a pas pu être envoyé. Mailer Error: {$mail->ErrorInfo}";
        }
    } else {
        $message = "Aucun compte n'est associé à cette adresse email.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mot de passe oublié - Gardien des Animaux</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f1e1;
            color: #d77f29;
            padding: 50px;
        }
        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 15px rgba(0,0,0,0.1);
            text-align: center;
        }
        input[type="email"], .btn {
            padding: 10px;
            margin: 10px;
            border: 1px solid #d77f29;
            border-radius: 5px;
            width: 80%;
        }
        .btn {
            background-color: #d77f29;
            color: white;
            cursor: pointer;
            width: 85%;
        }
        .btn:hover {
            background-color: #b86a1e;
        }
        .message {
            margin-bottom: 20px;
            padding: 10px;
            border-radius: 5px;
            font-size: 1em;
        }
        .message.success {
            background-color: #d4edda;
            color: #155724;
        }
        .message.error {
            background-color: #f8d7da;
            color: #721c24;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Réinitialisation de mot de passe</h2>
        <p>Veuillez entrer votre adresse email pour recevoir un lien de réinitialisation de mot de passe.</p>

        <!-- Message -->
        <?php if (!empty($message)): ?>
            <p class="message <?php echo strpos($message, 'Erreur') === false ? 'success' : 'error'; ?>">
                <?php echo htmlspecialchars($message); ?>
            </p>
        <?php endif; ?>

        <form method="POST">
            <input type="email" name="email" placeholder="Entrez votre adresse email" required><br>
            <button type="submit" class="btn">Envoyer le lien de réinitialisation</button>
        </form>
    </div>
</body>
</html>
