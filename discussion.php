<?php
include 'config.php'; 

// Fonction pour obtenir l'ID d'un utilisateur à partir de son nom d'utilisateur
function getUserIdByUsername($pdo, $username) {
    $query = "SELECT id FROM creation_compte WHERE nom_utilisateur = :username LIMIT 1";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['username' => $username]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result ? $result['id'] : null;
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
    $sender_id = $_POST['sender_id']; // ID de l'utilisateur actuel
    $receiver_username = $_POST['receiver_username']; // Nom d'utilisateur du destinataire
    $message = $_POST['message'];

    // Obtenir l'ID du destinataire à partir de son nom d'utilisateur
    $receiver_id = getUserIdByUsername($pdo, $receiver_username);

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
        echo "Tous les champs sont requis ou le destinataire n'existe pas.";
    }
}

// Affichage des messages entre deux utilisateurs
if (isset($_GET['sender_id']) && isset($_GET['receiver_username'])) {
    $sender_id = $_GET['sender_id'];
    $receiver_username = $_GET['receiver_username'];
    
    // Obtenir l'ID du destinataire
    $receiver_id = getUserIdByUsername($pdo, $receiver_username);
    
    if ($receiver_id) {
        $messages = getMessages($pdo, $sender_id, $receiver_id);
    } else {
        echo "Le destinataire n'existe pas.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Discussion</title>
    <style>
        /* Style pour la liste des suggestions */
        .suggestions {
            border: 1px solid #ccc;
            max-height: 150px;
            overflow-y: auto;
            background: #fff;
            position: absolute;
            z-index: 1000;
        }
        .suggestions div {
            padding: 10px;
            cursor: pointer;
        }
        .suggestions div:hover {
            background: #f0f0f0;
        }
    </style>
</head>
<body>
    <h1>Fenêtre de discussion</h1>

    <!-- Formulaire pour envoyer un message -->
    <form method="POST" action="">
        <input type="hidden" name="sender_id" value="1"> <!-- Remplacez 1 par l'ID de l'utilisateur actuel -->
        <label for="receiver_username">Nom d'utilisateur du destinataire :</label>
        <input type="text" name="receiver_username" id="receiver_username" autocomplete="off" required>
        <div id="suggestions" class="suggestions" style="display: none;"></div>
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
                        <?php echo $msg['sender_id'] == $sender_id ? "Vous" : "Utilisateur " . $msg['sender_id']; ?> :
                    </strong>
                    <?php echo htmlspecialchars($msg['message']); ?>
                    <em>(<?php echo $msg['timestamp']; ?>)</em>
                </p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <script>
        // Recherche dynamique avec AJAX
        const input = document.getElementById('receiver_username');
        const suggestionsBox = document.getElementById('suggestions');

        input.addEventListener('input', function() {
            const query = input.value;
            if (query.length >= 2) {
                fetch('search_users.php?query=' + query)
                    .then(response => response.json())
                    .then(data => {
                        suggestionsBox.innerHTML = '';
                        if (data.length > 0) {
                            data.forEach(user => {
                                const div = document.createElement('div');
                                div.textContent = user.nom_utilisateur;
                                div.addEventListener('click', () => {
                                    input.value = user.nom_utilisateur;
                                    suggestionsBox.style.display = 'none';
                                });
                                suggestionsBox.appendChild(div);
                            });
                            suggestionsBox.style.display = 'block';
                        } else {
                            suggestionsBox.style.display = 'none';
                        }
                    });
            } else {
                suggestionsBox.style.display = 'none';
            }
        });

        document.addEventListener('click', (e) => {
            if (!suggestionsBox.contains(e.target) && e.target !== input) {
                suggestionsBox.style.display = 'none';
            }
        });
    </script>
</body>
</html>
