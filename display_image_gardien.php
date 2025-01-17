<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("HTTP/1.1 403 Forbidden");
    exit();
}

$user_id = $_SESSION['user_id'];
$sql = "SELECT profile_picture FROM creation_compte WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$stmt->bind_result($profile_picture);
$stmt->fetch();
$stmt->close();
$conn->close();
if ($profile_picture) {
    header("Content-Type: image/jpeg"); 
    echo $profile_picture;
} else {
    header("Content-Type: image/png");
    readfile("images/profile-placeholder.png");
}
?>
