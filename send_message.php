<?php

$servername = "localhost";
$username = "gardien";
$password = "G@rdien-des-chiens";
$dbname = "gardiendb";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars(trim($_POST['name']));
    $email = htmlspecialchars(trim($_POST['email']));
    $message = htmlspecialchars(trim($_POST['message']));
    if (empty($name) || empty($email) || empty($message)) {
        echo "<p>Veuillez remplir tous les champs.</p>";
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<p>Adresse email invalide.</p>";
        exit;
    }
    $stmt = $conn->prepare("INSERT INTO messages (sender, message, date) VALUES (?, ?, NOW())");
    $stmt->bind_param("ss", $email, $message);

    if ($stmt->execute()) {
        echo "<p>Merci, $name ! Votre message a été envoyé avec succès.</p>";
    } else {
        echo "<p>Erreur lors de l'envoi du message : " . $conn->error . "</p>";
    }

    $stmt->close();
}

$conn->close();
?>
