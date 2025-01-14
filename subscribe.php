<?php
include 'config.php';
session_start();

// Ensure the user is logged in and is an owner
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 1) {
    echo json_encode(['success' => false, 'message' => 'Accès refusé.']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

// Validate received data
if (!isset($data['type_abonnement'], $data['duree_abonnement'], $_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Données manquantes.']);
    exit;
}

$type_abonnement = $data['type_abonnement'];
$duree_abonnement = intval($data['duree_abonnement']);
$id_utilisateur = intval($_SESSION['user_id']);

// Insert subscription into the database
try {
    $stmt = $conn->prepare("
        INSERT INTO Abonnement (id_utilisateur, type_abo, duree_abo, date_debut_abo, date_fin_abo)
        VALUES (?, ?, ?, NOW(), DATE_ADD(NOW(), INTERVAL ? DAY))
    ");
    $stmt->bind_param("isii", $id_utilisateur, $type_abonnement, $duree_abonnement, $duree_abonnement);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Abonnement enregistré.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Erreur lors de l\'enregistrement.']);
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Erreur serveur : ' . $e->getMessage()]);
}
?>
