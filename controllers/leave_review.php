<?php
session_start();
include 'config.php'; // Inclut la configuration pour la connexion à la base de données

// Vérifiez si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    // Redirigez vers la page de connexion si l'utilisateur n'est pas connecté
    header("Location: /views/login.html");
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
    <link rel="stylesheet" href="/CSS/styles.css">
    <style>
        .review-container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
            background-color: #ffffff;
        }
        h1, h2 {
            color: #2c3e50;
            text-align: center;
        }
        label {
            font-weight: bold;
            margin-top: 10px;
            color: #555;
        }
        .confirmation-message {
            color: #27ae60;
            font-size: 1.1em;
            text-align: center;
            margin-bottom: 15px;
        }
        .header-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }
        .header-container h1 {
            margin: 0;
        }
        .btn {
            text-decoration: none; /* Retire le soulignement */
        }
    </style>
</head>
<body>
    <header class="header-container">
        <h1>Laisser un Avis</h1>
        <a href="/controllers/index_connect.php" class="btn">Accueil</a>
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
                <li><a href="/views/securite.php">Sécurité</a></li>
                <li><a href="/views/aide.php">Centre d'aide</a></li>
            </ul>
        </div>
        <div>
            <h4>A propos de nous :</h4>
            <ul>
                <li><a href="/views/confidentialite.php">Politique de confidentialité</a></li>
                <li><a href="/views/contact.php">Nous contacter</a></li>
            </ul>
        </div>
        <div>
            <h4>Conditions Générales :</h4>
            <ul>
                <li><a href="/views/conditions.php">Conditions d'utilisateur et de Service</a></li>
            </ul>
        </div>
    </div>
</footer>
</body>
</html>
