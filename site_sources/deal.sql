-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le :  mar. 21 jan. 2020 à 12:47
-- Version du serveur :  10.4.10-MariaDB
-- Version de PHP :  7.3.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `deal`
--

-- --------------------------------------------------------

--
-- Structure de la table `annonce`
--

DROP TABLE IF EXISTS `annonce`;
CREATE TABLE IF NOT EXISTS `annonce` (
  `id_annonce` int(11) NOT NULL AUTO_INCREMENT,
  `titre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description_courte` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description_longue` varchar(2000) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prix` float NOT NULL,
  `photo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pays` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ville` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `adresse` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `code_postal` char(5) COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_membre` int(11) NOT NULL,
  `id_categorie` int(11) NOT NULL,
  `date_enregistrement` datetime NOT NULL,
  PRIMARY KEY (`id_annonce`),
  KEY `membre_id` (`id_membre`),
  KEY `categorie_id` (`id_categorie`)
) ENGINE=InnoDB AUTO_INCREMENT=38 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `annonce`
--

INSERT INTO `annonce` (`id_annonce`, `titre`, `description_courte`, `description_longue`, `prix`, `photo`, `pays`, `ville`, `adresse`, `code_postal`, `id_membre`, `id_categorie`, `date_enregistrement`) VALUES
(13, 'fiat 500', 'fiat 500 75ch de 2015', 'FIAT 500 1.3 MULTIJET 16V 95CH DPF S&amp;S POPSTAR 89G - année 2016 Diesel GRIS 39172km 6 Haut parleurs, Antipatinage, Arrêt et redémarrage auto. du moteur, Clim manuelle, Commandes vocales, Direction assistée asservie à la vitesse, Ecran tactile, Filtre à particules, Kit mains-libres Bluetooth, Préparation Isofix, Prise 12V, Prise auxiliaire de connexion audio, Prise USB, Rétroviseurs électriques, Toit panoramique en verre, Verrouillage centralisé à distance, Volant multifonction 9990€ TTC', 9990, 'photo/fiat 500_3.jpg', 'France', 'Paris', '1 Avenue Montain', '75016', 5, 21, '2020-01-17 16:48:15'),
(14, 'Fiat tipo', 'Fiat tipo 95ch de 2018', '\r\nTIPO Berline Mirror\r\nCraquer pour la nouvelle Fiat Tipo Mirror est la meilleure chose qui puisse vous arriver. Elle combine Style fort et Technologie connectée. Le pack chrome (baguettes contour de vitres, grille de calandre avant, cerclage des antibrouillards, coques de rétroviseurs, poignées de porte), les jantes 16&quot; finition diamant, s\'associent à la technologie Apple Carplay &amp; Android Auto sur Tablette tactile Uconnect 7 pouces.\r\n\r\nJantes 16&quot; finition diamant\r\n\r\nPack chrome\r\n\r\nTablette Uconnect tactile 7\'\' compatible Apple Carplay &amp; Android Auto\r\n\r\nClimatisation automatique\r\n\r\nRadar de recul', 11000, 'photo/Fiat tipo_1.jpg', 'France', 'Beauchamp', '131 Chaussée Jules César', '95250', 7, 21, '2020-01-17 19:45:22'),
(16, 'Galaxy Note9', 'Le Samsung Galaxy Note 9  128go', 'Le Samsung Galaxy Note 9 est le nouveau flagship de Samsung. Il fait suite au Galaxy Note 8, avec les caméras du Galaxy S9 Plus ainsi qu\'un stylet Bluetooth', 535, 'photo/Galaxy Note9_4.png', 'France', 'Beauchamp', '131 Chaussée Jules César', '95250', 4, 26, '2020-01-20 13:48:39'),
(26, 'Berger Australien pour saillie', 'Berger Australien pour saillie', 'Berger Australien pour saillie', 400, 'photo/Berger Australien pour saillie_7.jpg', 'France', 'Bessancourt', '20 Allée du Grand sentier', '95550', 4, 67, '2020-01-20 17:28:04'),
(27, 'renault trafic', 'renault trafic de 2017', 'DESCRIPTION\r\nEn accueillant jusqu\'à 9 sportifs, musiciens ou jeunes en vacances... avec leur bagages...Trafic Passenger fait une belle place à vos passions.\r\n\r\nLes équipements et l\'ambiance de bord sont dignes d\'un monospace : finitions élégantes, climatisation régulée, sièges confortables, nombreux rangements, dispositifs multimédia et de navigation nouvelle génération... Tout pour des voyages en groupe qui laissent des souvenirs inoubliables.\r\n\r\nRouler en Nouveau Trafic c\'est également garder le contrôle et se sentir en sécurité : freinage ABS, ESP et jusqu’à 8 airbags qui peuvent se déployer dans l\'habitacle.', 17000, 'photo/renault trafic_10.jpg', 'France', 'Bessancourt', '16 Allée du Grand sentier', '95550', 9, 21, '2020-01-21 08:55:01'),
(31, 'renault trafic', 'renault trafic de 2019', 'DESCRIPTION\r\nEn accueillant jusqu\'à 9 sportifs, musiciens ou jeunes en vacances... avec leur bagages...Trafic Passenger fait une belle place à vos passions.\r\n\r\nLes équipements et l\'ambiance de bord sont dignes d\'un monospace : finitions élégantes, climatisation régulée, sièges confortables, nombreux rangements, dispositifs multimédia et de navigation nouvelle génération... Tout pour des voyages en groupe qui laissent des souvenirs inoubliables.\r\n\r\nRouler en Nouveau Trafic c\'est également garder le contrôle et se sentir en sécurité : freinage ABS, ESP et jusqu’à 8 airbags qui peuvent se déployer dans l\'habitacle.', 14000, 'photo/renault trafic_11.jpg', 'France', 'Bessancourt', '16 Allée du Grand sentier', '95550', 9, 21, '2020-01-21 09:04:13'),
(34, 'Persan', 'Persan de couleur roux', 'Persan de couleur roux de seulement 1 mois disponible dans 30 jours', 1650, 'photo/Persan_12.jpg', 'France', 'Taverny', '110 Rue d\'herblay', '95200', 5, 67, '2020-01-21 09:13:15'),
(35, 'Rackdoll', 'Rackdoll de couleur blanc', 'Rackdoll de 2 mois disponible immédiatement', 1250, 'photo/Rackdoll_6.jpg', 'France', 'Taverny', '110 Rue d\'herblay', '95200', 5, 67, '2020-01-21 09:14:25'),
(36, 'Laika de yakoutie', 'Laika de yakoutie', 'Laika de yakoutie de couleur Blanc et noir pour saillie très sociable avec les chats et adore les enfants', 500, 'photo/Laika de yakoutie_13.jpg', 'France', 'Mériel', '9  Rue des boulots', '95150', 6, 67, '2020-01-21 09:18:27');

-- --------------------------------------------------------

--
-- Structure de la table `categorie`
--

DROP TABLE IF EXISTS `categorie`;
CREATE TABLE IF NOT EXISTS `categorie` (
  `id_categorie` int(11) NOT NULL AUTO_INCREMENT,
  `titre` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mots_cles` text COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id_categorie`)
) ENGINE=InnoDB AUTO_INCREMENT=76 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `categorie`
--

INSERT INTO `categorie` (`id_categorie`, `titre`, `mots_cles`) VALUES
(20, 'Emploi', 'Offres d\'emploi'),
(21, 'Véhicule', 'Voitures, Motos, Bateaux, Vélos, Equipement'),
(24, 'Immobilier', 'Ventes, Locations, Colocations, Bureaux, Logement'),
(25, 'Vacances', 'Camping, Hôtels, Hôte'),
(26, 'Multimédia', 'Jeux vidéos, Informatique, Image, Son, Téléphone'),
(27, 'Loisirs', 'Films, Musique, Livres'),
(28, 'Matériel', 'Outillage, Fournitures de bureau, Matériel agricole'),
(29, 'Services', 'Prestations de services, Evénements '),
(30, 'Maison', 'Ameublement, Electroménager, Bricolage, Jardinage'),
(31, 'Vétements', 'Jean, Chemise, Robe, Chaussure,'),
(57, 'Autres', ''),
(67, 'Animaux', 'Chien, Chat'),
(74, 'AAAAAAAA', 'zzzz');

-- --------------------------------------------------------

--
-- Structure de la table `commentaire`
--

DROP TABLE IF EXISTS `commentaire`;
CREATE TABLE IF NOT EXISTS `commentaire` (
  `id_commentaire` int(11) NOT NULL AUTO_INCREMENT,
  `commentaire` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_membre` int(11) NOT NULL,
  `id_annonce` int(11) NOT NULL,
  `date_enregistrement` datetime NOT NULL,
  PRIMARY KEY (`id_commentaire`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `membre`
--

DROP TABLE IF EXISTS `membre`;
CREATE TABLE IF NOT EXISTS `membre` (
  `id_membre` int(11) NOT NULL AUTO_INCREMENT,
  `pseudo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mdp` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nom` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prenom` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL,
  `telephone` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL,
  `civilite` enum('m','f') COLLATE utf8mb4_unicode_ci NOT NULL,
  `statut` enum('0','1') COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_enregistrement` datetime NOT NULL,
  PRIMARY KEY (`id_membre`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `membre`
--

INSERT INTO `membre` (`id_membre`, `pseudo`, `mdp`, `nom`, `prenom`, `telephone`, `email`, `civilite`, `statut`, `date_enregistrement`) VALUES
(4, 'Nono', '$2y$10$wRdR9BqZ7m0.mWi7zaFVweFDkRSjzXpkrSJArKkgMPNDiTJv1BwKu', 'MALFAIT', 'Arnaud', '0629948102', 'arnaud.malfait@hotmail.fr', 'm', '1', '2020-01-17 16:25:58'),
(5, 'Chacha', '$2y$10$UjYp7BImkn4C2KXBzRHovu7WN5v6WVZsbrIK8ppB2O3pkKxInECZy', 'Fouineteau', 'Charlotte', '0654304795', 'chacha938@hotmail.com', 'f', '0', '2020-01-17 16:46:10'),
(6, 'Olivier', '$2y$10$WbfsfrmzH/o6WyHqd4XtB.ZQ5pOW4EHbdTzf73.LoMrjVK/MjC.Sm', 'MALFAIT', 'Olivier', '0284632893', 'olivier.malfait@hotmail.fr', 'm', '0', '2020-01-17 16:47:02'),
(7, 'Marion', '$2y$10$khVju5XhsR9WSI/MyaZagOcf76VfDrRt9LDqZjqhAtdQavjBLN0PS', 'THIEBOT', 'Marion', '0648932596', 'marion.thiebot@hotmail.fr', 'f', '0', '2020-01-17 19:43:33'),
(9, 'Barbo', '$2y$10$2/FLY/Fs95UP0mLGWlZn5.LF2Q/94uILn1bmmTjav1Tjia8d24s9u', 'LAUNAY', 'Damien', '0617965426', 'damien.launay@gmail.com', 'm', '0', '2020-01-20 09:42:02'),
(10, 'Hélène', '$2y$10$T85R.zcXy4XeB2r5jNXNgeEYa490OoH.IsWbZgqtMD6uD1EtnI0x2', 'Hélène ', 'Laura-Huchon', '0614796247', 'helene.laura.huchon@gmail.com', 'f', '0', '2020-01-20 09:48:22'),
(11, 'Clem', '$2y$10$FdsyLLwXsvHWPi0O99kUSOfWNLQI9Ml6WfdZKuSLccSAOprivZolG', 'Clémence', 'Fleuret', '0614796247', 'clemence.fleuret@gmail.com', 'f', '0', '2020-01-20 09:49:20'),
(16, 'Sabrina', '$2y$10$y7ifV12Jemr/fteAf/kB9ugFbGKt02080FRx1RV/bXeU//T.qPiZ6', 'Christine', 'MALFAIT', '0634896523', 'christine.malfait@hotmail.fr', 'f', '0', '2020-01-21 09:53:59');

-- --------------------------------------------------------

--
-- Structure de la table `note`
--

DROP TABLE IF EXISTS `note`;
CREATE TABLE IF NOT EXISTS `note` (
  `id_note` int(11) NOT NULL AUTO_INCREMENT,
  `note` int(11) NOT NULL,
  `avis` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `id_membre1` int(11) NOT NULL,
  `id_membre2` int(11) NOT NULL,
  `date_enregistrement` datetime NOT NULL,
  PRIMARY KEY (`id_note`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
