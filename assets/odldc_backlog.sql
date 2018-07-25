-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le :  mer. 25 juil. 2018 à 11:44
-- Version du serveur :  5.7.19
-- Version de PHP :  7.1.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `odldc`
--

-- --------------------------------------------------------

--
-- Structure de la table `odldc_backlog`
--

DROP TABLE IF EXISTS `odldc_backlog`;
CREATE TABLE IF NOT EXISTS `odldc_backlog` (
  `id` text NOT NULL,
  `bl_type` text NOT NULL,
  `name_period` text NOT NULL,
  `old_position` text NOT NULL,
  `new_position` text NOT NULL,
  `title` text NOT NULL,
  `new_title` text NOT NULL,
  `cover` text NOT NULL,
  `content` text NOT NULL,
  `urban` text NOT NULL,
  `dctrad` text NOT NULL,
  `link_urban` text NOT NULL,
  `topic` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
