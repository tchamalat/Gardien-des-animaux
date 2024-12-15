<?php
session_start();
include 'config.php';

// Fonction de connexion
function checkAdminLogin($email, $password, $conn) {
    $stmt = $conn->prepare("SELECT * FROM Administrateur WHERE email_admin = ? AND mot_de_passe_admin = ?");
    $stmt->bind_param("ss", $email, $password);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}

// Gestion de la connexion
if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $admin = checkAdminLogin($email, $password, $conn);

    if ($admin) {
        $_SESSION['admin'] = $admin['email_admin'];
    } else {
        $error = "Identifiants incorrects.";
    }
}

// Gestion de la déconnexion
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: admin.php");
    exit();
}

// Protection de la page admin
if (!isset($_SESSION['admin'])) {
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion Admin</title>
</head>
<body>
    <h2>Connexion Administrateur</h2>
    <form method="post" action="">
        <label for="email">Email :</label>
        <input type="email" name="email" required><br>

        <label for="password">Mot de passe :</label>
        <input type="password" name="password" required><br>

        <button type="submit" name="login">Se Connecter</button>
    </form>
    <?php if (isset($error)) echo "<p style='color: red;'>$error</p>"; ?>
</body>
</html>

<?php
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Tableau de Bord Admin</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Bienvenue, <?php echo htmlspecialchars($_SESSION['admin']); ?> !</h1>
    <a href="?logout=1">Déconnexion</a>

    <h2>Gestion des Tables</h2>
    <ul>
        <li><a href="manage_abonnements.php">Gérer les Abonnements</a></li>
        <li><a href="manage_utilisateurs.php">Gérer les Utilisateurs</a></li>
        <li><a href="manage_avis.php">Gérer les Avis</a></li>
        <li><a href="manage_animaux.php">Gérer les Animaux</a></li>
        <li><a href="manage_faq.php">Gérer la FAQ</a></li>
        <li><a href="manage_paiements.php">Gérer les Paiements</a></li>
        <li><a href="manage_hebergements.php">Gérer les Hébergements</a></li>
    </ul>
</body>
</html>