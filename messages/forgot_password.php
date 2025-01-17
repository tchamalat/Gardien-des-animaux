<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'lib/src/PHPMailer.php';
require 'lib/src/SMTP.php';
require 'lib/src/Exception.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];

    // Connexion à la base de données
    $servername = "localhost";
    $username = "gardien";
    $password = "G@rdien-des-chiens";
    $dbname = "gardiendb";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Erreur de connexion : " . $conn->connect_error);
    }

    // Vérifier si l'email existe dans la base de données
    $query = $conn->prepare('SELECT email FROM users WHERE email = ?');
    $query->bind_param('s', $email);
    $query->execute();
    $result = $query->get_result();

    if ($result->num_rows > 0) {
        // Générer un lien de réinitialisation avec un token
        $token = bin2hex(random_bytes(50));
        $expiry = date('Y-m-d H:i:s', strtotime('+5 hour'));

        // Mettre à jour la table `users` avec le token et son expiration
        $query = $conn->prepare('UPDATE users SET reset_token = ?, token_expiration = ? WHERE email = ?');
        $query->bind_param('sss', $token, $expiry, $email);
        $query->execute();

        $reset_link = "https://gardien-des-animaux.fr/messages/reset_password.php?token=$token";

        // Configuration de PHPMailer
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; 
            $mail->SMTPAuth = true;
            $mail->Username = 'dan.bensimon44@gmail.com'; // Remplacez par votre email
            $mail->Password = 'ltiw cegp hnjh hdup'; // Remplacez par votre mot de passe SMTP
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587; 

            $mail->setFrom('noreply@gardien-des-animaux.fr', 'Gardien des Animaux');
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = "Réinitialisation de mot de passe";
            $mail->Body = "<p>Cliquez sur ce lien pour réinitialiser votre mot de passe :</p> <a href='$reset_link'>$reset_link</a>";

            $mail->send();
            $message = "Un email avec un lien de réinitialisation a été envoyé.";
        } catch (Exception $e) {
            $message = "Erreur : l'email n'a pas pu être envoyé. Erreur: {$mail->ErrorInfo}";
        }
    } else {
        $message = "Aucun compte n'est associé à cette adresse email.";
    }

    $query->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réinitialisation de mot de passe</title>
    <style>
        /* Styles similaires à ceux fournis */
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
            max-width: 400px;
            margin: 0 auto;
        }
        input[type="email"], button {
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #d77f29;
            border-radius: 5px;
            width: 100%;
            font-size: 16px;
        }
        button {
            background-color: #d77f29;
            color: white;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background-color: #b86a1e;
        }
        .message {
            margin: 20px 0;
            padding: 10px;
            border-radius: 5px;
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

        <?php if (!empty($message)): ?>
            <div class="message <?php echo strpos($message, 'Erreur') === false ? 'success' : 'error'; ?>">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <form method="POST">
            <input type="email" name="email" placeholder="Entrez votre adresse email" required>
            <button type="submit">Envoyer</button>
        </form>
    </div>
</body>
</html>
