<?php
include 'config.php';

$query = $_GET['query'] ?? '';
if (!empty($query)) {
    $stmt = $pdo->prepare("SELECT nom_utilisateur FROM creation_compte WHERE nom_utilisateur LIKE :query LIMIT 10");
    $stmt->execute(['query' => "%$query%"]);
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($users);
}
