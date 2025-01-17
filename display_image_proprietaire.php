<?php
include 'config.php';

if (!isset($_GET['id'])) {
    header("HTTP/1.1 400 Bad Request");
    exit("ID de propriétaire manquant.");
}

$proprietaire_id = intval($_GET['id']);
$sql = "SELECT profile_picture FROM creation_compte WHERE id = ? AND role = 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $proprietaire_id);
$stmt->execute();
$stmt->bind_result($profile_picture);
$stmt->fetch();
$stmt->close();

if ($profile_picture) {
    header("Content-Type: image/jpeg");
    echo $profile_picture;
} else {
    header("Content-Type: image/png");
    readfile("images/default_owner.png"); 
}

$conn->close();
?>
