<?php
session_start();
include 'config.php';

// Gestion de l'ajout ou de la mise à jour d'une FAQ
if (isset($_POST['save'])) {
    $id = $_POST['id'] ?? null;
    $question = $_POST['question'];
    $reponse = $_POST['reponse'];
    $id_admin = $_SESSION['admin_id']; // Assurez-vous que l'ID de l'admin est stocké dans la session

    if ($id) {
        // Mise à jour de la FAQ existante
        $stmt = $conn->prepare("UPDATE FAQ SET question = ?, reponse = ?, id_admin = ? WHERE id_faq = ?");
        $stmt->bind_param("ssii", $question, $reponse, $id_admin, $id);
    } else {
        // Ajout d'une nouvelle FAQ
        $stmt = $conn->prepare("INSERT INTO FAQ (question, reponse, id_admin) VALUES (?, ?, ?)");
        $stmt->bind_param("ssi", $question, $reponse, $id_admin);
    }

    $stmt->execute();
    $stmt->close();
    header("Location: manage_faq.php");
    exit();
}

// Gestion de la suppression d'une FAQ
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM FAQ WHERE id_faq = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    header("Location: manage_faq.php");
    exit();
}

// Récupération des entrées de la FAQ
$result = $conn->query("SELECT FAQ.id_faq, FAQ.question, FAQ.reponse, Administrateur.email_admin FROM FAQ JOIN Administrateur ON FAQ.id_admin = Administrateur.id_admin");
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gérer la FAQ</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <div class="header-container">
            <img src="images/logo.png" alt="Logo Gardien des Animaux">
            <h1 class="header-slogan">Gestion de la FAQ</h1>
            <div class="auth-buttons">
                <button class="btn" onclick="window.location.href='admin.php'">Retour au Tableau de Bord</button>
            </div>
        </div>
    </header>

    <div class="form-container">
        <h2>Ajouter ou Modifier une Entrée de FAQ</h2>
        <form method="POST" action="">
            <input type="hidden" name="id" id="id">
            <div class="form-group">
                <input type="text" name="question" id="question" placeholder="Question" required>
            </div>
            <div class="form-group">
                <textarea name="reponse" id="reponse" placeholder="Réponse" required></textarea>
            </div>
            <button type="submit" name="save" class="btn">Sauvegarder</button>
        </form>
    </div>

    <div class="form-container">
        <h2>Liste des Entrées de FAQ</h2>
        <table>
            <tr>
                <th>ID FAQ</th>
                <th>Question</th>
                <th>Réponse</th>
                <th>Créé par</th>
                <th>Actions</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['id_faq']; ?></td>
                    <td><?php echo htmlspecialchars($row['question']); ?></td>
                    <td><?php echo htmlspecialchars($row['reponse']); ?></td>
                    <td><?php echo htmlspecialchars($row['email_admin']); ?></td>
                    <td>
                        <button class="btn btn-delete" onclick="if(confirm('Êtes-vous sûr de vouloir supprimer cette entrée de FAQ ?')) window.location.href='?delete=<?php echo $row['id_faq']; ?>'">Supprimer</button>
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
