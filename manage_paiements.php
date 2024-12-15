<?php
session_start();
include 'config.php';

// Gestion de l'ajout ou de la mise à jour d'un paiement
if (isset($_POST['save'])) {
    $id = $_POST['id'] ?? null;
    $id_reservation = $_POST['id_reservation'];
    $prix = $_POST['prix'];
    $date = $_POST['date'];
    $statut_du_paiement = $_POST['statut_du_paiement'];

    if ($id) {
        // Mise à jour du paiement existant
        $stmt = $conn->prepare("UPDATE Paiement SET id_reservation = ?, prix = ?, date = ?, statut_du_paiement = ? WHERE id_paiement = ?");
        $stmt->bind_param("idssi", $id_reservation, $prix, $date, $statut_du_paiement, $id);
    } else {
        // Ajout d'un nouveau paiement
        $stmt = $conn->prepare("INSERT INTO Paiement (id_reservation, prix, date, statut_du_paiement) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("idss", $id_reservation, $prix, $date, $statut_du_paiement);
    }

    $stmt->execute();
    $stmt->close();
    header("Location: manage_paiements.php");
    exit();
}

// Gestion de la suppression d'un paiement
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM Paiement WHERE id_paiement = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    header("Location: manage_paiements.php");
    exit();
}

// Récupération des paiements
$result = $conn->query("SELECT * FROM Paiement");
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gérer les Paiements</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <div class="header-container">
            <img src="images/logo.png" alt="Logo Gardien des Animaux">
            <h1 class="header-slogan">Gestion des Paiements</h1>
            <div class="auth-buttons">
                <button class="btn" onclick="window.location.href='admin.php'">Retour au Tableau de Bord</button>
            </div>
        </div>
    </header>

    <div class="form-container">
        <h2>Ajouter ou Modifier un Paiement</h2>
        <form method="POST" action="">
            <input type="hidden" name="id" id="id">
            <div class="form-group">
                <input type="number" name="id_reservation" id="id_reservation" placeholder="ID Réservation" required>
            </div>
            <div class="form-group">
                <input type="number" step="0.01" name="prix" id="prix" placeholder="Prix (€)" required>
            </div>
            <div class="form-group">
                <input type="datetime-local" name="date" id="date" placeholder="Date" required>
            </div>
            <div class="form-group">
                <input type="text" name="statut_du_paiement" id="statut_du_paiement" placeholder="Statut du Paiement" required>
            </div>
            <button type="submit" name="save" class="btn">Sauvegarder</button>
        </form>
    </div>

    <div class="form-container">
        <h2>Liste des Paiements</h2>
        <table>
            <tr>
                <th>ID Paiement</th>
                <th>ID Réservation</th>
                <th>Prix (€)</th>
                <th>Date</th>
                <th>Statut du Paiement</th>
                <th>Actions</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['id_paiement']; ?></td>
                    <td><?php echo $row['id_reservation']; ?></td>
                    <td><?php echo $row['prix']; ?> €</td>
                    <td><?php echo $row['date']; ?></td>
                    <td><?php echo htmlspecialchars($row['statut_du_paiement']); ?></td>
                    <td>
                        <button class="btn btn-delete" onclick="if(confirm('Êtes-vous sûr de vouloir supprimer ce paiement ?')) window.location.href='?delete=<?php echo $row['id_paiement']; ?>'">Supprimer</button>
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
