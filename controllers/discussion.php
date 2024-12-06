<?php
include 'config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: /controllers/login.php");
    exit;
}

$sender_id = $_SESSION['user_id'];
$receiver_username = '';
$receiver_id = '';

// Si un formulaire est soumis, récupérer le nom d'utilisateur du gardien
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $receiver_username = trim($_POST['username'] ?? '');
    
    if (!empty($receiver_username)) {
        // Fonction pour obtenir l'ID d'un utilisateur à partir de son nom d'utilisateur
        function getUserIdByUsername($pdo, $username) {
            $query = "SELECT id FROM creation_compte WHERE nom_utilisateur = :username LIMIT 1";
            $stmt = $pdo->prepare($query);
            $stmt->execute([':username' => $username]);
            return $stmt->fetchColumn();
        }

        $receiver_id = getUserIdByUsername($pdo, $receiver_username);
        if (!$receiver_id) {
            $error = "Gardien introuvable.";
        }
    } else {
        $error = "Veuillez renseigner un nom d'utilisateur.";
    }
}

// Si un gardien est sélectionné, récupérer les messages
$messages = [];
if ($receiver_id) {
    $query = "SELECT * FROM messages WHERE (sender_id = :sender AND receiver_id = :receiver) OR (sender_id = :receiver AND receiver_id = :sender) ORDER BY created_at";
    $stmt = $pdo->prepare($query);
    $stmt->execute([':sender' => $sender_id, ':receiver' => $receiver_id]);
    $messages = $stmt->fetchAll();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/CSS/styles.css">
    <title>Discussion</title>
</head>
<body>
<header>
    <h1>Discussion</h1>
</header>
<main class="discussion-container">
    <!-- Formulaire pour renseigner le nom d'utilisateur -->
    <form method="POST" class="username-form">
        <label for="username">Nom d'utilisateur du gardien :</label>
        <input type="text" id="username" name="username" value="<?= htmlspecialchars($receiver_username) ?>" required>
        <button type="submit">Valider</button>
    </form>

    <!-- Afficher un message d'erreur si nécessaire -->
    <?php if (isset($error)): ?>
        <p class="error-message"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <!-- Afficher les messages si un gardien est sélectionné -->
    <?php if ($receiver_id): ?>
        <div class="messages">
            <?php foreach ($messages as $message): ?>
                <div class="<?= $message['sender_id'] == $sender_id ? 'message-sent' : 'message-received' ?>">
                    <p><?= htmlspecialchars($message['content']) ?></p>
                    <span><?= $message['created_at'] ?></span>
                </div>
            <?php endforeach; ?>
        </div>
        <form action="send_message.php" method="POST" class="message-form">
            <input type="hidden" name="receiver_id" value="<?= $receiver_id ?>">
            <textarea name="message" placeholder="Écrivez votre message..." required></textarea>
            <button type="submit">Envoyer</button>
        </form>
    <?php endif; ?>
</main>
</body>
</html>
