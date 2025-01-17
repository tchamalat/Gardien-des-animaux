<?php

$servername = "localhost";
$username = "gardien";
$password = "G@rdien-des-chiens";
$dbname = "gardiendb";
$conn = new mysqli($servername, $username, $password, $dbname);

// Vérification de la connexion
if ($conn->connect_error) {
    die("Connexion échouée : " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire
    $name = htmlspecialchars(trim($_POST['name']));
    $email = htmlspecialchars(trim($_POST['email']));
    $message = htmlspecialchars(trim($_POST['message']));

    // Validation des données
    if (empty($name) || empty($email) || empty($message)) {
        echo "<p>Veuillez remplir tous les champs.</p>";
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<p>Adresse email invalide.</p>";
        exit;
    }

    // Insertion dans la table messages
    $stmt = $conn->prepare("INSERT INTO contact (name, email, message, date_sent) VALUES (?, ?, ?, NOW())");
    $stmt->bind_param("sss", $name, $email, $message);


    if ($stmt->execute()) {
        echo "<p>Merci, $name ! Votre message a été envoyé avec succès.</p>";
    } else {
        echo "<p>Erreur lors de l'envoi du message : " . $conn->error . "</p>";
    }

    $stmt->close();
}

$conn->close();
?>

