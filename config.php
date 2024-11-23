<?php
$servername = "localhost:3308";
$username = "root"; 
$password = ""; 
$dbname = "web";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("La connexion a échoué : " . $conn->connect_error);
}
?>