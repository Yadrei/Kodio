-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:8889
-- Généré le : dim. 22 oct. 2023 à 17:23
-- Version du serveur : 5.7.39
-- Version de PHP : 8.2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `uranus`
--

-- --------------------------------------------------------

--
-- Structure de la table `REFERENCES_T`
--

CREATE TABLE `REFERENCES_T` (
  `REF` varchar(10) NOT NULL,
  `LABEL` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Index pour la table `REFERENCES_T`
--
ALTER TABLE `REFERENCES_T`
  ADD PRIMARY KEY (`REF`);

--
-- Déchargement des données de la table `REFERENCES_T`
--

INSERT INTO `REFERENCES_T` (`REF`, `LABEL`) VALUES
('R_CAT', 'Catégories de contenu'),
('R_LANG', 'Langues disponibles'),
('R_ROLE', 'R&ocirc;les pour les utilisateurs'),
('R_SETTING', 'Param&egrave;tres');

-- --------------------------------------------------------

--
-- Structure de la table `REFERENCES_D`
--

CREATE TABLE `REFERENCES_D` (
  `CLEF` varchar(10) NOT NULL,
  `FK_REF` varchar(10) NOT NULL,
  `LABEL` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Index pour la table `REFERENCES_D`
--
ALTER TABLE `REFERENCES_D`
  ADD PRIMARY KEY (`CLEF`,`FK_REF`),
  ADD KEY `CONST_FK_REF_T` (`FK_REF`);

--
-- Contraintes pour la table `REFERENCES_D`
--
ALTER TABLE `REFERENCES_D`
  ADD CONSTRAINT `CONST_FK_REF_T` FOREIGN KEY (`FK_REF`) REFERENCES `REFERENCES_T` (`REF`);

--
-- Déchargement des données de la table `REFERENCES_D`
--

INSERT INTO `REFERENCES_D` (`CLEF`, `FK_REF`, `LABEL`) VALUES
('SYSTEM', 'R_CAT', 'Syst&egrave;me'),
('MENU', 'R_CAT', 'Page li&eacute;e au menu'),
('PAGE', 'R_CAT', 'Page simple'),
('FR', 'R_LANG', 'Fran&ccedil;ais'),
('MAINT', 'R_SETTING', 'Mode maintenance'),
('COM', 'R_SETTING', 'Activer les commentaires'),
('COOKIES', 'R_SETTING', 'Activer les cookies'),
('WEB', 'R_ROLE', 'Webmaster'),
('SOC_FB', 'R_SETTING', 'Facebook'),
('SOC_TWT', 'R_SETTING', 'Twitter'),
('SOC_INST', 'R_SETTING', 'Instagram');

-- --------------------------------------------------------

--
-- Structure de la table `USERS`
--

CREATE TABLE `USERS` (
  `ID` int(11) NOT NULL,
  `NICKNAME` varchar(20) NOT NULL,
  `EMAIL` varchar(100) NOT NULL,
  `PASSWORD_PUBLIC` varchar(255) DEFAULT NULL,
  `PASSWORD_ADMIN` varchar(255) DEFAULT NULL,
  `R_ROLE` varchar(10) NOT NULL,
  `DATE_CRE` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Index pour la table `USERS`
--
ALTER TABLE `USERS`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `CONST_USER_ROLE` (`R_ROLE`);

--
-- AUTO_INCREMENT pour la table `USERS`
--
ALTER TABLE `USERS`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- Contraintes pour la table `USERS`
--
ALTER TABLE `USERS`
  ADD CONSTRAINT `CONST_USER_ROLE` FOREIGN KEY (`R_ROLE`) REFERENCES `REFERENCES_D` (`CLEF`);
COMMIT;

--
-- Déchargement des données de la table `USERS`
--

INSERT INTO `USERS` (`NICKNAME`, `EMAIL`, `PASSWORD_PUBLIC`, `PASSWORD_ADMIN`, `R_ROLE`, `DATE_CRE`) VALUES
('Yves', 'yves.ponchelet@shoku.be', NULL, '$2y$10$ftpM1Y4d0djxnz0Q.b0NbOgfDpyYnGHM6Z//rD/b7Vpog38Hha0wS', 'WEB', '2023-10-22 19:21:19');

--
-- Déclencheurs `USERS`
--
DELIMITER $$
CREATE TRIGGER `CHECK_ROLE_AJOUT` BEFORE INSERT ON `USERS` FOR EACH ROW IF (NEW.R_ROLE like "WEB") THEN
	SIGNAL SQLSTATE '45000'
    SET MESSAGE_TEXT = "Impossible de donner le r&ocirc;le 'WEB' car un utilisateur le possède déjà";
END IF
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `CHECK_ROLE_UPDATE` BEFORE UPDATE ON `USERS` FOR EACH ROW IF (NEW.R_ROLE LIKE "WEB" AND NEW.NICKNAME NOT LIKE "Yves") THEN
	SIGNAL SQLSTATE '45000'
    SET MESSAGE_TEXT = "Impossible de mettre le r&ocirc;le 'WEB' à un autre utilisateur";
END IF
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `PERMISSIONS_USER` AFTER INSERT ON `USERS` FOR EACH ROW INSERT INTO PERMISSIONS
VALUES (NEW.ID, FALSE, FALSE, FALSE, FALSE)
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Structure de la table `CONTENT`
--

CREATE TABLE `CONTENT` (
  `ID` int(11) NOT NULL,
  `DATE_CRE` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Index pour la table `CONTENT`
--
ALTER TABLE `CONTENT`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT pour la table `CONTENT`
--
ALTER TABLE `CONTENT`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

INSERT INTO `CONTENT` VALUES
(),
();

-- --------------------------------------------------------

--
-- Structure de la table `CONTENT_H`
--

CREATE TABLE `CONTENT_H` (
  `ID` int(11) NOT NULL,
  `ID_CONTENT` int(11) NOT NULL,
  `ID_CONTENT_LANG` int(11) NOT NULL,
  `R_LANG` varchar(10) NOT NULL,
  `R_CAT` varchar(10) NOT NULL,
  `TITLE` varchar(100) NOT NULL,
  `CONTENT` text,
  `META_TITLE` varchar(50) NOT NULL,
  `META_DESCRIPTION` varchar(200) NOT NULL,
  `SLUG` varchar(120) NOT NULL,
  `DTE` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ACTION` enum('ADD','UPDATE','DELETE', 'PUBLISHED', 'UNPUBLISHED') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Index pour la table `CONTENT_H`
--
ALTER TABLE `CONTENT_H`
  ADD PRIMARY KEY (`ID`);

  --
-- AUTO_INCREMENT pour la table `CONTENT_H`
--
ALTER TABLE `CONTENT_H`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

-- --------------------------------------------------------

--
-- Structure de la table `CONTENT_LANG`
--

CREATE TABLE `CONTENT_LANG` (
  `ID` int(11) NOT NULL,
  `FK_CONTENT` int(11) NOT NULL,
  `R_LANG` varchar(10) NOT NULL,
  `R_CAT` varchar(10) NOT NULL,
  `FK_AUTHOR` int(11) NOT NULL,
  `TITLE` varchar(100) NOT NULL,
  `CONTENT` text,
  `HEADING_IMAGE` varchar(50),
  `META_TITLE` varchar(50) NOT NULL,
  `META_DESCRIPTION` varchar(200) NOT NULL,
  `DATE_PUBLICATION` datetime,
  `SLUG` varchar(120) NOT NULL,
  `DATE_CRE` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `DATE_MOD` datetime DEFAULT NULL,
  `IS_PUBLISHED` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Index pour la table `CONTENT_LANG`
--
ALTER TABLE `CONTENT_LANG`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `CONST_MAIN_CONTENT` (`FK_CONTENT`),
  ADD KEY `CONST_CONTENT_LANG` (`R_LANG`),
  ADD KEY `CONST_CONTENT_CATEGORY` (`R_CAT`),
  ADD KEY `CONST_CONTENT_AUTHOR` (`FK_AUTHOR`);

--
-- AUTO_INCREMENT pour la table `CONTENT_LANG`
--
ALTER TABLE `CONTENT_LANG`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- Contraintes pour la table `CONTENT_LANG`
--
ALTER TABLE `CONTENT_LANG`
  ADD CONSTRAINT `CONST_CONTENT_AUTHOR` FOREIGN KEY (`FK_AUTHOR`) REFERENCES `USERS` (`ID`),
  ADD CONSTRAINT `CONST_CONTENT_CATEGORY` FOREIGN KEY (`R_CAT`) REFERENCES `REFERENCES_D` (`CLEF`),
  ADD CONSTRAINT `CONST_CONTENT_LANG` FOREIGN KEY (`R_LANG`) REFERENCES `REFERENCES_D` (`CLEF`),
  ADD CONSTRAINT `CONST_MAIN_CONTENT` FOREIGN KEY (`FK_CONTENT`) REFERENCES `CONTENT` (`ID`);

--
-- Déclencheurs `CONTENT_LANG`
--
DELIMITER $$
CREATE TRIGGER `HISTO_AJOUT` AFTER INSERT ON `CONTENT_LANG` FOR EACH ROW INSERT INTO CONTENT_H (ID_CONTENT, ID_CONTENT_LANG, R_LANG, R_CAT, TITLE, CONTENT, META_TITLE, META_DESCRIPTION, SLUG, DTE, ACTION) 
VALUES (NEW.FK_CONTENT, NEW.ID, NEW.R_LANG, NEW.R_CAT, NEW.TITLE, NEW.CONTENT, NEW.META_TITLE, NEW.META_DESCRIPTION, NEW.SLUG, NOW(), 'ADD')
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `HISTO_DELETE` AFTER DELETE ON `CONTENT_LANG` FOR EACH ROW INSERT INTO CONTENT_H (ID_CONTENT, ID_CONTENT_LANG, R_LANG, R_CAT, TITLE, CONTENT, META_TITLE, META_DESCRIPTION, SLUG, DTE, ACTION) 
VALUES (OLD.FK_CONTENT, OLD.ID, OLD.R_LANG, OLD.R_CAT, OLD.TITLE, OLD.CONTENT, OLD.META_TITLE, OLD.META_DESCRIPTION, OLD.SLUG, NOW(), 'DELETE')
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `HISTO_UPDATE` AFTER UPDATE ON `CONTENT_LANG` FOR EACH ROW IF (OLD.IS_PUBLISHED = 0 AND NEW.IS_PUBLISHED = 1)
THEN
    INSERT INTO CONTENT_H (ID_CONTENT, ID_CONTENT_LANG, R_LANG, R_CAT, TITLE, CONTENT, META_TITLE, META_DESCRIPTION, SLUG, DTE, ACTION) 
    VALUES (NEW.FK_CONTENT, NEW.ID, NEW.R_LANG, NEW.R_CAT, NEW.TITLE, NEW.CONTENT, NEW.META_TITLE, NEW.META_DESCRIPTION, NEW.SLUG, NOW(), 'PUBLISHED');
ELSEIF (OLD.IS_PUBLISHED = 1 AND NEW.IS_PUBLISHED = 0) THEN
    INSERT INTO CONTENT_H (ID_CONTENT, ID_CONTENT_LANG, R_LANG, R_CAT, TITLE, CONTENT, META_TITLE, META_DESCRIPTION, SLUG, DTE, ACTION) 
    VALUES (NEW.FK_CONTENT, NEW.ID, NEW.R_LANG, NEW.R_CAT, NEW.TITLE, NEW.CONTENT, NEW.META_TITLE, NEW.META_DESCRIPTION, NEW.SLUG, NOW(), 'UNPUBLISHED');
ELSE
    INSERT INTO CONTENT_H (ID_CONTENT, ID_CONTENT_LANG, R_LANG, R_CAT, TITLE, CONTENT, META_TITLE, META_DESCRIPTION, SLUG, DTE, ACTION) 
    VALUES (NEW.FK_CONTENT, NEW.ID, NEW.R_LANG, NEW.R_CAT, NEW.TITLE, NEW.CONTENT, NEW.META_TITLE, NEW.META_DESCRIPTION, NEW.SLUG, NOW(), 'UPDATE');
END IF;
$$
DELIMITER ;

INSERT INTO `CONTENT_LANG` (`FK_CONTENT`, `R_LANG`, `R_CAT`, `FK_AUTHOR`, `TITLE`, `META_TITLE`, `META_DESCRIPTION`, `SLUG`, `IS_PUBLISHED`) VALUES
(1, 'FR', 'SYSTEM', 1, 'Accueil site', 'Page d''accueil du site', 'Meta description globale', 'accueil', 1),
(2, 'FR', 'SYSTEM', 1, 'Contact site', 'Page de contact du site', 'Meta description contact', 'contact', 1);

-- --------------------------------------------------------

--
-- Structure de la table `COMMENTS`
--

CREATE TABLE `COMMENTS` (
  `ID` int NOT NULL,
  `NICKNAME` varchar(25) NOT NULL,
  `FK_CONTENT` int NOT NULL,
  `CONTENT` text NOT NULL,
  `DTE` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Index pour la table `COMMENTS`
--
ALTER TABLE `COMMENTS`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `CONST_COMMENT_CONTENT` (`FK_CONTENT`);

--
-- AUTO_INCREMENT pour la table `COMMENTS`
--
ALTER TABLE `COMMENTS`
  MODIFY `ID` int NOT NULL AUTO_INCREMENT;

--
-- Contraintes pour la table `COMMENTS`
--
ALTER TABLE `COMMENTS`
  ADD CONSTRAINT `CONST_COMMENT_CONTENT` FOREIGN KEY (`FK_CONTENT`) REFERENCES `CONTENT_LANG` (`ID`);
COMMIT;

-- --------------------------------------------------------

--
-- Structure de la table `MENU`
--

CREATE TABLE `MENU` (
  `ID` int(11) NOT NULL,
  `PARENT_ID` int(11) DEFAULT NULL,
  `R_LANG` varchar(10) NOT NULL,
  `FK_CONTENT` int(11),
  `LABEL` varchar(20) NOT NULL,
  `ORDRE` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Index pour la table `MENU`
--
ALTER TABLE `MENU`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `CONST_PARENT_ID` (`PARENT_ID`),
  ADD KEY `CONST_MENU_LANGUAGE` (`R_LANG`);

--
-- AUTO_INCREMENT pour la table `MENU`
--
ALTER TABLE `MENU`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- Contraintes pour la table `MENU`
--
ALTER TABLE `MENU`
  ADD CONSTRAINT `CONST_MENU_LANGUAGE` FOREIGN KEY (`R_LANG`) REFERENCES `REFERENCES_D` (`CLEF`),
  ADD CONSTRAINT `CONST_PARENT_ID` FOREIGN KEY (`PARENT_ID`) REFERENCES `MENU` (`ID`);

-- --------------------------------------------------------

--
-- Structure de la table `PERMISSIONS`
--

CREATE TABLE `PERMISSIONS` (
  `FK_USER` int(11) NOT NULL,
  `ALLOW_ADMIN` tinyint(1) NOT NULL DEFAULT '0',
  `ALLOW_ADD` tinyint(1) NOT NULL DEFAULT '0',
  `ALLOW_UPDATE` tinyint(1) NOT NULL DEFAULT '0',
  `ALLOW_DELETE` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Index pour la table `USERS`
--
ALTER TABLE `USERS`
  ADD PRIMARY KEY (`FK_USER`),
  ADD KEY `CONST_FK_USER_PK` (`FK_USER`);

--
-- Index pour la table `PERMISSIONS`
--
ALTER TABLE `PERMISSIONS`
  ADD KEY `CONST_PERMISSIONS_USER` (`FK_USER`);

--
-- Contraintes pour la table `PERMISSIONS`
--
ALTER TABLE `PERMISSIONS`
  ADD CONSTRAINT `CONST_PERMISSIONS_USER` FOREIGN KEY (`FK_USER`) REFERENCES `USERS` (`ID`);

--
-- Déchargement des données de la table `PERMISSIONS`
--

INSERT INTO `PERMISSIONS` (`FK_USER`, `ALLOW_ADMIN`, `ALLOW_ADD`, `ALLOW_UPDATE`, `ALLOW_DELETE`) VALUES
(1, 1, 1, 1, 1);

-- --------------------------------------------------------

--
-- Structure de la table `SETTINGS`
--

CREATE TABLE `SETTINGS` (
  `ID` int(11) NOT NULL,
  `R_SETTING` varchar(10) NOT NULL,
  `VALUE` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Index pour la table `SETTINGS`
--
ALTER TABLE `SETTINGS`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `CONST_SETTING` (`R_SETTING`);

--
-- AUTO_INCREMENT pour la table `SETTINGS`
--
ALTER TABLE `SETTINGS`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- Contraintes pour la table `SETTINGS`
--
ALTER TABLE `SETTINGS`
  ADD CONSTRAINT `CONST_SETTING` FOREIGN KEY (`R_SETTING`) REFERENCES `REFERENCES_D` (`CLEF`);

--
-- Déchargement des données de la table `SETTINGS`
--

INSERT INTO `SETTINGS` (`R_SETTING`, `VALUE`) VALUES
('MAINT', '0'),
('COM', '0'),
('COOKIES', '0'),
('SOC_FB', NULL),
('SOC_INST', NULL),
('SOC_TWT', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `TAGS`
--

CREATE TABLE `TAGS`
(
  `ID` int NOT NULL,
  `LABEL` varchar(20) NOT NULL,
  `COLOR` varchar(7) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Index pour la table `TAGS`
--
ALTER TABLE `TAGS`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT pour la table `TAGS`
--
ALTER TABLE `TAGS`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

-- --------------------------------------------------------

--
-- Structure de la table de jonction `J_CONTENT_TAGS`
--

CREATE TABLE `J_CONTENT_TAGS` (
    `ID` int NOT NULL,
    `FK_CONTENT` int NOT NULL,
    `FK_TAG` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Index pour la table `J_CONTENT_TAGS`
--
ALTER TABLE `J_CONTENT_TAGS`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `CONST_J_FK_CONTENT` (`FK_CONTENT`),
  ADD KEY `CONST_J_FK_TAG` (`FK_TAG`);

--
-- AUTO_INCREMENT pour la table `J_CONTENT_TAGS`
--
ALTER TABLE `J_CONTENT_TAGS`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT;

--
-- Contraintes pour la table `J_CONTENT_TAGS`
--
ALTER TABLE `J_CONTENT_TAGS`
  ADD CONSTRAINT `CONST_J_FK_CONTENT` FOREIGN KEY (`FK_CONTENT`) REFERENCES `CONTENT` (`ID`),
  ADD CONSTRAINT `CONST_J_FK_TAG` FOREIGN KEY (`FK_TAG`) REFERENCES `TAGS` (`ID`);
COMMIT;

-- --------------------------------------------------------

--
-- Événement de publication `CHECK_PUBLICATIONS`
--

CREATE EVENT `PUBLICATIONS` ON SCHEDULE EVERY 1 HOUR STARTS '2023-12-29 21:00:00.794000' ENDS '2033-12-31 23:00:00.688000' ON COMPLETION NOT PRESERVE ENABLE DO UPDATE CONTENT_LANG SET IS_PUBLISHED = TRUE WHERE DATE_PUBLICATION = NOW();

-- --------------------------------------------------------

--
-- Doublure de structure pour la vue `V_CONTENT_LANG`
-- (Voir ci-dessous la vue réelle)
--
CREATE TABLE `V_CONTENT_LANG` (
`ID` int(11)
,`FK_CONTENT` int(11)
,`LANGUAGE` varchar(50)
,`AUTHOR` varchar(20)
,`TITLE` varchar(100)
,`DATE_CRE` datetime
,`DATE_MOD` datetime
,`IS_PUBLISHED` varchar(10)
);

-- --------------------------------------------------------

--
-- Doublure de structure pour la vue `V_CONTENT_MAIN`
-- (Voir ci-dessous la vue réelle)
--
CREATE TABLE `V_CONTENT_MAIN` (
`ID` int(11)
,`FK_CONTENT` int(11)
,`CAT` varchar(50)
,`AUTHOR` varchar(20)
,`TITLE` varchar(100)
,`DATE_CRE` datetime
,`DATE_MOD` datetime
,`IS_PUBLISHED` varchar(10)
);

-- --------------------------------------------------------

--
-- Structure de la vue `V_CONTENT_LANG`
--
DROP TABLE IF EXISTS `V_CONTENT_LANG`;

CREATE VIEW `V_CONTENT_LANG`  AS   (select `C`.`ID` AS `ID`,`C`.`FK_CONTENT` AS `FK_CONTENT`,`R`.`LABEL` AS `LANGUAGE`,`U`.`NICKNAME` AS `AUTHOR`,`C`.`TITLE` AS `TITLE`,`C`.`SLUG` AS `SLUG`,`C`.`DATE_CRE` AS `DATE_CRE`,`C`.`DATE_MOD` AS `DATE_MOD`,(case when (`C`.`IS_PUBLISHED` = TRUE) then 'Publi&eacute;' else 'Non publi&eacute;' end) AS `IS_PUBLISHED` from ((`CONTENT_LANG` `C` join `USERS` `U` on((`U`.`ID` = `C`.`FK_AUTHOR`))) join `REFERENCES_D` `R` on((`R`.`CLEF` = `C`.`R_LANG`))) where (`C`.`R_LANG` <> 'FR'))  ;

-- --------------------------------------------------------

--
-- Structure de la vue `V_CONTENT_MAIN`
--
DROP TABLE IF EXISTS `V_CONTENT_MAIN`;

CREATE VIEW `V_CONTENT_MAIN`  AS   (select `C`.`ID` AS `ID`,`C`.`FK_CONTENT` AS `FK_CONTENT`,`R`.`LABEL` AS `CAT`,`U`.`NICKNAME` AS `AUTHOR`,`C`.`TITLE` AS `TITLE`,`C`.`SLUG` AS `SLUG`,`C`.`DATE_CRE` AS `DATE_CRE`,`C`.`DATE_MOD` AS `DATE_MOD`,(case when (`C`.`IS_PUBLISHED` = TRUE) then 'Publi&eacute;' else 'Non publi&eacute;' end) AS `IS_PUBLISHED` from ((`CONTENT_LANG` `C` join `USERS` `U` on((`U`.`ID` = `C`.`FK_AUTHOR`))) join `REFERENCES_D` `R` on((`R`.`CLEF` = `C`.`R_CAT`))) where (`C`.`R_LANG` = 'FR'))  ;

DELIMITER $$
--
-- Procédures
--
CREATE PROCEDURE `CHANGE_CONTENT_STATUS` (IN `p_ID` INT)   BEGIN
	DECLARE published BOOLEAN;

        SELECT IS_PUBLISHED
        INTO published
        FROM CONTENT_LANG
        WHERE ID = p_ID;
    
        IF published IS TRUE THEN
        UPDATE CONTENT_LANG
            SET IS_PUBLISHED = FALSE
            WHERE ID = p_ID;
        ELSE
            UPDATE CONTENT_LANG
            SET IS_PUBLISHED = TRUE
            WHERE ID = p_ID;
        END IF;
END$$

DELIMITER ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
