-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Client :  127.0.0.1
-- Généré le :  Ven 28 Octobre 2016 à 23:15
-- Version du serveur :  10.1.16-MariaDB
-- Version de PHP :  7.0.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `cloud-aray`
--

-- --------------------------------------------------------

--
-- Structure de la table `commentaire`
--

CREATE TABLE `commentaire` (
  `id` int(11) NOT NULL,
  `contenu` text,
  `date` datetime DEFAULT CURRENT_TIMESTAMP,
  `idUtilisateur` int(11) NOT NULL,
  `idDisque` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `disque`
--

CREATE TABLE `disque` (
  `id` int(11) NOT NULL,
  `nom` varchar(50) DEFAULT NULL,
  `idUtilisateur` int(11) NOT NULL,
  `createdAt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `nouveau` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `disque`
--

INSERT INTO `disque` (`id`, `nom`, `idUtilisateur`, `createdAt`, `nouveau`) VALUES
(1, 'Datas', 1, '2016-03-28 18:59:58', 0),
(2, 'System', 1, '2016-03-20 18:59:58', 0),
(3, 'Jacob datas', 2, '2016-02-15 18:59:58', 0),
(4, 'System', 2, '2016-03-26 18:59:58', 0),
(6, 'Server web', 2, '2016-03-26 18:59:58', 0),
(7, 'Archives', 1, '2015-12-18 18:59:58', 1);

--
-- Déclencheurs `disque`
--


-- --------------------------------------------------------

--
-- Structure de la table `disque_service`
--

CREATE TABLE `disque_service` (
  `idDisque` int(11) NOT NULL,
  `idService` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `disque_service`
--

INSERT INTO `disque_service` (`idDisque`, `idService`) VALUES
(1, 1),
(1, 3),
(2, 1),
(2, 2),
(3, 1),
(4, 1),
(4, 2),
(6, 1),
(6, 2),
(6, 3),
(7, 1);

-- --------------------------------------------------------

--
-- Structure de la table `disque_tarif`
--

CREATE TABLE `disque_tarif` (
  `idDisque` int(11) NOT NULL,
  `idTarif` int(11) NOT NULL,
  `startDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `disque_tarif`
--

INSERT INTO `disque_tarif` (`idDisque`, `idTarif`, `startDate`) VALUES
(1, 1, '2016-03-12 18:37:25'),
(2, 1, '2016-10-17 15:38:14'),
(2, 2, '2016-03-12 18:39:20'),
(2, 2, '2016-10-17 15:38:59'),
(2, 3, '2016-10-17 15:38:22'),
(2, 3, '2016-10-17 15:38:38'),
(3, 2, '2016-10-25 01:17:16'),
(3, 3, '2016-10-15 15:29:46'),
(4, 1, '2016-10-15 15:30:03'),
(6, 1, '2016-10-15 15:30:19'),
(7, 1, '2016-10-27 03:40:56'),
(7, 1, '2016-10-27 03:58:58'),
(7, 2, '2016-10-27 03:58:51'),
(7, 2, '2016-10-27 03:59:20');

-- --------------------------------------------------------

--
-- Structure de la table `facture`
--

CREATE TABLE `facture` (
  `id` int(11) NOT NULL,
  `total` float NOT NULL,
  `date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `reglee` tinyint(1) DEFAULT NULL,
  `idUtilisateur` int(11) NOT NULL,
  `idDisque` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `historique`
--

CREATE TABLE `historique` (
  `idDisque` int(11) NOT NULL,
  `date` date NOT NULL,
  `occupation` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `historique`
--

INSERT INTO `historique` (`idDisque`, `date`, `occupation`) VALUES
(1, '0000-00-00', 102905),
(1, '2016-03-16', 97518),
(1, '2016-03-17', 97518),
(1, '2016-03-18', 97518),
(1, '2016-03-27', 102905),
(1, '2016-03-28', 102905),
(2, '0000-00-00', 0),
(2, '2016-03-16', 12696741),
(2, '2016-03-17', 12756959),
(2, '2016-03-18', 12756959),
(2, '2016-03-27', 0),
(2, '2016-03-28', 0),
(3, '0000-00-00', 0),
(3, '2016-03-27', 0),
(3, '2016-03-28', 0),
(3, '2016-10-28', 0),
(4, '0000-00-00', 0),
(4, '2016-03-27', 0),
(4, '2016-03-28', 0),
(6, '0000-00-00', 0),
(6, '2016-03-27', 0),
(6, '2016-03-28', 0),
(7, '0000-00-00', 0),
(7, '2016-03-16', 137072),
(7, '2016-03-17', 137072),
(7, '2016-03-18', 137072),
(7, '2016-03-27', 0),
(7, '2016-03-28', 0);

-- --------------------------------------------------------

--
-- Structure de la table `message`
--

CREATE TABLE `message` (
  `id` int(11) NOT NULL,
  `objet` varchar(45) DEFAULT NULL,
  `contenu` text,
  `date` datetime DEFAULT CURRENT_TIMESTAMP,
  `lu` tinyint(1) DEFAULT NULL,
  `idExpediteur` int(11) NOT NULL,
  `idReceveur` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `service`
--

CREATE TABLE `service` (
  `id` int(11) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `description` text,
  `prix` float(8,2) DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `service`
--

INSERT INTO `service` (`id`, `nom`, `description`, `prix`) VALUES
(1, 'Backup', 'Sauvegarde journalière de toutes les données sur un disque de sauvegarde', 4.00),
(2, 'LoadBalancer', 'Ce service permet de répartir la charge entre 2 à 5 VM pour répondre au mieux à vos exigences de trafic. Si vous êtes soumis à des pics de charge, il vous suffit d''allumer vos VM au fil de vos besoins. Ce service permet également de faire de la haute disponibilité de VM en doublant vos ressources VM.', 40.00),
(3, 'Bande passante', '10 Mbps de bande passante garantie par environnement pour 0 €. Vous pouvez néanmoins opter pour une extension allant jusqu''à 1 Gbps, elle vous sera facturée mensuellement. Les transferts de données vers ou depuis Internet ne sont pas facturés.', 160.00);

-- --------------------------------------------------------

--
-- Structure de la table `tarif`
--

CREATE TABLE `tarif` (
  `id` int(11) NOT NULL,
  `quota` bigint(20) DEFAULT NULL,
  `coutDepassement` float(8,2) DEFAULT NULL,
  `prix` float(8,2) DEFAULT NULL,
  `unite` set('o','Ko','Mo','Go','To') DEFAULT NULL,
  `margeDepassement` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `tarif`
--

INSERT INTO `tarif` (`id`, `quota`, `coutDepassement`, `prix`, `unite`, `margeDepassement`) VALUES
(1, 200, 0.00, 0.00, 'Ko', 0.02),
(2, 15, 0.01, 5.00, 'Mo', 0.02),
(3, 1, 0.01, 0.01, 'Ko', 0.02);

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

CREATE TABLE `utilisateur` (
  `id` int(11) NOT NULL,
  `login` varchar(30) NOT NULL,
  `mail` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `nom` varchar(35) DEFAULT NULL,
  `prenom` varchar(35) DEFAULT NULL,
  `tel` varchar(10) DEFAULT NULL,
  `admin` tinyint(1) NOT NULL,
  `createdAt` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `nouveau` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contenu de la table `utilisateur`
--

INSERT INTO `utilisateur` (`id`, `login`, `mail`, `password`, `nom`, `prenom`, `tel`, `admin`, `createdAt`, `nouveau`) VALUES
(1, 'eAllman', 'eric.allman@gmail.com', '94716e5905252be105681d723e18caafd8120402019c31cadc227d61bf6bb29e', 'Allman', 'Eric', '0782xxxxxx', 1, '2016-03-28 19:09:32', 0),
(2, 'jAppelbaum', 'jacob.appelbaum@gmail.com', '9af15b336e6a9619928537df30b2e6a2376569fcf9d7e773eccede65606529a0', 'Appelbaum', 'Jacob', NULL, 1, '2016-03-14 19:09:32', 0),
(3, 'kdMitnick', 'kd.mitnick@gmail.com', '84d89877f0d4041efb6bf91a16f0248f2fd573e6af05c19f96bedb9f882f7882', 'Mitnick', 'Kevin David', NULL, 0, '2016-03-28 19:09:32', 0),
(4, 'djbernstein', 'djbernstein@gmail.com', 'aa3d2fe4f6d301dbd6b8fb2d2fddfb7aeebf3bec53ffff4b39a0967afa88c609', 'Bernstein', 'Daniel J.', '0612344444', 1, '2016-03-26 19:09:32', 0),
(5, 'linus.torvals', 'linus.torvals@gmail.com', 'e7042ac7d09c7bc41c8cfa5749e41858f6980643bc0db1a83cc793d3e24d3f77', 'Torvals', 'Linus', NULL, 1, '2016-03-27 19:09:32', 0);

--
-- Index pour les tables exportées
--

--
-- Index pour la table `commentaire`
--
ALTER TABLE `commentaire`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_commentaire_utilisateur1_idx` (`idUtilisateur`),
  ADD KEY `fk_commentaire_disque1_idx` (`idDisque`);

--
-- Index pour la table `disque`
--
ALTER TABLE `disque`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idUtilisateur` (`idUtilisateur`);

--
-- Index pour la table `disque_service`
--
ALTER TABLE `disque_service`
  ADD PRIMARY KEY (`idDisque`,`idService`),
  ADD KEY `idService` (`idService`);

--
-- Index pour la table `disque_tarif`
--
ALTER TABLE `disque_tarif`
  ADD PRIMARY KEY (`idDisque`,`idTarif`,`startDate`),
  ADD KEY `idTarif` (`idTarif`);

--
-- Index pour la table `facture`
--
ALTER TABLE `facture`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_facture_utilisateur1_idx` (`idUtilisateur`),
  ADD KEY `fk_facture_disque1_idx` (`idDisque`);

--
-- Index pour la table `historique`
--
ALTER TABLE `historique`
  ADD PRIMARY KEY (`idDisque`,`date`);

--
-- Index pour la table `message`
--
ALTER TABLE `message`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_message_utilisateur1_idx` (`idExpediteur`),
  ADD KEY `fk_message_utilisateur2_idx` (`idReceveur`);

--
-- Index pour la table `service`
--
ALTER TABLE `service`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `tarif`
--
ALTER TABLE `tarif`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `commentaire`
--
ALTER TABLE `commentaire`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `disque`
--
ALTER TABLE `disque`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT pour la table `facture`
--
ALTER TABLE `facture`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `message`
--
ALTER TABLE `message`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT pour la table `service`
--
ALTER TABLE `service`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT pour la table `tarif`
--
ALTER TABLE `tarif`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `commentaire`
--
ALTER TABLE `commentaire`
  ADD CONSTRAINT `fk_commentaire_disque1` FOREIGN KEY (`idDisque`) REFERENCES `disque` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_commentaire_utilisateur1` FOREIGN KEY (`idUtilisateur`) REFERENCES `utilisateur` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `disque`
--
ALTER TABLE `disque`
  ADD CONSTRAINT `disque_ibfk_2` FOREIGN KEY (`idUtilisateur`) REFERENCES `utilisateur` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `disque_service`
--
ALTER TABLE `disque_service`
  ADD CONSTRAINT `FK_associer` FOREIGN KEY (`idDisque`) REFERENCES `disque` (`id`),
  ADD CONSTRAINT `disque_service_ibfk_1` FOREIGN KEY (`idService`) REFERENCES `service` (`id`);

--
-- Contraintes pour la table `disque_tarif`
--
ALTER TABLE `disque_tarif`
  ADD CONSTRAINT `disque_tarif_ibfk_1` FOREIGN KEY (`idDisque`) REFERENCES `disque` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `disque_tarif_ibfk_2` FOREIGN KEY (`idTarif`) REFERENCES `tarif` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `facture`
--
ALTER TABLE `facture`
  ADD CONSTRAINT `fk_facture_disque1` FOREIGN KEY (`idDisque`) REFERENCES `disque` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_facture_utilisateur1` FOREIGN KEY (`idUtilisateur`) REFERENCES `utilisateur` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `historique`
--
ALTER TABLE `historique`
  ADD CONSTRAINT `historique_ibfk_1` FOREIGN KEY (`idDisque`) REFERENCES `disque` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `message`
--
ALTER TABLE `message`
  ADD CONSTRAINT `fk_message_utilisateur1` FOREIGN KEY (`idExpediteur`) REFERENCES `utilisateur` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_message_utilisateur2` FOREIGN KEY (`idReceveur`) REFERENCES `utilisateur` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
