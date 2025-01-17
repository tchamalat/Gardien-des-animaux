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
    
    // Vérification de l'existence de l'email dans la base de données
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // Email trouvé, envoi du lien de réinitialisation
        $reset_link = "https://gardien-des-animaux.fr/messages/reset_password.php?email=" . urlencode($email); // Lien de réinitialisation

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
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = "Réinitialisation de votre mot de passe";
            $mail->Body = "
                <p>Bonjour,</p>
                <p>Pour réinitialiser votre mot de passe, veuillez cliquer sur le lien suivant :</p>
                <p><a href='$reset_link'>$reset_link</a></p>
                <p>Cordialement,</p>
                <p>L'équipe Gardien des Animaux</p>
            ";

            $mail->send();
            echo "<p>Un lien pour réinitialiser votre mot de passe a été envoyé à $email.</p>";
        } catch (Exception $e) {
            echo "<p>Erreur : l'email n'a pas pu être envoyé. Mailer Error: {$mail->ErrorInfo}</p>";
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
        input[type="email"] {
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
        a {
            color: #d77f29;
            text-decoration: none;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Récupérer votre mot de passe</h2>
        <form method="POST">
            <input type="email" name="email" placeholder="Entrez votre adresse email" required><br>
            <input type="submit" value="Envoyer le lien de réinitialisation">
        </form>
        <br>
        <a href="signup.php" style="display: block; text-align: center; text-decoration:none;">
            N'avez-vous pas de compte ? Inscrivez-vous ici.
        </a>

        <a href="login.php" style="display: block; text-align: center; text-decoration:none;">
            Revenez pour la connexion.
        </a>
    </div>
</body>
</html>
