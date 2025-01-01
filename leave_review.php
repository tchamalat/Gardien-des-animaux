<?php
session_start();
include 'config.php'; // Inclut la configuration pour la connexion à la base de données

// Vérifiez si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}

$confirmationMessage = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $review = $_POST['review'];
    $rating = $_POST['rating'];

    $stmt = $conn->prepare("INSERT INTO avis (user_id, review, rating) VALUES (?, ?, ?)");
    $stmt->bind_param("isi", $user_id, $review, $rating);

    if ($stmt->execute()) {
        $confirmationMessage = "Merci pour votre avis ! Il a été enregistré avec succès.";
    } else {
        $confirmationMessage = "Erreur : Impossible d'enregistrer l'avis. Veuillez réessayer.";
    }

    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laisser un Avis</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: url('images/premierplan.png') no-repeat center center fixed;
            background-size: cover;
            color: #333;
            margin: 0;
            padding: 0;
        }

        header {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 10;
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: none;
        }

        header h1 {
            font-size: 1.8em;
            color: orange;
            margin: 0;
        }

        header .btn {
            background-color: orange;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            font-size: 1em;
            cursor: pointer;
            text-decoration: none;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        header .btn:hover {
            background-color: #ff7f00;
            transform: translateY(-3px);
        }

        .review-container {
            max-width: 600px;
            margin: 150px auto 50px auto;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
            text-align: center;
        }

        h2 {
            color: orange;
            margin-bottom: 20px;
        }

        label {
            font-weight: bold;
            color: #555;
            margin-top: 10px;
        }

        textarea {
            width: 100%;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 10px;
            resize: none;
            font-size: 1em;
        }

        select {
            width: 100%;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 10px;
            font-size: 1em;
        }

        .confirmation-message {
            color: #27ae60;
            font-size: 1.1em;
            margin-bottom: 15px;
        }

        .btn-submit {
            background-color: orange;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            font-size: 1em;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.3s ease;
            margin-top: 15px;
        }

        .btn-submit:hover {
            background-color: #ff7f00;
            transform: translateY(-3px);
        }

        footer {
            padding: 20px;
            background: rgba(0, 0, 0, 0.85);
            color: #fff;
            text-align: center;
            margin-top: 50px;
        }

        .footer-links {
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
        }

        .footer-links div {
            margin: 10px;
            text-align: left;
        }

        .footer-links h4 {
            font-size: 1.2em;
            margin-bottom: 10px;
            color: orange;
        }

        .footer-links ul {
            list-style: none;
            padding: 0;
        }

        .footer-links li {
            margin-bottom: 5px;
        }

        .footer-links a {
            color: #fff;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .footer-links a:hover {
            color: orange;
        }
    </style>
</head>
<body>
<header>
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
            <textarea id="review" name="review" rows="4" required></textarea><br>
            <label for="rating">Note (sur 5) :</label><br>
            <select id="rating" name="rating">
                <option value="5">5</option>
                <option value="4">4</option>
                <option value="3">3</option>
                <option value="2">2</option>
                <option value="1">1</option>
            </select><br>
            <button type="submit" class="btn-submit">Envoyer l'avis</button>
        </form>
    </div>
</main>

<footer>
    <div class="footer-links">
        <div>
            <h4>En savoir plus :</h4>
            <ul>
                <li><a href="securite_connect.php">Sécurité</a></li>
                <li><a href="aide_connect.php">Centre d'aide</a></li>
            </ul>
        </div>
        <div>
            <h4>A propos de nous :</h4>
            <ul>
                <li><a href="confidentialite_connect.php">Politique de confidentialité</a></li>
                <li><a href="contact_connect.php">Nous contacter</a></li>
            </ul>
        </div>
        <div>
            <h4>Conditions Générales :</h4>
            <ul>
                <li><a href="conditions_connect.php">Conditions d'utilisateur et de Service</a></li>
            </ul>
        </div>
    </div>
</footer>
</body>
</html>
