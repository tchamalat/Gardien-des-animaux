<?php
// Activation des erreurs PHP pour le débogage
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

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

// Fonction pour envoyer un email de confirmation
function envoyerEmailConfirmation($email, $reset_link) {
    // Création d'une instance PHPMailer
    $mail = new PHPMailer(true);

    try {
        // Paramètres du serveur SMTP
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';            
        $mail->SMTPAuth = true;
        $mail->Username = 'dan.bensimon44@gmail.com';  // Adresse email expéditeur
        $mail->Password = 'ltiw cegp hnjh hdup';  // Mot de passe de l'email expéditeur
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Paramètres de l'email
        $mail->setFrom('noreply@gardien-des-animaux.fr', 'Gardien des Animaux');
        $mail->addAddress($email);  // Destinataire

        // Contenu de l'email
        $mail->isHTML(true);
        $mail->Subject = 'Mot de passe à été changer avec succes ';
        $mail->Body = "Bonjour,<br><br>Si vous n'etes pas l'auteur, veuillez cliquer sur le lien suivant :<br><a href='$reset_link'>$reset_link</a><br><br>Cordialement,<br>Votre équipe.";
        
        // Envoi de l'email
        $mail->send();
        return true;  // Retourne vrai si l'email a été envoyé
    } catch (Exception $e) {
        // Si une erreur se produit, renvoyer l'erreur
        return false;  // Retourne faux si l'envoi échoue
    }
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
            $new_password = trim($_POST['password']); // Validation de la saisie utilisateur

            // Vérification de la longueur du mot de passe
            if (strlen($new_password) >= 6) {
                // Mise à jour du mot de passe
                $update_stmt = $conn->prepare("UPDATE users SET password = ? WHERE email = ?");
                $update_stmt->bind_param("ss", $new_password, $email);

                if ($update_stmt->execute()) {
                    echo "<p>Votre mot de passe a été réinitialisé avec succès.</p>";

                    // Envoi de l'email de confirmation
                    if (envoyerEmailConfirmation($email)) {
                        echo "<p>Un email de confirmation a été envoyé.</p>";
                    } else {
                        echo "<p>Erreur lors de l'envoi de l'email de confirmation. Vérifiez la configuration de votre serveur.</p>";
                    }
                } else {
                    echo "<p>Erreur lors de la mise à jour du mot de passe : " . $conn->error . "</p>";
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

        input[type="password"], input[type="submit"] {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #d7a261; /* Beige-orangé */
            border-radius: 5px;
            box-sizing: border-box;
            font-size: 16px;
        }

        input[type="password"] {
            background-color: #fefdfb; /* Beige clair */
        }

        input[type="password"]:focus {
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
        <h2>Réinitialiser votre mot de passe</h2>
        <form method="POST">
            <input type="password" name="password" placeholder="Entrez un nouveau mot de passe" required>
            <input type="submit" value="Réinitialiser le mot de passe">
        </form>
        <a href="https://gardien-des-animaux.fr/messages/signup.php">Créer un compte</a>
        <a href="https://gardien-des-animaux.fr/messages/login.php">Retour à la connexion</a>
    </div>
</body>
</html>
