<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'C:/xampp/htdocs/projet/lib/src/PHPMailer.php';
require 'C:/xampp/htdocs/projet/lib/src/SMTP.php';
require 'C:/xampp/htdocs/projet/lib/src/Exception.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Récupérer les données du formulaire
    $name = htmlspecialchars(trim($_POST['name']));
    $email = htmlspecialchars(trim($_POST['email']));
    $message = htmlspecialchars(trim($_POST['message']));

    // Vérifier si tous les champs sont remplis
    if (!empty($name) && !empty($email) && !empty($message)) {
        // Vérifier si l'adresse email est valide
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            try {
                // Créer une instance de PHPMailer
                $mail = new PHPMailer(true);

                // Configuration du serveur SMTP
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com'; // Remplacez par votre serveur SMTP
                $mail->SMTPAuth = true;
                $mail->Username = 'dan.bensimon44@gmail.com'; // Remplacez par votre adresse email
                $mail->Password = 'ltiw cegp hnjh hdup'; // Remplacez par votre mot de passe SMTP
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Sécurisation TLS
                $mail->Port = 587; // Port SMTP

                // Destinataires
                $mail->setFrom($email, $name);
                $mail->addAddress('contact@gardien-des-animaux.fr', 'Gardien des Animaux'); // Adresse de réception

                // Contenu de l'email
                $mail->isHTML(true);
                $mail->Subject = "Nouveau message de contact de $name";
                $mail->Body = "
                    <h2>Nouveau message de contact</h2>
                    <p><strong>Nom :</strong> $name</p>
                    <p><strong>Email :</strong> $email</p>
                    <p><strong>Message :</strong></p>
                    <p>$message</p>
                ";
                $mail->AltBody = "Nom : $name\nEmail : $email\nMessage :\n$message";

                // Envoyer l'email
                $mail->send();

                // Succès
                echo "<script>
                        alert('Merci, votre message a été envoyé avec succès !');
                        window.location.href = '/views/contact.php';
                      </script>";
            } catch (Exception $e) {
                // Erreur lors de l'envoi
                echo "<script>
                        alert('Désolé, une erreur est survenue lors de l\'envoi du message. Erreur : {$mail->ErrorInfo}');
                        window.location.href = '/views/contact.php';
                      </script>";
            }
        } else {
            // Adresse email invalide
            echo "<script>
                    alert('Veuillez fournir une adresse email valide.');
                    window.location.href = '/views/contact.php';
                  </script>";
        }
    } else {
        // Champs obligatoires non remplis
        echo "<script>
                alert('Veuillez remplir tous les champs.');
                window.location.href = '/views/contact.php';
              </script>";
    }
} else {
    // Accès non autorisé
    header("Location: /views/contact.php");
    exit();
}
?>
