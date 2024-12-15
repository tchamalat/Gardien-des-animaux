<?php
session_start();
include 'config.php';

// Gestion de l'ajout d'un abonnement
if (isset($_POST['add'])) {
    $id_utilisateur = $_POST['id_utilisateur'];
    $id_paiement = $_POST['id_paiement'];
    $type_abo = $_POST['type_abo'];
    $duree_abo = $_POST['duree_abo'];
    $date_debut_abo = $_POST['date_debut_abo'];
    $date_fin_abo = $_POST['date_fin_abo'];

    $stmt = $conn->prepare("INSERT INTO Abonnement (id_utilisateur, id_paiement, type_abo, duree_abo, date_debut_abo, date_fin_abo) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("iissss", $id_utilisateur, $id_paiement, $type_abo, $duree_abo, $date_debut_abo, $date_fin_abo);
    $stmt->execute();
    $stmt->close();
    header("Location: manage_abonnements.php");
    exit();
}

// Gestion de la suppression d'un abonnement
if (isset($_GET['delete'])) {
    $id_abo = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM Abonnement WHERE id_abo = ?");
    $stmt->bind_param("i", $id_abo);
    $stmt->execute();
    $stmt->close();
    header("Location: manage_abonnements.php");
    exit();
}

// Récupération des abonnements
$result = $conn->query("SELECT * FROM Abonnement");
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gérer les Abonnements</title>
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
        <h2>Ajouter un Abonnement</h2>
        <form method="POST" action="">
            <div class="form-group">
                <input type="number" name="id_utilisateur" placeholder="ID Utilisateur" required>
            </div>
            <div class="form-group">
                <input type="number" name="id_paiement" placeholder="ID Paiement" required>
            </div>
            <div class="form-group">
                <input type="text" name="type_abo" placeholder="Type d'Abonnement" required>
            </div>
            <div class="form-group">
                <input type="number" name="duree_abo" placeholder="Durée (jours)" required>
            </div>
            <div class="form-group">
                <input type="date" name="date_debut_abo" placeholder="Date Début" required>
            </div>
            <div class="form-group">
                <input type="date" name="date_fin_abo" placeholder="Date Fin" required>
            </div>
            <button type="submit" class="btn" name="add">Ajouter</button>
        </form>
    </div>

    <div class="form-container">
        <h2>Liste des Abonnements</h2>
        <table>
            <tr>
                <th>ID Abonnement</th>
                <th>ID Utilisateur</th>
                <th>ID Paiement</th>
                <th>Type Abonnement</th>
                <th>Durée (jours)</th>
                <th>Date Début</th>
                <th>Date Fin</th>
                <th>Action</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['id_abo']; ?></td>
                    <td><?php echo $row['id_utilisateur']; ?></td>
                    <td><?php echo $row['id_paiement']; ?></td>
                    <td><?php echo htmlspecialchars($row['type_abo']); ?></td>
                    <td><?php echo $row['duree_abo']; ?></td>
                    <td><?php echo $row['date_debut_abo']; ?></td>
                    <td><?php echo $row['date_fin_abo']; ?></td>
                    <td>
                        <a class="btn btn-delete" href="?delete=<?php echo $row['id_abo']; ?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet abonnement ?');">Supprimer</a>
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
