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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Récupération de l'email du formulaire
    $email = $_POST['email'];
    
    // Vérification de l'existence de l'email dans la base de données
    $sql = "SELECT * FROM users WHERE email='$email'"; // Remplacez 'users' par votre table et 'email' par le champ approprié
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Email trouvé, envoi du lien de réinitialisation
        $reset_link = "https://gardien-des-animaux.fr/messages/reset_password.php?email=" . urlencode($email); // Lien de réinitialisation (vous devrez créer la page 'reset_password.php')

        // Envoi de l'email
        $sto = $email; // Destinataire
        $subject = "Réinitialisation de votre mot de passe"; // Sujet du mail
        $message = "Bonjour, \n\nPour réinitialiser votre mot de passe, veuillez cliquer sur le lien suivant : \n$reset_link\n\nCordialement,\nVotre équipe"; // Message

        $headers = "Content-Type: text/plain; charset=utf-8\r\n";
        $headers .= "From:hatsasse@gmail.com\r\n"; // Email de l'émetteur

        if (mail($sto, $subject, $message, $headers)) {
            echo "<p>Un lien pour réinitialiser votre mot de passe a été envoyé à $email.</p>";
        } else {
            echo "<p>Erreur survenue lors de l'envoi de l'email. L'email n'existe pas.</p>";
        }
    } else {
        echo "<p>Aucun compte trouvé avec cet email.</p>";
    }
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
