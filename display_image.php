<?php
include 'config.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']); 
    $stmt = $conn->prepare("SELECT profile_picture FROM creation_compte WHERE id = ?");
    $stmt->bind_param("i", $id);
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
        readfile("images/default_profile.png"); 
    }
} else {
    header("HTTP/1.1 400 Bad Request");
    echo "ID utilisateur manquant.";
}
