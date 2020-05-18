-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le :  ven. 08 mai 2020 à 09:19
-- Version du serveur :  10.4.10-MariaDB
-- Version de PHP :  7.4.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  'blogphp'

CREATE DATABASE IF NOT EXISTS 'blogphp' DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE 'blogphp';

-- --------------------------------------------------------

--
-- Structure de la table 'comment'
--

DROP TABLE IF EXISTS 'comment';
CREATE TABLE IF NOT EXISTS 'comment' (
  'id' int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  'user_id' int(10) UNSIGNED NOT NULL,
  'post_id' int(10) UNSIGNED NOT NULL,
  'content' text NOT NULL,
  'created_at' timestamp NOT NULL DEFAULT current_timestamp(),
  'is_validated' tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY ('id'),
  KEY 'fk_comment_postid' ('post_id'),
  KEY 'fk_comment_userid' ('user_id')
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table 'post'
--

DROP TABLE IF EXISTS 'post';
CREATE TABLE IF NOT EXISTS 'post' (
  'id' int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  'user_id' int(11) UNSIGNED NOT NULL,
  'title' varchar(64) NOT NULL,
  'intro' text NOT NULL,
  'content' text NOT NULL,
  'created_at' timestamp NOT NULL DEFAULT current_timestamp(),
  'updated_at' timestamp NULL DEFAULT NULL ON UPDATE current_timestamp(),
  'image_url' varchar(255) DEFAULT NULL,
  PRIMARY KEY ('id'),
  KEY 'fk_post_userid' ('user_id')
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Structure de la table 'user'
--

DROP TABLE IF EXISTS 'user';
CREATE TABLE IF NOT EXISTS 'user' (
  'id' int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  'first_name' varchar(16) NOT NULL,
  'last_name' varchar(32) NOT NULL,
  'email' varchar(64) NOT NULL,
  'password' varchar(255) NOT NULL,
  'created_at' timestamp NOT NULL DEFAULT current_timestamp(),
  'is_admin' tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY ('id'),
  UNIQUE KEY 'UNIQUE' ('email') USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table 'comment'
--
ALTER TABLE 'comment'
  ADD CONSTRAINT 'fk_comment_postid' FOREIGN KEY ('post_id') REFERENCES 'post' ('id') ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT 'fk_comment_userid' FOREIGN KEY ('user_id') REFERENCES 'user' ('id') ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table 'post'
--
ALTER TABLE 'post'
  ADD CONSTRAINT 'fk_post_userid' FOREIGN KEY ('user_id') REFERENCES 'user' ('id') ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
