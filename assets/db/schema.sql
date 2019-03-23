-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le :  Dim 23 déc. 2018 à 17:15
-- Version du serveur :  5.7.19
-- Version de PHP :  5.6.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `odldcbis`
--

-- --------------------------------------------------------

--
-- Structure de la table `odldc_changelog`
--

DROP TABLE IF EXISTS `odldc_changelog`;
CREATE TABLE IF NOT EXISTS `odldc_changelog` (
  `id` text NOT NULL,
  `author` text NOT NULL,
  `cl_type` text NOT NULL,
  `name_era` text NOT NULL,
  `name_period` text NOT NULL,
  `old_position` text NOT NULL,
  `new_position` text NOT NULL,
  `title` text NOT NULL,
  `new_title` text NOT NULL,
  `cover` text NOT NULL,
  `content` text NOT NULL,
  `urban` text NOT NULL,
  `dctrad` text NOT NULL,
  `isEvent` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `odldc_era`
--

DROP TABLE IF EXISTS `odldc_era`;
CREATE TABLE IF NOT EXISTS `odldc_era` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `clean_name` varchar(100) NOT NULL,
  `id_era` varchar(60) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `odldc_period`
--

DROP TABLE IF EXISTS `odldc_period`;
CREATE TABLE IF NOT EXISTS `odldc_period` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `clean_name` varchar(100) NOT NULL,
  `id_era` varchar(60) NOT NULL,
  `id_period` varchar(60) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `odldc_rebirth`
--

DROP TABLE IF EXISTS `odldc_rebirth`;
CREATE TABLE IF NOT EXISTS `odldc_rebirth` (
  `id` int(11) NOT NULL,
  `id_period` varchar(60) NOT NULL,
  `arc` text NOT NULL,
  `cover` text NOT NULL,
  `contenu` text NOT NULL,
  `urban` text NOT NULL,
  `dctrad` text NOT NULL,
  `isEvent` tinyint(1) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `odldc_users`
--

DROP TABLE IF EXISTS `odldc_users`;
CREATE TABLE IF NOT EXISTS `odldc_users` (
  `user_name` text NOT NULL,
  `user_name_clean` text NOT NULL,
  `user_password` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table `odldc_admin`
--

DROP TABLE IF EXISTS `odldc_admin`;
CREATE TABLE IF NOT EXISTS `odldc_admin` (
  `param` varchar(60) NOT NULL,
  `value` tinyint(1) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;