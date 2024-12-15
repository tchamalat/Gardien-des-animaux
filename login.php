<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start(); 

include 'config.php';

$response = array(); 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['username'], $_POST['password'])) {
        $usernameOrEmail = $_POST['username'];
        $password = $_POST['password'];

        $hashed_password = md5($password);

        // Vérification dans la table Administrateur
        $sql_admin = "SELECT id_admin, email_admin, mot_de_passe_admin, permissions FROM Administrateur WHERE email_admin = ?";
        
        if ($stmt_admin = $conn->prepare($sql_admin)) {
            $stmt_admin->bind_param("s", $usernameOrEmail);
            $stmt_admin->execute();
            $stmt_admin->store_result();
            
            if ($stmt_admin->num_rows > 0) {
                $stmt_admin->bind_result($admin_id, $email_admin, $stored_password, $permissions);
                $stmt_admin->fetch();

                if ($hashed_password == $stored_password) {
                    $_SESSION['admin_id'] = $admin_id;
                    $_SESSION['email_admin'] = $email_admin;
                    $_SESSION['permissions'] = $permissions;

                    $response['status'] = 'success';
                    $response['message'] = 'Connexion réussie en tant qu\'administrateur.';
                    $response['redirect'] = 'admin.php'; // Redirection vers admin.php pour les administrateurs
                } else {
                    $response['status'] = 'error';
                    $response['message'] = 'Mot de passe incorrect pour l\'administrateur.';
                }
                $stmt_admin->close();
            } else {
                // Vérification dans la table creation_compte si ce n'est pas un administrateur
                $sql_user = "SELECT id, nom_utilisateur, mail, mot_de_passe, role FROM creation_compte WHERE nom_utilisateur = ? OR mail = ?";
                
                if ($stmt_user = $conn->prepare($sql_user)) {
                    $stmt_user->bind_param("ss", $usernameOrEmail, $usernameOrEmail);
                    $stmt_user->execute();
                    $stmt_user->store_result();

                    if ($stmt_user->num_rows > 0) {
                        $stmt_user->bind_result($user_id, $nom_utilisateur, $email, $stored_password, $role);
                        $stmt_user->fetch();

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
                        $response['message'] = "Nom d'utilisateur ou adresse e-mail incorrect.";
                    }

                    $stmt_user->close();
                } else {
                    $response['status'] = 'error';
                    $response['message'] = "Erreur de connexion à la base de données.";
                }
            }
        } else {
            $response['status'] = 'error';
            $response['message'] = "Erreur de connexion à la base de données.";
        }
    }
}

header('Content-Type: application/json');
echo json_encode($response);

$conn->close();
?>
