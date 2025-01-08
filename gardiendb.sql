-- phpMyAdmin SQL Dump
-- version 5.2.1deb1
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:3306
-- Généré le : ven. 29 nov. 2024 à 12:54
-- Version du serveur : 10.11.6-MariaDB-0+deb12u1
-- Version de PHP : 8.2.24

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `gardiendb`
--

-- --------------------------------------------------------

--
-- Structure de la table `Abonnement`
--

CREATE TABLE `Abonnement` (
  `id_abo` int(11) NOT NULL,
  `id_utilisateur` int(11) NOT NULL,
  `id_paiement` int(11) NOT NULL,
  `type_abo` varchar(50) NOT NULL,
  `duree_abo` int(11) NOT NULL COMMENT 'Durée en jours',
  `date_debut_abo` date NOT NULL,
  `date_fin_abo` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
-- Erreur de lecture des données pour la table gardiendb.Abonnement : #1064 - Erreur de syntaxe près de &#039;FROM `gardiendb`.`Abonnement`&#039; à la ligne 1

-- --------------------------------------------------------

--
-- Structure de la table `Administrateur`
--

CREATE TABLE `Administrateur` (
  `id_admin` int(11) NOT NULL,
  `email_admin` varchar(255) NOT NULL,
  `mot_de_passe_admin` varchar(255) NOT NULL,
  `permissions` text NOT NULL COMMENT 'Liste des permissions ou rôle global'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
-- Erreur de lecture des données pour la table gardiendb.Administrateur : #1064 - Erreur de syntaxe près de &#039;FROM `gardiendb`.`Administrateur`&#039; à la ligne 1

-- --------------------------------------------------------

--
-- Structure de la table `Animal`
--

CREATE TABLE `Animal` (
  `id_animal` int(11) NOT NULL,
  `id_utilisateur` int(11) NOT NULL,
  `type` varchar(100) NOT NULL,
  `url_photo` text DEFAULT NULL,
  `prenom_animal` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
-- Erreur de lecture des données pour la table gardiendb.Animal : #1064 - Erreur de syntaxe près de &#039;FROM `gardiendb`.`Animal`&#039; à la ligne 1

-- --------------------------------------------------------

--
-- Structure de la table `avis`
--

CREATE TABLE `avis` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `review` text NOT NULL,
  `rating` int(11) DEFAULT NULL,
  `date_created` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
-- Erreur de lecture des données pour la table gardiendb.avis : #1064 - Erreur de syntaxe près de &#039;FROM `gardiendb`.`avis`&#039; à la ligne 1

-- --------------------------------------------------------

--
-- Structure de la table `CGU_mentions_legales`
--

CREATE TABLE `CGU_mentions_legales` (
  `id_cgu` int(11) NOT NULL,
  `id_admin` int(11) NOT NULL,
  `contenu` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
-- Erreur de lecture des données pour la table gardiendb.CGU_mentions_legales : #1064 - Erreur de syntaxe près de &#039;FROM `gardiendb`.`CGU_mentions_legales`&#039; à la ligne 1

-- --------------------------------------------------------

--
-- Structure de la table `creation_compte`
--

CREATE TABLE `creation_compte` (
  `id` int(11) NOT NULL,
  `prenom` text NOT NULL,
  `nom` text NOT NULL,
  `nom_utilisateur` text NOT NULL,
  `mail` text NOT NULL,
  `numero_telephone` text NOT NULL,
  `adresse` text NOT NULL,
  `ville` text NOT NULL,
  `mot_de_passe` text NOT NULL,
  `role` int(11) NOT NULL,
  `profile_picture` text DEFAULT NULL,
  `reset_token` varchar(100) DEFAULT NULL,
  `token_expiration` datetime DEFAULT NULL,
  `type_animal` varchar(100) DEFAULT NULL,
  `nombre_animal` int(11) DEFAULT NULL,
  `budget_min` decimal(10,2) DEFAULT NULL,
  `disponibilites` varchar(100) DEFAULT NULL,
  `budget_max` decimal(10,2) DEFAULT NULL,
  `service` text DEFAULT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
-- Erreur de lecture des données pour la table gardiendb.creation_compte : #1064 - Erreur de syntaxe près de &#039;FROM `gardiendb`.`creation_compte`&#039; à la ligne 1

-- --------------------------------------------------------

--
-- Structure de la table `discussion`
--

CREATE TABLE `discussion` (
  `id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `receiver_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `timestamp` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
-- Erreur de lecture des données pour la table gardiendb.discussion : #1064 - Erreur de syntaxe près de &#039;FROM `gardiendb`.`discussion`&#039; à la ligne 1

-- --------------------------------------------------------

--
-- Structure de la table `FAQ`
--

CREATE TABLE `FAQ` (
  `id_faq` int(11) NOT NULL,
  `id_admin` int(11) NOT NULL,
  `question` text NOT NULL,
  `reponse` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
-- Erreur de lecture des données pour la table gardiendb.FAQ : #1064 - Erreur de syntaxe près de &#039;FROM `gardiendb`.`FAQ`&#039; à la ligne 1

-- --------------------------------------------------------

--
-- Structure de la table `Galerie_photo`
--

CREATE TABLE `Galerie_photo` (
  `id_galerie` int(11) NOT NULL,
  `id_utilisateur` int(11) NOT NULL,
  `id_hebergement` int(11) NOT NULL,
  `description` text DEFAULT NULL,
  `nombre_de_photo` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
-- Erreur de lecture des données pour la table gardiendb.Galerie_photo : #1064 - Erreur de syntaxe près de &#039;FROM `gardiendb`.`Galerie_photo`&#039; à la ligne 1

-- --------------------------------------------------------

--
-- Structure de la table `Hebergement`
--

CREATE TABLE `Hebergement` (
  `id_hebergement` int(11) NOT NULL,
  `id_reservation` int(11) NOT NULL,
  `id_paiement` int(11) NOT NULL,
  `id_avis` int(11) DEFAULT NULL,
  `date_debut` date NOT NULL,
  `date_fin` date NOT NULL,
  `lieu` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
-- Erreur de lecture des données pour la table gardiendb.Hebergement : #1064 - Erreur de syntaxe près de &#039;FROM `gardiendb`.`Hebergement`&#039; à la ligne 1

-- --------------------------------------------------------

--
-- Structure de la table `Paiement`
--

CREATE TABLE `Paiement` (
  `id_paiement` int(11) NOT NULL,
  `id_reservation` int(11) NOT NULL,
  `prix` decimal(10,2) NOT NULL,
  `date` datetime NOT NULL,
  `statut_du_paiement` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
-- Erreur de lecture des données pour la table gardiendb.Paiement : #1064 - Erreur de syntaxe près de &#039;FROM `gardiendb`.`Paiement`&#039; à la ligne 1

-- --------------------------------------------------------

--
-- Structure de la table `photo`
--

CREATE TABLE `photo` (
  `id_photo` int(11) NOT NULL,
  `id_utilisateur` int(11) NOT NULL,
  `id_galerie` int(11) NOT NULL,
  `description_photo` text DEFAULT NULL,
  `type` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
-- Erreur de lecture des données pour la table gardiendb.photo : #1064 - Erreur de syntaxe près de &#039;FROM `gardiendb`.`photo`&#039; à la ligne 1

-- --------------------------------------------------------

--
-- Structure de la table `reservation`
--

CREATE TABLE `reservation` (
  `id_reservation` int(11) NOT NULL,
  `id_utilisateur` int(11) NOT NULL,
  `date_debut` date NOT NULL,
  `date_fin` date NOT NULL,
  `lieu` text NOT NULL,
  `type` varchar(255) NOT NULL,
  `heure_debut` time NOT NULL,
  `heure_fin` time NOT NULL,
  `gardien_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
-- Erreur de lecture des données pour la table gardiendb.reservation : #1064 - Erreur de syntaxe près de &#039;FROM `gardiendb`.`reservation`&#039; à la ligne 1

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `Abonnement`
--
ALTER TABLE `Abonnement`
  ADD PRIMARY KEY (`id_abo`),
  ADD KEY `id_utilisateur` (`id_utilisateur`),
  ADD KEY `id_paiement` (`id_paiement`);

--
-- Index pour la table `Administrateur`
--
ALTER TABLE `Administrateur`
  ADD PRIMARY KEY (`id_admin`),
  ADD UNIQUE KEY `email_admin` (`email_admin`);

--
-- Index pour la table `Animal`
--
ALTER TABLE `Animal`
  ADD PRIMARY KEY (`id_animal`),
  ADD KEY `id_utilisateur` (`id_utilisateur`);

--
-- Index pour la table `avis`
--
ALTER TABLE `avis`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Index pour la table `CGU_mentions_legales`
--
ALTER TABLE `CGU_mentions_legales`
  ADD PRIMARY KEY (`id_cgu`),
  ADD KEY `id_admin` (`id_admin`);

--
-- Index pour la table `creation_compte`
--
ALTER TABLE `creation_compte`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `discussion`
--
ALTER TABLE `discussion`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sender_id` (`sender_id`),
  ADD KEY `receiver_id` (`receiver_id`);

--
-- Index pour la table `FAQ`
--
ALTER TABLE `FAQ`
  ADD PRIMARY KEY (`id_faq`),
  ADD KEY `id_admin` (`id_admin`);

--
-- Index pour la table `Galerie_photo`
--
ALTER TABLE `Galerie_photo`
  ADD PRIMARY KEY (`id_galerie`),
  ADD KEY `id_utilisateur` (`id_utilisateur`),
  ADD KEY `id_hebergement` (`id_hebergement`);

--
-- Index pour la table `Hebergement`
--
ALTER TABLE `Hebergement`
  ADD PRIMARY KEY (`id_hebergement`),
  ADD KEY `id_reservation` (`id_reservation`),
  ADD KEY `id_paiement` (`id_paiement`),
  ADD KEY `id_avis` (`id_avis`);

--
-- Index pour la table `Paiement`
--
ALTER TABLE `Paiement`
  ADD PRIMARY KEY (`id_paiement`),
  ADD KEY `id_reservation` (`id_reservation`);

--
-- Index pour la table `photo`
--
ALTER TABLE `photo`
  ADD PRIMARY KEY (`id_photo`),
  ADD KEY `id_utilisateur` (`id_utilisateur`),
  ADD KEY `id_galerie` (`id_galerie`);

--
-- Index pour la table `reservation`
--
ALTER TABLE `reservation`
  ADD PRIMARY KEY (`id_reservation`),
  ADD KEY `id_utilisateur` (`id_utilisateur`),
  ADD KEY `fk_gardien` (`gardien_id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `Abonnement`
--
ALTER TABLE `Abonnement`
  MODIFY `id_abo` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `Administrateur`
--
ALTER TABLE `Administrateur`
  MODIFY `id_admin` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `Animal`
--
ALTER TABLE `Animal`
  MODIFY `id_animal` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `avis`
--
ALTER TABLE `avis`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `CGU_mentions_legales`
--
ALTER TABLE `CGU_mentions_legales`
  MODIFY `id_cgu` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `creation_compte`
--
ALTER TABLE `creation_compte`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT pour la table `discussion`
--
ALTER TABLE `discussion`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `FAQ`
--
ALTER TABLE `FAQ`
  MODIFY `id_faq` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `Galerie_photo`
--
ALTER TABLE `Galerie_photo`
  MODIFY `id_galerie` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `Hebergement`
--
ALTER TABLE `Hebergement`
  MODIFY `id_hebergement` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `Paiement`
--
ALTER TABLE `Paiement`
  MODIFY `id_paiement` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `photo`
--
ALTER TABLE `photo`
  MODIFY `id_photo` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `reservation`
--
ALTER TABLE `reservation`
  MODIFY `id_reservation` int(11) NOT NULL AUTO_INCREMENT;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `Abonnement`
--
ALTER TABLE `Abonnement`
  ADD CONSTRAINT `Abonnement_ibfk_1` FOREIGN KEY (`id_utilisateur`) REFERENCES `creation_compte` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `Abonnement_ibfk_2` FOREIGN KEY (`id_paiement`) REFERENCES `Paiement` (`id_paiement`) ON DELETE CASCADE;

--
-- Contraintes pour la table `Animal`
--
ALTER TABLE `Animal`
  ADD CONSTRAINT `Animal_ibfk_1` FOREIGN KEY (`id_utilisateur`) REFERENCES `creation_compte` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `avis`
--
ALTER TABLE `avis`
  ADD CONSTRAINT `avis_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `creation_compte` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `CGU_mentions_legales`
--
ALTER TABLE `CGU_mentions_legales`
  ADD CONSTRAINT `CGU_mentions_legales_ibfk_1` FOREIGN KEY (`id_admin`) REFERENCES `Administrateur` (`id_admin`) ON DELETE CASCADE;

--
-- Contraintes pour la table `discussion`
--
ALTER TABLE `discussion`
  ADD CONSTRAINT `discussion_ibfk_1` FOREIGN KEY (`sender_id`) REFERENCES `creation_compte` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `discussion_ibfk_2` FOREIGN KEY (`receiver_id`) REFERENCES `creation_compte` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `FAQ`
--
ALTER TABLE `FAQ`
  ADD CONSTRAINT `FAQ_ibfk_1` FOREIGN KEY (`id_admin`) REFERENCES `Administrateur` (`id_admin`) ON DELETE CASCADE;

--
-- Contraintes pour la table `Galerie_photo`
--
ALTER TABLE `Galerie_photo`
  ADD CONSTRAINT `Galerie_photo_ibfk_1` FOREIGN KEY (`id_utilisateur`) REFERENCES `creation_compte` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `Galerie_photo_ibfk_2` FOREIGN KEY (`id_hebergement`) REFERENCES `Hebergement` (`id_hebergement`) ON DELETE CASCADE;

--
-- Contraintes pour la table `Hebergement`
--
ALTER TABLE `Hebergement`
  ADD CONSTRAINT `Hebergement_ibfk_1` FOREIGN KEY (`id_reservation`) REFERENCES `reservation` (`id_reservation`) ON DELETE CASCADE,
  ADD CONSTRAINT `Hebergement_ibfk_2` FOREIGN KEY (`id_paiement`) REFERENCES `Paiement` (`id_paiement`) ON DELETE CASCADE,
  ADD CONSTRAINT `Hebergement_ibfk_3` FOREIGN KEY (`id_avis`) REFERENCES `avis` (`id`) ON DELETE SET NULL;

--
-- Contraintes pour la table `Paiement`
--
ALTER TABLE `Paiement`
  ADD CONSTRAINT `Paiement_ibfk_1` FOREIGN KEY (`id_reservation`) REFERENCES `reservation` (`id_reservation`) ON DELETE CASCADE;

--
-- Contraintes pour la table `photo`
--
ALTER TABLE `photo`
  ADD CONSTRAINT `photo_ibfk_1` FOREIGN KEY (`id_utilisateur`) REFERENCES `creation_compte` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `photo_ibfk_2` FOREIGN KEY (`id_galerie`) REFERENCES `Galerie_photo` (`id_galerie`) ON DELETE CASCADE;

--
-- Contraintes pour la table `reservation`
--
ALTER TABLE `reservation`
  ADD CONSTRAINT `fk_gardien` FOREIGN KEY (`gardien_id`) REFERENCES `creation_compte` (`id`),
  ADD CONSTRAINT `reservation_ibfk_1` FOREIGN KEY (`id_utilisateur`) REFERENCES `creation_compte` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
