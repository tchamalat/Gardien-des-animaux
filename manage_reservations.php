<?php
session_start();
include 'config.php';

// Gestion de l'ajout ou de la mise à jour d'une réservation
if (isset($_POST['save'])) {
    $id = $_POST['id'] ?? null;
    $id_utilisateur = $_POST['id_utilisateur'];
    $date_debut = $_POST['date_debut'];
    $date_fin = $_POST['date_fin'];
    $lieu = $_POST['lieu'];
    $type = $_POST['type'];
    $heure_debut = $_POST['heure_debut'];
    $heure_fin = $_POST['heure_fin'];
    $gardien_id = $_POST['gardien_id'];

    if ($id) {
        $stmt = $conn->prepare("UPDATE reservation SET id_utilisateur = ?, date_debut = ?, date_fin = ?, lieu = ?, type = ?, heure_debut = ?, heure_fin = ?, gardien_id = ? WHERE id_reservation = ?");
        $stmt->bind_param("issssssii", $id_utilisateur, $date_debut, $date_fin, $lieu, $type, $heure_debut, $heure_fin, $gardien_id, $id);
    } else {
        $stmt = $conn->prepare("INSERT INTO reservation (id_utilisateur, date_debut, date_fin, lieu, type, heure_debut, heure_fin, gardien_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("issssssi", $id_utilisateur, $date_debut, $date_fin, $lieu, $type, $heure_debut, $heure_fin, $gardien_id);
    }
    
    $stmt->execute();
    $stmt->close();
    header("Location: manage_reservations.php");
    exit();
}

// Gestion de la suppression d'une réservation
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM reservation WHERE id_reservation = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    header("Location: manage_reservations.php");
    exit();
}

// Récupération des réservations
$result = $conn->query("SELECT * FROM reservation");
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gérer les Réservations</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <div class="header-container">
            <img src="images/logo.png" alt="Logo Gardien des Animaux">
            <h1 class="header-slogan">Gestion des Réservations</h1>
            <div class="auth-buttons">
                <button class="btn" onclick="window.location.href='admin.php'">Retour au Tableau de Bord</button>
            </div>
        </div>
    </header>

    <div class="form-container">
        <h2>Ajouter ou Modifier une Réservation</h2>
        <form method="POST" action="">
            <input type="hidden" name="id" id="id">
            <div class="form-group">
                <input type="number" name="id_utilisateur" id="id_utilisateur" placeholder="ID Utilisateur" required>
            </div>
            <div class="form-group">
                <input type="date" name="date_debut" id="date_debut" placeholder="Date Début" required>
            </div>
            <div class="form-group">
                <input type="date" name="date_fin" id="date_fin" placeholder="Date Fin" required>
            </div>
            <div class="form-group">
                <input type="text" name="lieu" id="lieu" placeholder="Lieu" required>
            </div>
            <div class="form-group">
                <input type="text" name="type" id="type" placeholder="Type de Réservation" required>
            </div>
            <div class="form-group">
                <input type="time" name="heure_debut" id="heure_debut" placeholder="Heure Début" required>
            </div>
            <div class="form-group">
                <input type="time" name="heure_fin" id="heure_fin" placeholder="Heure Fin" required>
            </div>
            <div class="form-group">
                <input type="number" name="gardien_id" id="gardien_id" placeholder="ID Gardien" required>
            </div>
            <button type="submit" name="save" class="btn">Sauvegarder</button>
        </form>
    </div>

    <div class="form-container">
        <h2>Liste des Réservations</h2>
        <table>
            <tr>
                <th>ID Réservation</th>
                <th>ID Utilisateur</th>
                <th>Date Début</th>
                <th>Date Fin</th>
                <th>Lieu</th>
                <th>Type</th>
                <th>Heure Début</th>
                <th>Heure Fin</th>
                <th>ID Gardien</th>
                <th>Actions</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['id_reservation']; ?></td>
                    <td><?php echo $row['id_utilisateur']; ?></td>
                    <td><?php echo $row['date_debut']; ?></td>
                    <td><?php echo $row['date_fin']; ?></td>
                    <td><?php echo htmlspecialchars($row['lieu']); ?></td>
                    <td><?php echo htmlspecialchars($row['type']); ?></td>
                    <td><?php echo $row['heure_debut']; ?></td>
                    <td><?php echo $row['heure_fin']; ?></td>
                    <td><?php echo $row['gardien_id']; ?></td>
                    <td>
                        <a href="?delete=<?php echo $row['id_reservation']; ?>" class="btn auth-buttons" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette réservation ?');">Supprimer</a>
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
