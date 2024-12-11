<?php
session_start();
require_once 'config.php'; // Fichier de connexion à la base de données

// Vérifie si l'utilisateur est connecté et a le rôle approprié
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 1) {
    header('Location: login.php');
    exit;
}

// Vérifie si l'ID de la réservation est présent dans l'URL
if (isset($_GET['id'])) {
    $id_reservation = intval($_GET['id']);

    // Préparation de la requête de suppression
    $sql = "DELETE FROM reservation WHERE id_reservation = ? AND id_utilisateur = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ii', $id_reservation, $_SESSION['user_id']);

    if ($stmt->execute()) {
        header('Location: historique.php?message=ReservationSupprimee');
    } else {
        echo "Erreur lors de la suppression.";
    }
} else {
    echo "ID de réservation manquant.";
}
?>
