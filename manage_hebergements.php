<?php
session_start();
include 'config.php';

// Gestion de l'ajout ou de la mise à jour d'un hébergement
if (isset($_POST['save'])) {
    $id = $_POST['id'] ?? null;
    $id_reservation = $_POST['id_reservation'];
    $id_paiement = $_POST['id_paiement'];
    $id_avis = $_POST['id_avis'] ?? null;
    $date_debut = $_POST['date_debut'];
    $date_fin = $_POST['date_fin'];
    $lieu = $_POST['lieu'];

    if ($id) {
        // Mise à jour de l'hébergement existant
        $stmt = $conn->prepare("UPDATE Hebergement SET id_reservation = ?, id_paiement = ?, id_avis = ?, date_debut = ?, date_fin = ?, lieu = ? WHERE id_hebergement = ?");
        $stmt->bind_param("iiisssi", $id_reservation, $id_paiement, $id_avis, $date_debut, $date_fin, $lieu, $id);
    } else {
        // Ajout d'un nouvel hébergement
        $stmt = $conn->prepare("INSERT INTO Hebergement (id_reservation, id_paiement, id_avis, date_debut, date_fin, lieu) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("iiisss", $id_reservation, $id_paiement, $id_avis, $date_debut, $date_fin, $lieu);
    }

    $stmt->execute();
    $stmt->close();
    header("Location: manage_hebergements.php");
    exit();
}

// Gestion de la suppression d'un hébergement
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM Hebergement WHERE id_hebergement = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    header("Location: manage_hebergements.php");
    exit();
}

// Récupération des hébergements
$result = $conn->query("SELECT * FROM Hebergement");
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gérer les Hébergements</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <div class="header-container">
            <img src="images/logo.png" alt="Logo Gardien des Animaux">
            <h1 class="header-slogan">Gestion des Hébergements</h1>
            <div class="auth-buttons">
                <button class="btn" onclick="window.location.href='admin.php'">Retour au Tableau de Bord</button>
            </div>
        </div>
    </header>

    <div class="form-container">
        <h2>Ajouter ou Modifier un Hébergement</h2>
        <form method="POST" action="">
            <input type="hidden" name="id" id="id">
            <div class="form-group">
                <input type="number" name="id_reservation" id="id_reservation" placeholder="ID Réservation" required>
            </div>
            <div class="form-group">
                <input type="number" name="id_paiement" id="id_paiement" placeholder="ID Paiement" required>
            </div>
            <div class="form-group">
                <input type="number" name="id_avis" id="id_avis" placeholder="ID Avis">
            </div>
            <div class="form-group">
                <input type="date" name="date_debut" id="date_debut" required>
            </div>
            <div class="form-group">
                <input type="date" name="date_fin" id="date_fin" required>
            </div>
            <div class="form-group">
                <input type="text" name="lieu" id="lieu" placeholder="Lieu" required>
            </div>
            <button type="submit" name="save" class="btn">Sauvegarder</button>
        </form>
    </div>

    <div class="form-container">
        <h2>Liste des Hébergements</h2>
        <table>
            <tr>
                <th>ID Hébergement</th>
                <th>ID Réservation</th>
                <th>ID Paiement</th>
                <th>ID Avis</th>
                <th>Date Début</th>
                <th>Date Fin</th>
                <th>Lieu</th>
                <th>Actions</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['id_hebergement']; ?></td>
                    <td><?php echo $row['id_reservation']; ?></td>
                    <td><?php echo $row['id_paiement']; ?></td>
                    <td><?php echo $row['id_avis'] ?? 'N/A'; ?></td>
                    <td><?php echo $row['date_debut']; ?></td>
                    <td><?php echo $row['date_fin']; ?></td>
                    <td><?php echo htmlspecialchars($row['lieu']); ?></td>
                    <td>
                        <button class="btn btn-delete" onclick="if(confirm('Êtes-vous sûr de vouloir supprimer cet hébergement ?')) window.location.href='?delete=<?php echo $row['id_hebergement']; ?>'">Supprimer</button>
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
