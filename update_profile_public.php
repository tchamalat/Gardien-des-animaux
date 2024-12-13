<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include 'config.php';

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $type_animal = $_POST['type_animal'];
    $nombre_animal = $_POST['nombre_animal'];

    $sql_update = "UPDATE creation_compte SET type_animal = ?, nombre_animal = ? WHERE id = ?";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param("sii", $type_animal, $nombre_animal, $user_id);

    if ($stmt_update->execute()) {
        $_SESSION['message'] = "Informations mises à jour avec succès.";
    } else {
        $_SESSION['message'] = "Erreur lors de la mise à jour : " . $stmt_update->error;
    }

    $stmt_update->close();
    $conn->close();

    header("Location: profil_public.php");
    exit();
}
?>
