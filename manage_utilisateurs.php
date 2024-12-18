<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gérer les Utilisateurs</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Styles pour une table élégante */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table th, table td {
            text-align: left;
            padding: 10px;
            border: 1px solid #ddd;
        }

        table th {
            background-color: #f5a623;
            color: white;
        }

        table tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        table tr:hover {
            background-color: #f5f5f5;
        }

        /* Styles pour le bouton supprimer */
        .btn-delete {
            background-color: #e74c3c;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            font-size: 0.9em;
        }

        .btn-delete:hover {
            background-color: #c0392b;
        }

        /* Formulaire amélioré */
        .form-container form {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
        }

        .form-container .form-group {
            flex: 1 1 calc(50% - 10px); /* 50% de la largeur moins les espaces */
            display: flex;
            flex-direction: column;
        }

        .form-container input {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            width: 100%;
            font-size: 1em;
        }

        /* Boutons */
        .form-container button, .auth-buttons .btn {
            padding: 10px 20px;
            font-size: 1em;
            color: white;
            background-color: orange;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .form-container button:hover, .auth-buttons .btn:hover {
            background-color: #ff7f00;
            transform: translateY(-3px);
        }
    </style>
</head>
<body>
    <header>
        <div class="header-container">
            <img src="images/logo.png" alt="Logo Gardien des Animaux">
            <h1 class="header-slogan">Tableau de Bord Administrateur</h1>
            <div class="auth-buttons">
                <button class="btn" onclick="window.location.href='admin.php'">Retour au Tableau de Bord</button>
            </div>
        </div>
    </header>

    <div class="form-container">
        <h2>Ajouter ou Modifier un Utilisateur</h2>
        <form method="POST" action="">
            <input type="hidden" name="id" id="id">
            <div class="form-group">
                <label for="prenom">Prénom</label>
                <input type="text" name="prenom" id="prenom" placeholder="Prénom" required>
            </div>
            <div class="form-group">
                <label for="nom">Nom</label>
                <input type="text" name="nom" id="nom" placeholder="Nom" required>
            </div>
            <div class="form-group">
                <label for="nom_utilisateur">Nom d'utilisateur</label>
                <input type="text" name="nom_utilisateur" id="nom_utilisateur" placeholder="Nom d'utilisateur" required>
            </div>
            <div class="form-group">
                <label for="mail">Email</label>
                <input type="email" name="mail" id="mail" placeholder="Email" required>
            </div>
            <div class="form-group">
                <label for="numero_telephone">Téléphone</label>
                <input type="text" name="numero_telephone" id="numero_telephone" placeholder="Téléphone" required>
            </div>
            <div class="form-group">
                <label for="adresse">Adresse</label>
                <input type="text" name="adresse" id="adresse" placeholder="Adresse" required>
            </div>
            <div class="form-group">
                <label for="ville">Ville</label>
                <input type="text" name="ville" id="ville" placeholder="Ville" required>
            </div>
            <div class="form-group">
                <label for="mot_de_passe">Mot de Passe</label>
                <input type="password" name="mot_de_passe" id="mot_de_passe" placeholder="Mot de passe" required>
            </div>
            <div class="form-group">
                <label for="role">Rôle</label>
                <input type="number" name="role" id="role" placeholder="Rôle (1=Admin, 2=User)" required>
            </div>
            <button type="submit" name="save">Sauvegarder</button>
        </form>
    </div>

    <div class="form-container">
        <h2>Liste des Utilisateurs</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Prénom</th>
                <th>Nom</th>
                <th>Nom d'utilisateur</th>
                <th>Email</th>
                <th>Téléphone</th>
                <th>Adresse</th>
                <th>Ville</th>
                <th>Rôle</th>
                <th>Actions</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo htmlspecialchars($row['prenom']); ?></td>
                    <td><?php echo htmlspecialchars($row['nom']); ?></td>
                    <td><?php echo htmlspecialchars($row['nom_utilisateur']); ?></td>
                    <td><?php echo htmlspecialchars($row['mail']); ?></td>
                    <td><?php echo htmlspecialchars($row['numero_telephone']); ?></td>
                    <td><?php echo htmlspecialchars($row['adresse']); ?></td>
                    <td><?php echo htmlspecialchars($row['ville']); ?></td>
                    <td><?php echo $row['role']; ?></td>
                    <td>
                        <a class="btn-delete" href="?delete=<?php echo $row['id']; ?>" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?');">Supprimer</a>
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
