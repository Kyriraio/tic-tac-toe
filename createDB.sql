CREATE DATABASE IF NOT EXISTS `gameDB`;
USE gameDB;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";
CREATE TABLE IF NOT EXISTS `players` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `login` text NOT NULL,
    `password` text NOT NULL,
    `salt` text NOT NULL,
    `level` tinyint unsigned DEFAULT 1 NOT NULL ,
    PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 AUTO_INCREMENT=1 ;