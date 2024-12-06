<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start(); 

include 'config.php';

$response = array(); 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['username'], $_POST['password'])) {
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
    } elseif (isset($_POST['reset_email'])) {
        $reset_email = $_POST['reset_email'];

        // Vérifier si l'e-mail existe
        $sql = "SELECT id FROM creation_compte WHERE mail = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("s", $reset_email);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                $stmt->bind_result($user_id);
                $stmt->fetch();

                // Générer un token unique
                $token = bin2hex(random_bytes(50));
                $expiry = date("Y-m-d H:i:s", strtotime("+1 hour"));

                // Mettre à jour la base de données avec le token
                $update_sql = "UPDATE creation_compte SET reset_token = ?, token_expiration = ? WHERE id = ?";
                if ($update_stmt = $conn->prepare($update_sql)) {
                    $update_stmt->bind_param("ssi", $token, $expiry, $user_id);
                    $update_stmt->execute();

                    // Envoyer l'e-mail
                    $reset_link = "http://example.com/reset_password.php?token=" . $token;
                    $subject = "Réinitialisation de votre mot de passe";
                    $message = "Cliquez sur le lien suivant pour réinitialiser votre mot de passe : " . $reset_link;
                    $headers = "From: noreply@example.com";

                    if (mail($reset_email, $subject, $message, $headers)) {
                        $response['status'] = 'success';
                        $response['message'] = "Un e-mail de réinitialisation a été envoyé.";
                    } else {
                        $response['status'] = 'error';
                        $response['message'] = "Échec de l'envoi de l'e-mail.";
                    }
                }
            } else {
                $response['status'] = 'error';
                $response['message'] = "Adresse e-mail introuvable.";
            }

            $stmt->close();
        }
    }
}

header('Content-Type: application/json');
echo json_encode($response);

$conn->close();
?>
