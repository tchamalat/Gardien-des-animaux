<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'lib/src/PHPMailer.php';
require 'lib/src/SMTP.php';
require 'lib/src/Exception.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    require 'config.php';
    $query = $conn->prepare('SELECT * FROM creation_compte WHERE mail = ?');
    $query->bind_param('s', $email);
    $query->execute();
    $result = $query->get_result();

    if ($result->num_rows > 0) {
        $token = bin2hex(random_bytes(50));
        $expiry = date('Y-m-d H:i:s', strtotime('+5 hour'));
        $query = $conn->prepare('UPDATE creation_compte SET reset_token = ?, token_expiration = ? WHERE mail = ?');
        $query->bind_param('sss', $token, $expiry, $email);
        $query->execute();
        $reset_link = "gardien-des-animaux.fr/reset_password.php?token=$token";

        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com'; 
            $mail->SMTPAuth = true;
            $mail->Username = 'dan.bensimon44@gmail.com';
            $mail->Password = 'ltiw cegp hnjh hdup'; 
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
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <div class="header-container">
            <img src="images/logo.png" alt="Logo Gardien des Animaux">
            <h1 class="header-slogan">Un foyer chaleureux même en votre absence</h1>
            <div class="auth-buttons">
                <button class="btn" onclick="window.location.href='index.php'">Accueil</button>
            </div>
        </div>
    </header>

    <div class="form-container">
        <h2>Réinitialisation de mot de passe</h2>
        <p>Veuillez entrer votre adresse email pour recevoir un lien de réinitialisation de mot de passe.</p>

        <!-- Afficher le message après soumission -->
        <?php if (!empty($message)): ?>
            <p class="message"><?php echo htmlspecialchars($message); ?></p>
        <?php endif; ?>

        <form action="forgot_password.php" method="POST">
            <div class="form-group">
                <label for="email">Adresse Email :</label>
                <input type="email" name="email" id="email" required>
            </div>
            <button type="submit" class="btn">Envoyer</button>
        </form>
    </div>

    <footer>
    <div class="footer-links">
        <div>
            <h4>En savoir plus :</h4>
            <ul>
                <li><a href="securite.php">Sécurité</a></li>
                <li><a href="aide.php">Centre d'aide</a></li>
            </ul>
        </div>
        <div>
            <h4>A propos de nous :</h4>
            <ul>
                <li><a href="confidentialite.php">Politique de confidentialité</a></li>
                <li><a href="contact.php">Nous contacter</a></li>
            </ul>
        </div>
        <div>
            <h4>Conditions Générales :</h4>
            <ul>
                <li><a href="conditions.php">Conditions d'utilisateur et de Service</a></li>
            </ul>
        </div>
    </div>
</footer>
</body>
</html>
