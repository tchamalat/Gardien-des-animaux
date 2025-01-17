<?php
// Inclusion de PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Récupération de l'email du formulaire
    $email = $_POST['email'];

    // Vérification de l'existence de l'email dans la base de données avec requête préparée
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Email trouvé, envoi du lien de réinitialisation
        $reset_link = "https://gardien-des-animaux.fr/messages/reset_password.php?email=" . urlencode($email); // Lien de réinitialisation

        // Utilisation de PHPMailer pour l'envoi de l'email
        $mail = new PHPMailer(true);

        try {
            // Paramètres du serveur SMTP
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'hatsasse@gmail.com'; // Votre adresse Gmail
            $mail->Password = 'motdepasse_app'; // Mot de passe spécifique à l'application Gmail
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            // Destinataire
            $mail->setFrom('hatsasse@gmail.com', 'Gardien des Chiens');
            $mail->addAddress($email);

            // Contenu
            $mail->isHTML(false); // Format texte brut
            $mail->Subject = "Réinitialisation de votre mot de passe";
            $mail->Body = "Bonjour,\n\nPour réinitialiser votre mot de passe, veuillez cliquer sur le lien suivant :\n$reset_link\n\nCordialement,\nVotre équipe";

            // Envoi de l'email
            $mail->send();
            echo "<p>Un lien pour réinitialiser votre mot de passe a été envoyé à $email.</p>";
        } catch (Exception $e) {
            echo "<p>Erreur lors de l'envoi de l'email : {$mail->ErrorInfo}</p>";
        }
    } else {
        echo "<p>Aucun compte trouvé avec cet email.</p>";
    }

    // Fermeture de la connexion
    $stmt->close();
}

$conn->close();
?>
