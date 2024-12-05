<?php
$servername = "localhost";
$username = "gardien";
$password = "G@rdien-des-chiens";
$dbname = "gardiendb";

try {
    $pdo = new mysqli($servername, $username, $password, $dbname);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

// Fonction pour afficher l'historique des messages
function getMessages($pdo, $sender_id, $receiver_id) {
    $query = "
        SELECT * FROM discussion 
        WHERE (sender_id = :sender_id AND receiver_id = :receiver_id)
           OR (sender_id = :receiver_id AND receiver_id = :sender_id)
        ORDER BY timestamp ASC";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['sender_id' => $sender_id, 'receiver_id' => $receiver_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Traitement de l'envoi du message
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sender_id = $_POST['sender_id'];
    $receiver_id = $_POST['receiver_id'];
    $message = $_POST['message'];

    if (!empty($sender_id) && !empty($receiver_id) && !empty($message)) {
        $query = "INSERT INTO discussion (sender_id, receiver_id, message) VALUES (:sender_id, :receiver_id, :message)";
        $stmt = $pdo->prepare($query);
        $stmt->execute([
            'sender_id' => $sender_id,
            'receiver_id' => $receiver_id,
            'message' => $message
        ]);
        echo "Message envoyé avec succès.";
    } else {
        echo "Tous les champs sont requis.";
    }
}

// Affichage des messages entre deux utilisateurs
if (isset($_GET['sender_id']) && isset($_GET['receiver_id'])) {
    $sender_id = $_GET['sender_id'];
    $receiver_id = $_GET['receiver_id'];
    $messages = getMessages($pdo, $sender_id, $receiver_id);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Discussion</title>
</head>
<body>
    <h1>Fenêtre de discussion</h1>

    <!-- Formulaire pour envoyer un message -->
    <form method="POST" action="">
        <input type="hidden" name="sender_id" value="1"> <!-- Remplacez 1 par l'ID de l'utilisateur actuel -->
        <label for="receiver_id">ID du destinataire :</label>
        <input type="number" name="receiver_id" id="receiver_id" required>
        <br>
        <label for="message">Message :</label>
        <textarea name="message" id="message" required></textarea>
        <br>
        <button type="submit">Envoyer</button>
    </form>

    <!-- Affichage des messages -->
    <?php if (isset($messages)): ?>
        <h2>Messages échangés</h2>
        <div>
            <?php foreach ($messages as $msg): ?>
                <p>
                    <strong>
                        <?php echo $msg['sender_id'] === $sender_id ? "Vous" : "Utilisateur " . $msg['sender_id']; ?> :
                    </strong>
                    <?php echo htmlspecialchars($msg['message']); ?>
                    <em>(<?php echo $msg['timestamp']; ?>)</em>
                </p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</body>
</html>
