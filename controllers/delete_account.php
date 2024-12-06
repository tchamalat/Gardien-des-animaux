<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include 'config.php';

$user_id = $_SESSION['user_id'];

$sql = "DELETE FROM creation_compte WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);

if ($stmt->execute()) {
    session_unset();
    session_destroy();
    header("Location: index.php");
    exit();
} else {
    echo "Une erreur est survenue lors de la suppression de votre compte.";
}

$stmt->close();
$conn->close();
?>
