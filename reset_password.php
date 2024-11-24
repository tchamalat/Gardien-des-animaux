
<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['token'];
    $new_password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);

    // Validate the token and update the password
    require 'config.php';
    $query = $conn->prepare('SELECT * FROM creation_compte WHERE reset_token = ? AND token_expiration > NOW()');
    $query->bind_param('s', $token);
    $query->execute();
    $result = $query->get_result();

    if ($result->num_rows > 0) {
        // Token is valid
        $query = $conn->prepare('UPDATE creation_compte SET password = ?, reset_token = NULL, token_expiration = NULL WHERE reset_token = ?');
        $query->bind_param('ss', $new_password, $token);
        $query->execute();
        echo "Votre mot de passe a été mis à jour.";
    } else {
        echo "Le lien de réinitialisation est invalide ou a expiré.";
    }
} else if (isset($_GET['token'])) {
    $token = $_GET['token'];
} else {
    echo "Aucun token fourni.";
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réinitialiser le mot de passe</title>
</head>
<body>
    <h1>Réinitialiser le mot de passe</h1>
    <form action="reset_password.php" method="POST">
        <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
        <label for="new_password">Nouveau mot de passe :</label>
        <input type="password" name="new_password" id="new_password" required>
        <button type="submit">Mettre à jour</button>
    </form>
</body>
</html>
