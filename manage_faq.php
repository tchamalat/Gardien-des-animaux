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
    <title>Gérer la FAQ</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 10px;
            text-align: center;
        }
        th {
            background-color: #f4f4f4;
        }
        a {
            text-decoration: none;
            color: red;
        }
        form {
            margin-bottom: 20px;
        }
        input, textarea, button {
            padding: 10px;
            margin: 5px;
            width: 100%;
            box-sizing: border-box;
        }
        textarea {
            height: 100px;
        }
    </style>
</head>
<body>
    <h1>Gestion de la FAQ</h1>
    <a href="admin.php">Retour au Tableau de Bord</a>

    <h2>Ajouter ou Modifier une Entrée de FAQ</h2>
    <form method="POST" action="">
        <input type="hidden" name="id" id="id">
        <input type="text" name="question" id="question" placeholder="Question" required>
        <textarea name="reponse" id="reponse" placeholder="Réponse" required></textarea>
        <button type="submit" name="save">Sauvegarder</button>
    </form>

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
                    <a href="?delete=<?php echo $row['id_faq']; ?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette entrée de FAQ ?');">Supprimer</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>

<?php $conn->close(); ?>
