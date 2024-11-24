
<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];

    // Check if email exists in the database (example logic)
    // Assuming a connection to the database has been made via config.php
    require 'config.php';
    $query = $conn->prepare('SELECT * FROM creation_compte WHERE mail = ?');
    $query->bind_param('s', $email);
    $query->execute();
    $result = $query->get_result();

    if ($result->num_rows > 0) {
        // Generate a unique token
        $token = bin2hex(random_bytes(50));

        // Save the token in the database with an expiry date
        $expiry = date('Y-m-d H:i:s', strtotime('+1 hour'));
        $query = $conn->prepare('UPDATE creation_compte SET reset_token = ?, token_expiration = ? WHERE mail = ?');
        $query->bind_param('sss', $token, $expiry, $email);
        $query->execute();

        // Send reset link via email
        $reset_link = "https://gardien-des-animaux.fr/reset_password.php?token=$token";
        mail($email, "Réinitialisation de mot de passe", "Cliquez sur ce lien pour réinitialiser votre mot de passe : $reset_link");

        echo "Un email avec un lien de réinitialisation a été envoyé.";
    } else {
        echo "Aucun compte n'est associé à cette adresse email.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mot de passe oublié</title>
</head>
<body>
    <h1>Réinitialisation de mot de passe</h1>
    <form action="forgot_password.php" method="POST">
        <label for="email">Adresse Email :</label>
        <input type="email" name="email" id="email" required>
        <button type="submit">Envoyer</button>
    </form>
</body>
</html>
