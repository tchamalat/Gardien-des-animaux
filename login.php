<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start(); 

include 'config.php';

$response = array(); 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $hashed_password = md5($password);

    $sql = "SELECT id, nom_utilisateur, mot_de_passe, role FROM creation_compte WHERE nom_utilisateur = ?";
    
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();
        
        if ($stmt->num_rows > 0) {
            $stmt->bind_result($user_id, $nom_utilisateur, $stored_password, $role);
            $stmt->fetch();

            if ($hashed_password == $stored_password) {
                $_SESSION['user_id'] = $user_id;
                $_SESSION['nom_utilisateur'] = $nom_utilisateur;
                $_SESSION['role'] = $role;
                
                $response['status'] = 'success';
                $response['message'] = 'Connexion réussie.';
            } else {
                $response['status'] = 'error';
                $response['message'] = 'Mot de passe incorrect.';
            }
        } else {
            $response['status'] = 'error';
            $response['message'] = "Nom d'utilisateur incorrect.";
        }

        $stmt->close();
    } else {
        $response['status'] = 'error';
        $response['message'] = "Erreur de connexion à la base de données.";
    }
}

header('Content-Type: application/json');
echo json_encode($response);

$conn->close();
?>

