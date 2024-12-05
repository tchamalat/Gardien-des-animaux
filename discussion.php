
<?php
include 'config.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$sender_id = $_SESSION['user_id'];
$receiver_username = $_GET['username'] ?? '';
if (empty($receiver_username)) {
    die("Nom d'utilisateur du gardien manquant.");
}

// Fetch receiver's ID based on username
function getUserIdByUsername($pdo, $username) {
    $query = "SELECT id FROM creation_compte WHERE nom_utilisateur = :username LIMIT 1";
    $stmt = $pdo->prepare($query);
    $stmt->execute([':username' => $username]);
    return $stmt->fetchColumn();
}

$receiver_id = getUserIdByUsername($pdo, $receiver_username);
if (!$receiver_id) {
    die("Gardien introuvable.");
}

// Fetch messages between sender and receiver
$query = "SELECT * FROM messages WHERE (sender_id = :sender AND receiver_id = :receiver) OR (sender_id = :receiver AND receiver_id = :sender) ORDER BY created_at";
$stmt = $pdo->prepare($query);
$stmt->execute([':sender' => $sender_id, ':receiver' => $receiver_id]);
$messages = $stmt->fetchAll();

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <title>Discussion avec <?= htmlspecialchars($receiver_username) ?></title>
</head>
<body>
<header>
    <h1>Discussion</h1>
</header>
<main class="discussion-container">
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
</main>
</body>
</html>
