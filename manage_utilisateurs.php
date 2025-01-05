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
        $stmt = $conn->prepare("UPDATE creation_compte SET prenom = ?, nom = ?, nom_utilisateur = ?, mail = ?, numero_telephone = ?, adresse = ?, ville = ?, mot_de_passe = ?, role = ? WHERE id = ?");
        $stmt->bind_param("ssssssssii", $prenom, $nom, $nom_utilisateur, $mail, $numero_telephone, $adresse, $ville, $mot_de_passe, $role, $id);
    } else {
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
    <style>
        /* Styles globaux */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            color: #333;
            background: url('images/premierplan.png') no-repeat center center fixed;
            background-size: cover;
        }

        header {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 10;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 20px 40px;
            background: transparent;
        }

        header img {
            height: 80px;
        }

        header .header-slogan {
            font-size: 1.5em;
            color: orange;
            font-weight: bold;
            text-align: center;
            flex: 1;
            text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.8);
        }

        header .auth-buttons .btn {
            background-color: orange;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            font-size: 1em;
            cursor: pointer;
            text-decoration: none;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        header .auth-buttons .btn:hover {
            background-color: #ff7f00;
            transform: translateY(-3px);
        }

        .form-container {
            max-width: 1200px; /* Largeur augmentée */
            margin: 150px auto;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
            overflow: hidden;
        }
        .form-container h2 {
            font-size: 1.8em;
            color: orange;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 1em;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .table-container {
            overflow-x: auto; /* Ajoute un défilement horizontal si nécessaire */
            margin-top: 20px;
        }

        table th, table td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
            white-space: nowrap; /* Empêche le texte de dépasser */
        }

        table th {
            background-color: orange;
            color: white;
        }
        .btn {
            display: inline-block;
            background-color: orange;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            font-size: 1em;
            cursor: pointer;
            text-decoration: none;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .btn:hover {
            background-color: #ff7f00;
            transform: translateY(-3px);
        }

        footer {
            background: rgba(0, 0, 0, 0.85);
            color: #fff;
            padding: 20px;
            margin-top: 50px;
        }

        footer .footer-links {
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
        }

        footer .footer-links h4 {
            color: orange;
            margin-bottom: 10px;
        }

        footer .footer-links ul {
            list-style: none;
            padding: 0;
        }

        footer .footer-links a {
            color: white;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        footer .footer-links a:hover {
            color: orange;
        }
    </style>
</head>
<body>

<header>
    <img src="images/logo.png" alt="Logo Gardien des Animaux">
    <h1 class="header-slogan">Gestion des Utilisateurs</h1>
    <div class="auth-buttons">
        <button class="btn" onclick="window.location.href='admin.php'">Retour au Tableau de Bord</button>
    </div>
</header>

<div class="form-container">
    <h2>Ajouter ou Modifier un Utilisateur</h2>
    <form method="POST" action="">
        <input type="hidden" name="id" id="id">
        <div class="form-group">
            <input type="text" name="prenom" placeholder="Prénom" required>
        </div>
        <div class="form-group">
            <input type="text" name="nom" placeholder="Nom" required>
        </div>
        <div class="form-group">
            <input type="text" name="nom_utilisateur" placeholder="Nom d'utilisateur" required>
        </div>
        <div class="form-group">
            <input type="email" name="mail" placeholder="Email" required>
        </div>
        <div class="form-group">
            <input type="text" name="numero_telephone" placeholder="Téléphone" required>
        </div>
        <div class="form-group">
            <input type="text" name="adresse" placeholder="Adresse" required>
        </div>
        <div class="form-group">
            <input type="text" name="ville" placeholder="Ville" required>
        </div>
        <div class="form-group">
            <input type="password" name="mot_de_passe" placeholder="Mot de passe" required>
        </div>
        <div class="form-group">
            <input type="number" name="role" placeholder="Rôle (1=Admin, 2=User)" required>
        </div>
        <button type="submit" name="save" class="btn">Sauvegarder</button>
    </form>
</div>

<div class="form-container">
    <h2>Liste des Utilisateurs</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Prénom</th>
            <th>Nom</th>
            <th>Nom d'utilisateur</th>
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
                    <a class="btn btn-delete" href="?delete=<?php echo $row['id']; ?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?');">Supprimer</a>
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
