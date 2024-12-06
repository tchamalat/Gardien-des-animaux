<?php
$servername = "localhost";
$username = "gardien";
$password = "G@rdien-des-chiens";
$dbname = "gardiendb";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("La connexion a échoué : " . $conn->connect_error);
}
?>
