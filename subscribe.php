<?php
include 'config.php';
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 1) {
    echo json_encode(['success' => false, 'message' => 'Accès refusé.']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$type_abonnement = $data['type_abonnement'] ?? null;
$duree_abonnement = $data['duree_abonnement'] ?? null;
$id_utilisateur = $_SESSION['user_id'];

if (!$type_abonnement || !$duree_abonnement || !$id_utilisateur) {
    echo json_encode(['success' => false, 'message' => 'Données manquantes.']);
    exit;
}

try {
    // Insert subscription into the database
    $stmt = $conn->prepare("
        INSERT INTO Abonnement (id_utilisateur, type_abo, duree_abo, date_debut_abo, date_fin_abo)
        VALUES (?, ?, ?, NOW(), DATE_ADD(NOW(), INTERVAL ? DAY))
    ");
    $stmt->bind_param("isii", $id_utilisateur, $type_abonnement, $duree_abonnement, $duree_abonnement);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Abonnement enregistré.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Erreur lors de l\'enregistrement de l\'abonnement.']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Erreur serveur : ' . $e->getMessage()]);
}
?>
