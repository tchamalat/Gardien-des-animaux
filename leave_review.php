<?php
session_start();
include 'config.php'; // Inclut la configuration pour la connexion à la base de données

// Vérifiez si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    // Redirigez vers la page de connexion si l'utilisateur n'est pas connecté
    header("Location: login.html");
    exit();
}

// Message de confirmation initialisé comme vide
$confirmationMessage = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupération des données du formulaire
    $user_id = $_SESSION['user_id'];
    $review = $_POST['review'];
    $rating = $_POST['rating'];

    // Préparer et exécuter la requête d'insertion
    $stmt = $conn->prepare("INSERT INTO avis (user_id, review, rating) VALUES (?, ?, ?)");
    $stmt->bind_param("isi", $user_id, $review, $rating);

    if ($stmt->execute()) {
        $confirmationMessage = "Merci pour votre avis ! Il a été enregistré avec succès.";
    } else {
        $confirmationMessage = "Erreur : Impossible d'enregistrer l'avis. Veuillez réessayer.";
    }

    // Fermer la connexion
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laisser un Avis</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header class="header-container">
        <h1>Laisser un Avis</h1>
        <a href="index_connect.php" class="btn">Accueil</a>
    </header>
    <main>
        <div class="review-container">
            <h2>Votre avis compte</h2>
            <?php if ($confirmationMessage): ?>
                <p class="confirmation-message"><?php echo $confirmationMessage; ?></p>
            <?php endif; ?>
            <form action="leave_review.php" method="POST">
                <label for="review">Votre avis :</label><br>
                <textarea id="review" name="review" rows="4" cols="50" required></textarea><br>
                <label for="rating">Note (sur 5) :</label><br>
                <select id="rating" name="rating">
                    <option value="5">5</option>
                    <option value="4">4</option>
                    <option value="3">3</option>
                    <option value="2">2</option>
                    <option value="1">1</option>
                </select><br><br>
                <input type="submit" class="btn" value="Envoyer l'avis">
            </form>
        </div>
    </main>
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
