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
        $reset_link = "https://gardien-des-animaux.fr/reset_password_Hilan.php?email=" . urlencode($email);

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
    <style>
        /* Styles généraux */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f9f4ed; /* Beige clair */
            color: #d77f29; /* Orange */
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background-color: #fff; /* Blanc */
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 100%;
            max-width: 400px;
        }

        h2 {
            font-size: 24px;
            margin-bottom: 20px;
        }

        input[type="email"], input[type="submit"] {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #d7a261; /* Beige-orangé */
            border-radius: 5px;
            box-sizing: border-box;
            font-size: 16px;
        }

        input[type="email"] {
            background-color: #fefdfb; /* Beige clair */
        }

        input[type="email"]:focus {
            outline: none;
            border-color: #d77f29;
        }

        input[type="submit"] {
            background-color: #d77f29; /* Orange */
            color: white;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        input[type="submit"]:hover {
            background-color: #b86a1e; /* Orange foncé */
        }

        a {
            display: block;
            margin-top: 15px;
            color: #d77f29; /* Orange */
            text-decoration: none;
            font-weight: bold;
            transition: color 0.3s ease;
        }

        a:hover {
            color: #b86a1e; /* Orange foncé */
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Récupérer votre mot de passe</h2>
        <form method="POST">
            <input type="email" name="email" placeholder="Entrez votre adresse email" required>
            <input type="submit" value="Envoyer le lien de réinitialisation">
        </form>
        <a href="https://gardien-des-animaux.fr/messages/signup.php">Créer un compte</a>
        <a href="https://gardien-des-animaux.fr/messages/login.php">Retour à la connexion</a>
    </div>
</body>
</html>

