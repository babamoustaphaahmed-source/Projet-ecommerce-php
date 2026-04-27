-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : lun. 27 avr. 2026 à 18:23
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `site_vente`
--

-- --------------------------------------------------------

--
-- Structure de la table `article`
--

CREATE TABLE `article` (
  `id_article` int(3) NOT NULL,
  `nom_article` varchar(150) NOT NULL,
  `description` text DEFAULT NULL,
  `prix` decimal(10,2) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `stock` int(11) DEFAULT 0,
  `id_admin` int(11) DEFAULT NULL,
  `date_ajout` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `article`
--

INSERT INTO `article` (`id_article`, `nom_article`, `description`, `prix`, `image`, `stock`, `id_admin`, `date_ajout`) VALUES
(2, 'Montre Intelligente ', 'Elle est très  performante', 15000.00, 'uploads/1776448054_montre-intelligente-gts5-pour-hommes-et-femmes-fr.jpg', 7, 1, '2026-04-17 19:47:34'),
(3, 'pizza', 'c\'est doux', 2300.00, 'uploads/1776514670_thomas-tucker-MNtag_eXMKw-unsplash.jpg', 10, 1, '2026-04-18 14:17:51'),
(4, 'Jus d\'orange', 'Très délicieux', 1000.00, 'uploads/1776761387_abhishek-hajare-kkrXVKK-jhg-unsplash.jpg', 4, 1, '2026-04-21 10:49:50');

-- --------------------------------------------------------

--
-- Structure de la table `commande`
--

CREATE TABLE `commande` (
  `id_commande` int(11) NOT NULL,
  `id_client` int(11) DEFAULT NULL,
  `id_article` int(11) DEFAULT NULL,
  `quantite` int(11) DEFAULT 1,
  `prix_total` decimal(10,2) DEFAULT NULL,
  `statut` enum('En_attente','validee','livree') DEFAULT 'En_attente',
  `date_commande` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `commande`
--

INSERT INTO `commande` (`id_commande`, `id_client`, `id_article`, `quantite`, `prix_total`, `statut`, `date_commande`) VALUES
(2, 2, 2, 1, 15000.00, 'En_attente', '2026-04-17 21:49:01'),
(6, 4, 3, 1, 2300.00, 'En_attente', '2026-04-18 19:34:52'),
(7, 5, 2, 1, 15000.00, 'En_attente', '2026-04-18 22:27:47'),
(8, 2, 4, 1, 1000.00, 'validee', '2026-04-21 10:54:00');

-- --------------------------------------------------------

--
-- Structure de la table `panier`
--

CREATE TABLE `panier` (
  `id_panier` int(11) NOT NULL,
  `id_utilisateur` int(11) NOT NULL,
  `id_article` int(11) NOT NULL,
  `quantite` int(11) DEFAULT 1,
  `date_ajout` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

CREATE TABLE `utilisateur` (
  `id` int(3) NOT NULL,
  `no` varchar(35) NOT NULL,
  `prenom` varchar(55) NOT NULL,
  `email` varchar(55) NOT NULL,
  `motpasse` varchar(255) DEFAULT NULL,
  `role` enum('admin','client') DEFAULT 'client',
  `date_inscription` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `utilisateur`
--

INSERT INTO `utilisateur` (`id`, `no`, `prenom`, `email`, `motpasse`, `role`, `date_inscription`) VALUES
(1, 'BABA', 'Ahmed', 'admin@site.com', '$2y$10$CTdDuQC6eG.D3bjKbNcxreGFN.MGvSdMY8OCvIGZtVkIs7b6ebjwG', 'admin', '2026-04-17 00:34:22'),
(2, 'NANA', 'GEZA', 'nanagezaa@gmail.com', '$2y$10$n2xMbOCgkilgYdt4K/mWsO2hKXZce1oUMIDquLBQ4VtL3ZGbgKhUy', 'client', '2026-04-17 14:53:02'),
(4, 'BABA', 'Sahada', 'babasahada11@gmail.com', '$2y$10$.dSA27lzY960gZ5CzXQMc.1vJIuA/VRkgbkujv5fkXMAwXTPYkf76', 'client', '2026-04-18 19:32:57'),
(5, 'YOUMA', 'Rachide', 'youmarachide@gmail.com', '$2y$10$xFeepz1dFW6yLBhO6tdh8OrgsEknSo2m8Hlu2Eysc0mmSvm/avpje', 'client', '2026-04-18 22:26:17');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `article`
--
ALTER TABLE `article`
  ADD PRIMARY KEY (`id_article`),
  ADD KEY `id_admin` (`id_admin`);

--
-- Index pour la table `commande`
--
ALTER TABLE `commande`
  ADD PRIMARY KEY (`id_commande`),
  ADD KEY `id_client` (`id_client`),
  ADD KEY `id_article` (`id_article`);

--
-- Index pour la table `panier`
--
ALTER TABLE `panier`
  ADD PRIMARY KEY (`id_panier`),
  ADD KEY `id_utilisateur` (`id_utilisateur`),
  ADD KEY `id_article` (`id_article`);

--
-- Index pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `article`
--
ALTER TABLE `article`
  MODIFY `id_article` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `commande`
--
ALTER TABLE `commande`
  MODIFY `id_commande` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT pour la table `panier`
--
ALTER TABLE `panier`
  MODIFY `id_panier` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  MODIFY `id` int(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `article`
--
ALTER TABLE `article`
  ADD CONSTRAINT `article_ibfk_1` FOREIGN KEY (`id_admin`) REFERENCES `utilisateur` (`id`) ON DELETE SET NULL;

--
-- Contraintes pour la table `commande`
--
ALTER TABLE `commande`
  ADD CONSTRAINT `commande_ibfk_1` FOREIGN KEY (`id_client`) REFERENCES `utilisateur` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `commande_ibfk_2` FOREIGN KEY (`id_article`) REFERENCES `article` (`id_article`) ON DELETE CASCADE;

--
-- Contraintes pour la table `panier`
--
ALTER TABLE `panier`
  ADD CONSTRAINT `panier_ibfk_1` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateur` (`id`),
  ADD CONSTRAINT `panier_ibfk_2` FOREIGN KEY (`id_article`) REFERENCES `article` (`id_article`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
