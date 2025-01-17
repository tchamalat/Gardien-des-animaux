<?php
session_start();

// Informations de connexion à la base de données
$servername = "localhost";
$username = "gardien";
$password = "G@rdien-des-chiens";
$dbname = "gardiendb";

// Connexion à la base de données
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connexion échouée : " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars(trim($_POST['name']));
    $email = htmlspecialchars(trim($_POST['email']));
    $message = htmlspecialchars(trim($_POST['message']));

    if (empty($name) || empty($email) || empty($message)) {
        $_SESSION['confirmation_message'] = "Veuillez remplir tous les champs.";
        header("Location: contact_connect_gardien.php");
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['confirmation_message'] = "Adresse email invalide.";
        header("Location: contact_connect_gardien.php");
        exit;
    }

    $stmt = $conn->prepare("INSERT INTO contact (name, email, message, date_sent) VALUES (?, ?, ?, NOW())");
    $stmt->bind_param("sss", $name, $email, $message);

    if ($stmt->execute()) {
        $_SESSION['confirmation_message'] = "Merci, $name ! Votre message a été envoyé avec succès.";
    } else {
        $_SESSION['confirmation_message'] = "Erreur lors de l'envoi du message. Veuillez réessayer.";
    }

    $stmt->close();
    $conn->close();

    // Rediriger vers la page de contact avec un message de confirmation
    header("Location: contact_connect_gardien.php");
    exit;
}
?>
