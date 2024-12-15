<?php
session_start();
include 'config.php';

// Gestion de l'ajout ou de la mise à jour d'un utilisateur
if (isset($_POST['save'])) {
    $id = $_POST['id'] ?? null;
    $prenom = $_POST['prenom'];
    $nom = $_POST['nom'];
    $nom_utilisateur = $_POST['nom_utilisateur'];
    $mail = $_POST['mail'];
    $numero_telephone = $_POST['numero_telephone'];
    $adresse = $_POST['adresse'];
    $ville = $_POST['ville'];
    $mot_de_passe = $_POST['mot_de_passe'];
    $role = $_POST['role'];

    if ($id) {
        // Mise à jour de l'utilisateur existant
        $stmt = $conn->prepare("UPDATE creation_compte SET prenom = ?, nom = ?, nom_utilisateur = ?, mail = ?, numero_telephone = ?, adresse = ?, ville = ?, mot_de_passe = ?, role = ? WHERE id = ?");
        $stmt->bind_param("ssssssssii", $prenom, $nom, $nom_utilisateur, $mail, $numero_telephone, $adresse, $ville, $mot_de_passe, $role, $id);
    } else {
        // Ajout d'un nouvel utilisateur
        $stmt = $conn->prepare("INSERT INTO creation_compte (prenom, nom, nom_utilisateur, mail, numero_telephone, adresse, ville, mot_de_passe, role) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssssi", $prenom, $nom, $nom_utilisateur, $mail, $numero_telephone, $adresse, $ville, $mot_de_passe, $role);
    }
    
    $stmt->execute();
    $stmt->close();
    header("Location: manage_utilisateurs.php");
    exit();
}

// Gestion de la suppression d'un utilisateur
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM creation_compte WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    header("Location: manage_utilisateurs.php");
    exit();
}

// Récupération des utilisateurs
$result = $conn->query("SELECT * FROM creation_compte");
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gérer les Utilisateurs</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <div class="header-container">
            <img src="images/logo.png" alt="Logo Gardien des Animaux">
            <h1 class="header-slogan">Tableau de Bord Administrateur</h1>
            <div class="auth-buttons">
                <button class="btn" onclick="window.location.href='admin.php'">Retour au Tableau de Bord</button>
            </div>
        </div>
    </header>

    <div class="form-container">
        <h2>Ajouter ou Modifier un Utilisateur</h2>
        <form method="POST" action="">
            <input type="hidden" name="id" id="id">
            <div class="form-group">
                <input type="text" name="prenom" id="prenom" placeholder="Prénom" required>
            </div>
            <div class="form-group">
                <input type="text" name="nom" id="nom" placeholder="Nom" required>
            </div>
            <div class="form-group">
                <input type="text" name="nom_utilisateur" id="nom_utilisateur" placeholder="Nom d'utilisateur" required>
            </div>
            <div class="form-group">
                <input type="email" name="mail" id="mail" placeholder="Email" required>
            </div>
            <div class="form-group">
                <input type="text" name="numero_telephone" id="numero_telephone" placeholder="Téléphone" required>
            </div>
            <div class="form-group">
                <input type="text" name="adresse" id="adresse" placeholder="Adresse" required>
            </div>
            <div class="form-group">
                <input type="text" name="ville" id="ville" placeholder="Ville" required>
            </div>
            <div class="form-group">
                <input type="password" name="mot_de_passe" id="mot_de_passe" placeholder="Mot de passe" required>
            </div>
            <div class="form-group">
                <input type="number" name="role" id="role" placeholder="Rôle (1=Admin, 2=User)" required>
            </div>
            <button type="submit" class="btn" name="save">Sauvegarder</button>
        </form>
    </div>

    <div class="form-container">
        <h2>Liste des Utilisateurs</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Prénom</th>
                <th>Nom</th>
                <th>Nom d'Utilisateur</th>
                <th>Email</th>
                <th>Téléphone</th>
                <th>Adresse</th>
                <th>Ville</th>
                <th>Rôle</th>
                <th>Actions</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo htmlspecialchars($row['prenom']); ?></td>
                    <td><?php echo htmlspecialchars($row['nom']); ?></td>
                    <td><?php echo htmlspecialchars($row['nom_utilisateur']); ?></td>
                    <td><?php echo htmlspecialchars($row['mail']); ?></td>
                    <td><?php echo htmlspecialchars($row['numero_telephone']); ?></td>
                    <td><?php echo htmlspecialchars($row['adresse']); ?></td>
                    <td><?php echo htmlspecialchars($row['ville']); ?></td>
                    <td><?php echo $row['role']; ?></td>
                    <td>
                        <a class="auth-buttons .btn" href="?delete=<?php echo $row['id']; ?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?');">Supprimer</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    </div>

    <footer>
        <div class="footer-links">
            <div>
                <h4>En savoir plus :</h4>
                <ul>
                    <li><a href="securite.php">Sécurité</a></li>
                    <li><a href="aide.php">Centre d'aide</a></li>
                </ul>
            </div>
            <div>
                <h4>A propos de nous :</h4>
                <ul>
                    <li><a href="confidentialite.php">Politique de confidentialité</a></li>
                    <li><a href="contact.php">Nous contacter</a></li>
                </ul>
            </div>
            <div>
                <h4>Conditions Générales :</h4>
                <ul>
                    <li><a href="conditions.php">Conditions d'utilisateur et de Service</a></li>
                </ul>
            </div>
        </div>
    </footer>
</body>
</html>

<?php $conn->close(); ?>
