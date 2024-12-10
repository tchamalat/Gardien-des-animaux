<?php
session_start();
include 'config.php'; // Inclut le fichier de connexion à la base de données

// Vérifie si l'ID du propriétaire est passé en paramètre
if (!isset($_GET['id'])) {
    echo "ID du propriétaire non spécifié.";
    exit();
}

$proprietaire_id = intval($_GET['id']);

// Récupère les informations du propriétaire depuis la base de données
$sql = "SELECT nom_utilisateur, nom, prenom, mail, profile_picture FROM creation_compte WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $proprietaire_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Aucun utilisateur trouvé avec cet ID.";
    exit();
}

$proprietaire = $result->fetch_assoc();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil du Propriétaire</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .profile-container {
            width: 80%;
            margin: 50px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        .profile-picture img {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
        }
        .profile-item {
            margin: 15px 0;
        }
        .profile-item label {
            font-weight: bold;
        }
        .btn-back {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 15px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            text-decoration: none;
        }
        .btn-back:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<div class="profile-container">
    <h2>Profil du Propriétaire</h2>

    <div class="profile-picture">
        <?php if ($proprietaire['profile_picture']): ?>
            <img src="data:image/jpeg;base64,<?php echo base64_encode($proprietaire['profile_picture']); ?>" alt="Photo de profil">
        <?php else: ?>
            <p>Aucune photo de profil disponible.</p>
        <?php endif; ?>
    </div>

    <div class="profile-details">
        <div class="profile-item">
            <label>Nom d'utilisateur :</label>
            <span><?php echo htmlspecialchars($proprietaire['nom_utilisateur']); ?></span>
        </div>
        <div class="profile-item">
            <label>Nom :</label>
            <span><?php echo htmlspecialchars($proprietaire['nom']); ?></span>
        </div>
        <div class="profile-item">
            <label>Prénom :</label>
            <span><?php echo htmlspecialchars($proprietaire['prenom']); ?></span>
        </div>
        <div class="profile-item">
            <label>Email :</label>
            <span><?php echo htmlspecialchars($proprietaire['mail']); ?></span>
        </div>
    </div>

    <a href="mes_reservations.php" class="btn-back">Retour aux réservations</a>
</div>

</body>
</html>
