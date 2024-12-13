<?php
session_start();
include 'config.php';

if (!isset($_GET['animal_id'])) {
    header("HTTP/1.1 400 Bad Request");
    exit();
}

$animal_id = intval($_GET['animal_id']);

$sql = "SELECT photo_animal FROM animaux WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $animal_id);
$stmt->execute();
$stmt->bind_result($photo_animal);
$stmt->fetch();
$stmt->close();
$conn->close();

if ($photo_animal) {
    header("Content-Type: image/jpeg");
    echo $photo_animal;
} else {
    header("Content-Type: image/png");
    readfile("images/default_animal.png");
}
?>
