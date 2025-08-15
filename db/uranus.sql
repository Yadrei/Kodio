-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:8889
-- Généré le : mer. 13 août 2025 à 13:38
-- Version du serveur : 8.0.40
-- Version de PHP : 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

--
-- Base de données : `uranus`
--

-- --------------------------------------------------------

--
-- Structure de la table `COMMENTS`
--

CREATE TABLE `COMMENTS` (
  `ID` int NOT NULL,
  `NICKNAME` varchar(25) NOT NULL,
  `FK_CONTENT` int NOT NULL,
  `CONTENT` text NOT NULL,
  `R_STATUS` varchar(10) NOT NULL,
  `DTE` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `TOKEN` char(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `CONTENT`
--

CREATE TABLE `CONTENT` (
  `ID` int NOT NULL,
  `DATE_CRE` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `CONTENT`
--

INSERT INTO `CONTENT` (`ID`, `DATE_CRE`) VALUES
(1, '2025-02-10 17:00:28'),
(2, '2025-02-10 17:00:28');

-- --------------------------------------------------------

--
-- Structure de la table `CONTENT_H`
--

CREATE TABLE `CONTENT_H` (
  `ID` int NOT NULL,
  `ID_CONTENT` int NOT NULL,
  `ID_CONTENT_LANG` int NOT NULL,
  `R_LANG` varchar(10) NOT NULL,
  `R_CAT` varchar(10) NOT NULL,
  `TITLE` varchar(100) NOT NULL,
  `CONTENT` text,
  `META_TITLE` varchar(50) NOT NULL,
  `META_DESCRIPTION` varchar(200) NOT NULL,
  `SLUG` varchar(120) NOT NULL,
  `DTE` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `ACTION` enum('ADD','UPDATE','DELETE','PUBLISHED','UNPUBLISHED') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `CONTENT_LANG`
--

CREATE TABLE `CONTENT_LANG` (
  `ID` int NOT NULL,
  `FK_CONTENT` int NOT NULL,
  `R_LANG` varchar(10) NOT NULL,
  `R_CAT` varchar(10) NOT NULL,
  `FK_AUTHOR` int NOT NULL,
  `TITLE` varchar(100) NOT NULL,
  `CONTENT` text,
  `HEADING_IMAGE` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `META_TITLE` varchar(80) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `META_DESCRIPTION` varchar(200) NOT NULL,
  `DATE_PUBLICATION` datetime DEFAULT NULL,
  `SLUG` varchar(120) NOT NULL,
  `DATE_CRE` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `DATE_MOD` datetime DEFAULT NULL,
  `IS_PUBLISHED` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `CONTENT_LANG`
--

INSERT INTO `CONTENT_LANG` (`ID`, `FK_CONTENT`, `R_LANG`, `R_CAT`, `FK_AUTHOR`, `TITLE`, `CONTENT`, `HEADING_IMAGE`, `META_TITLE`, `META_DESCRIPTION`, `DATE_PUBLICATION`, `SLUG`, `DATE_CRE`, `DATE_MOD`, `IS_PUBLISHED`) VALUES
(1, 1, 'FR', 'SYSTEM', 1, 'Accueil site', NULL, '', 'Page d''accueil du site', 'Meta description globale', NULL, 'accueil', '2025-02-10 17:00:28', NULL, 1),
(2, 2, 'FR', 'SYSTEM', 1, 'Contact site', NULL, '', 'Page de contact du site', 'Meta description contact', NULL, 'contact', '2025-02-10 17:00:28', NULL, 1);

-- --------------------------------------------------------

--
-- Structure de la table `CONTENT_LANG_SEO`
--

CREATE TABLE `CONTENT_LANG_SEO` (
  `ID` INT PRIMARY KEY AUTO_INCREMENT NOT NULL,
  `FK_CONTENT_LANG` INT NOT NULL,
  `META_TITLE` VARCHAR(80) NOT NULL,
  `META_DESCRIPTION` VARCHAR(200) NOT NULL,
  `CANONICAL_URL` VARCHAR(255) NULL,
  `ROBOTS_INDEX` TINYINT(1) NOT NULL DEFAULT 1,   -- 1=index, 0=noindex
  `ROBOTS_FOLLOW` TINYINT(1) NOT NULL DEFAULT 1,  -- 1=follow, 0=nofollow
  `OG_TITLE` VARCHAR(100) NULL,
  `OG_DESCRIPTION` VARCHAR(200) NULL,
  `OG_IMAGE` VARCHAR(255) NULL,
  `TW_CARD` VARCHAR(20) NULL,          -- summary, summary_large_image
  `SCHEMA_TYPE` VARCHAR(50) NULL,      -- Article, WebPage, Product, etc.
  `SCHEMA_JSON` JSON NULL              -- optional: JSON-LD précompilé
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- --------------------------------------------------------

--
-- Structure de la table `J_CONTENT_TAGS`
--

CREATE TABLE `J_CONTENT_TAGS` (
  `ID` int NOT NULL,
  `FK_CONTENT` int NOT NULL,
  `FK_TAG` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `MENU`
--

CREATE TABLE `MENU` (
  `ID` int NOT NULL,
  `PARENT_ID` int DEFAULT NULL,
  `R_LANG` varchar(10) NOT NULL,
  `FK_CONTENT` int DEFAULT NULL,
  `LABEL` varchar(20) NOT NULL,
  `ORDRE` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `PERMISSIONS`
--

CREATE TABLE `PERMISSIONS` (
  `FK_USER` int NOT NULL,
  `ALLOW_ADMIN` tinyint(1) NOT NULL DEFAULT '0',
  `ALLOW_ADD` tinyint(1) NOT NULL DEFAULT '0',
  `ALLOW_UPDATE` tinyint(1) NOT NULL DEFAULT '0',
  `ALLOW_DELETE` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `PERMISSIONS`
--

INSERT INTO `PERMISSIONS` (`FK_USER`, `ALLOW_ADMIN`, `ALLOW_ADD`, `ALLOW_UPDATE`, `ALLOW_DELETE`) VALUES
(1, 1, 1, 1, 1);

-- --------------------------------------------------------

--
-- Structure de la table `REFERENCES_D`
--

CREATE TABLE `REFERENCES_D` (
  `CLEF` varchar(10) NOT NULL,
  `FK_REF` varchar(10) NOT NULL,
  `LABEL` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `REFERENCES_D`
--

INSERT INTO `REFERENCES_D` (`CLEF`, `FK_REF`, `LABEL`) VALUES
('ADMIN', 'R_ROLE', 'Administrateur'),
('APPROVED', 'R_STATUS', 'Approuvé'),
('COM', 'R_SETTING', 'Activer les commentaires'),
('COOKIES', 'R_SETTING', 'Activer les cookies'),
('FR', 'R_LANG', 'Français'),
('MAINT', 'R_SETTING', 'Mode maintenance'),
('MENU', 'R_CAT', 'Page liée au menu'),
('PAGE', 'R_CAT', 'Page indépendante'),
('PENDING', 'R_STATUS', 'En attente de validation'),
('SOC_FB', 'R_SETTING', 'Facebook'),
('SOC_INST', 'R_SETTING', 'Instagram'),
('SOC_TWT', 'R_SETTING', 'Twitter'),
('SYSTEM', 'R_CAT', 'Système'),
('WEB', 'R_ROLE', 'Webmaster');

-- --------------------------------------------------------

--
-- Structure de la table `REFERENCES_T`
--

CREATE TABLE `REFERENCES_T` (
  `REF` varchar(10) NOT NULL,
  `LABEL` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `REFERENCES_T`
--

INSERT INTO `REFERENCES_T` (`REF`, `LABEL`) VALUES
('R_CAT', 'Catégories de contenu'),
('R_LANG', 'Langues disponibles'),
('R_ROLE', 'Rôles pour les utilisateurs'),
('R_SETTING', 'Paramètres'),
('R_STATUS', 'Statuts possibles');

-- --------------------------------------------------------

--
-- Structure de la table `SETTINGS`
--

CREATE TABLE `SETTINGS` (
  `ID` int NOT NULL,
  `R_SETTING` varchar(10) NOT NULL,
  `VALUE` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `SETTINGS`
--

INSERT INTO `SETTINGS` (`ID`, `R_SETTING`, `VALUE`) VALUES
(1, 'MAINT', ''),
(2, 'COM', ''),
(3, 'COOKIES', '1'),
(4, 'SOC_FB', 'https://www.facebook.com/'),
(5, 'SOC_INST', 'https://www.instagram.com/'),
(6, 'SOC_TWT', '');

-- --------------------------------------------------------

--
-- Structure de la table `TAGS`
--

CREATE TABLE `TAGS` (
  `ID` int NOT NULL,
  `LABEL` varchar(20) NOT NULL,
  `TXT_COLOR` varchar(7) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `BG_COLOR` varchar(7) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `USERS`
--

CREATE TABLE `USERS` (
  `ID` int NOT NULL,
  `NICKNAME` varchar(20) NOT NULL,
  `EMAIL` varchar(100) NOT NULL,
  `PASSWORD_HASH` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `R_ROLE` varchar(10) NOT NULL,
  `WEB_ONLY` tinyint(1) GENERATED ALWAYS AS ((CASE WHEN (`R_ROLE` = 'WEB') THEN 1 ELSE NULL END)) STORED,
  `DATE_CRE` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `USERS`
--

INSERT INTO `USERS` (`ID`, `NICKNAME`, `EMAIL`, `PASSWORD_HASH`, `R_ROLE`, `DATE_CRE`) VALUES
(1, 'Yves', 'yves.ponchelet@shoku.be', '$2y$10$ftpM1Y4d0djxnz0Q.b0NbOgfDpyYnGHM6Z//rD/b7Vpog38Hha0wS', 'WEB', '2023-10-22 19:21:19');

-- --------------------------------------------------------

--
-- Doublure de structure pour la vue `v_content_lang`
-- (Voir ci-dessous la vue réelle)
--
CREATE TABLE `V_CONTENT_LANG` (
`AUTHOR` varchar(20)
,`DATE_CRE` datetime
,`DATE_MOD` datetime
,`FK_CONTENT` int
,`ID` int
,`IS_PUBLISHED` varchar(17)
,`LANGUAGE` varchar(50)
,`SLUG` varchar(120)
,`TITLE` varchar(100)
);

-- --------------------------------------------------------

--
-- Doublure de structure pour la vue `v_content_main`
-- (Voir ci-dessous la vue réelle)
--
CREATE TABLE `V_CONTENT_MAIN` (
`AUTHOR` varchar(20)
,`CAT` varchar(50)
,`DATE_CRE` datetime
,`DATE_MOD` datetime
,`FK_CONTENT` int
,`ID` int
,`IS_PUBLISHED` varchar(17)
,`SLUG` varchar(120)
,`TITLE` varchar(100)
);

-- --------------------------------------------------------

--
-- Structure de la vue `v_content_lang`
--
DROP TABLE IF EXISTS `V_CONTENT_LANG`;

CREATE VIEW `V_CONTENT_LANG`  AS SELECT `C`.`ID` AS `ID`, `C`.`FK_CONTENT` AS `FK_CONTENT`, `R`.`LABEL` AS `LANGUAGE`, `U`.`NICKNAME` AS `AUTHOR`, `C`.`TITLE` AS `TITLE`, `C`.`SLUG` AS `SLUG`, `C`.`DATE_CRE` AS `DATE_CRE`, `C`.`DATE_MOD` AS `DATE_MOD`, (case when (`C`.`IS_PUBLISHED` = true) then TRUE else FALSE end) AS `IS_PUBLISHED` FROM ((`CONTENT_LANG` `C` join `USERS` `U` on((`U`.`ID` = `C`.`FK_AUTHOR`))) join `REFERENCES_D` `R` on((`R`.`CLEF` = `C`.`R_LANG`))) WHERE (`C`.`R_LANG` <> 'FR') ;

-- --------------------------------------------------------

--
-- Structure de la vue `v_content_main`
--
DROP TABLE IF EXISTS `V_CONTENT_MAIN`;

CREATE VIEW `V_CONTENT_MAIN`  AS SELECT `C`.`ID` AS `ID`, `C`.`FK_CONTENT` AS `FK_CONTENT`, `R`.`LABEL` AS `CAT`, `U`.`NICKNAME` AS `AUTHOR`, `C`.`TITLE` AS `TITLE`, `C`.`SLUG` AS `SLUG`, `C`.`DATE_CRE` AS `DATE_CRE`, `C`.`DATE_MOD` AS `DATE_MOD`, (case when (`C`.`IS_PUBLISHED` = true) then TRUE else FALSE end) AS `IS_PUBLISHED` FROM ((`CONTENT_LANG` `C` join `USERS` `U` on((`U`.`ID` = `C`.`FK_AUTHOR`))) join `REFERENCES_D` `R` on((`R`.`CLEF` = `C`.`R_CAT`))) WHERE (`C`.`R_LANG` = 'FR') ;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `COMMENTS`
--
ALTER TABLE `COMMENTS`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `UQ_TOKEN` (`TOKEN`),
  ADD KEY `CONST_COMMENT_STATUS` (`R_STATUS`),
  ADD KEY `IDX_FK_CONTENT` (`FK_CONTENT`,`DTE`);

--
-- Index pour la table `CONTENT`
--
ALTER TABLE `CONTENT`
  ADD PRIMARY KEY (`ID`);

--
-- Index pour la table `CONTENT_H`
--
ALTER TABLE `CONTENT_H`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `IDX_H_ID_CONTENT` (`ID_CONTENT`),
  ADD KEY `IDX_H_LANG` (`ID_CONTENT_LANG`),
  ADD KEY `IDX_H_DTE` (`DTE`),
  ADD KEY `IDX_H_ACTION` (`ACTION`);

--
-- Index pour la table `CONTENT_LANG`
--
ALTER TABLE `CONTENT_LANG`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `UQ_CONTENT_LANG` (`FK_CONTENT`,`R_LANG`),
  ADD UNIQUE KEY `UQ_SLUG_LANG` (`R_LANG`,`SLUG`),
  ADD KEY `CONST_MAIN_CONTENT` (`FK_CONTENT`),
  ADD KEY `CONST_CONTENT_LANG` (`R_LANG`),
  ADD KEY `CONST_CONTENT_CATEGORY` (`R_CAT`),
  ADD KEY `CONST_CONTENT_AUTHOR` (`FK_AUTHOR`),
  ADD KEY `IDX_SLUG` (`SLUG`);

--
-- Index pour la table `CONTENT_LANG_SEO`
--
ALTER TABLE `CONTENT_LANG_SEO`
  UNIQUE KEY UQ_SEO (FK_CONTENT_LANG),
  CONSTRAINT CONST_SEO_CONTENT_LANG FOREIGN KEY (FK_CONTENT_LANG) REFERENCES CONTENT_LANG(ID)

--
-- Index pour la table `J_CONTENT_TAGS`
--
ALTER TABLE `J_CONTENT_TAGS`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `CONST_J_FK_CONTENT` (`FK_CONTENT`),
  ADD KEY `CONST_J_FK_TAG` (`FK_TAG`);

--
-- Index pour la table `MENU`
--
ALTER TABLE `MENU`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `CONST_PARENT_ID` (`PARENT_ID`),
  ADD KEY `CONST_MENU_LANGUAGE` (`R_LANG`);

--
-- Index pour la table `PERMISSIONS`
--
ALTER TABLE `PERMISSIONS`
  ADD PRIMARY KEY (`FK_USER`),
  ADD KEY `CONST_PERMISSIONS_USER` (`FK_USER`);

--
-- Index pour la table `REFERENCES_D`
--
ALTER TABLE `REFERENCES_D`
  ADD PRIMARY KEY (`CLEF`) USING BTREE,
  ADD KEY `CONST_FK_REF_T` (`FK_REF`),
  ADD UNIQUE KEY `UQ_REF_LABEL_PER_TYPE` (`FK_REF`, `LABEL`);


--
-- Index pour la table `REFERENCES_T`
--
ALTER TABLE `REFERENCES_T`
  ADD PRIMARY KEY (`REF`);

--
-- Index pour la table `SETTINGS`
--
ALTER TABLE `SETTINGS`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `UQ_SETTING` (`R_SETTING`),
  ADD KEY `CONST_SETTING` (`R_SETTING`);

--
-- Index pour la table `TAGS`
--
ALTER TABLE `TAGS`
  ADD PRIMARY KEY (`ID`);

--
-- Index pour la table `USERS`
--
ALTER TABLE `USERS`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `UQ_EMAIL` (`EMAIL`) USING BTREE,
  ADD UNIQUE KEY `UQ_NICKNAME` (`NICKNAME`),
  ADD UNIQUE KEY `UQ_SINGLE_WEB` (`WEB_ONLY`),
  ADD KEY `CONST_USER_ROLE` (`R_ROLE`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `COMMENTS`
--
ALTER TABLE `COMMENTS`
  MODIFY `ID` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `CONTENT`
--
ALTER TABLE `CONTENT`
  MODIFY `ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `CONTENT_H`
--
ALTER TABLE `CONTENT_H`
  MODIFY `ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT pour la table `CONTENT_LANG`
--
ALTER TABLE `CONTENT_LANG`
  MODIFY `ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT pour la table `J_CONTENT_TAGS`
--
ALTER TABLE `J_CONTENT_TAGS`
  MODIFY `ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `MENU`
--
ALTER TABLE `MENU`
  MODIFY `ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `SETTINGS`
--
ALTER TABLE `SETTINGS`
  MODIFY `ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `TAGS`
--
ALTER TABLE `TAGS`
  MODIFY `ID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `USERS`
--
ALTER TABLE `USERS`
  MODIFY `ID` int NOT NULL AUTO_INCREMENT;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `COMMENTS`
--
ALTER TABLE `COMMENTS`
  ADD CONSTRAINT `CONST_COMMENT_CONTENT` FOREIGN KEY (`FK_CONTENT`) REFERENCES `CONTENT_LANG` (`ID`) ON DELETE CASCADE,
  ADD CONSTRAINT `CONST_COMMENT_STATUS` FOREIGN KEY (`R_STATUS`) REFERENCES `REFERENCES_D` (`CLEF`) ON DELETE RESTRICT ON UPDATE RESTRICT;

--
-- Contraintes pour la table `CONTENT_LANG`
--
ALTER TABLE `CONTENT_LANG`
  ADD CONSTRAINT `CONST_CONTENT_AUTHOR` FOREIGN KEY (`FK_AUTHOR`) REFERENCES `USERS` (`ID`),
  ADD CONSTRAINT `CONST_CONTENT_CATEGORY` FOREIGN KEY (`R_CAT`) REFERENCES `REFERENCES_D` (`CLEF`),
  ADD CONSTRAINT `CONST_CONTENT_LANG` FOREIGN KEY (`R_LANG`) REFERENCES `REFERENCES_D` (`CLEF`),
  ADD CONSTRAINT `CONST_MAIN_CONTENT` FOREIGN KEY (`FK_CONTENT`) REFERENCES `CONTENT` (`ID`);

--
-- Contraintes pour la table `J_CONTENT_TAGS`
--
ALTER TABLE `J_CONTENT_TAGS`
  ADD CONSTRAINT `CONST_J_FK_CONTENT` FOREIGN KEY (`FK_CONTENT`) REFERENCES `CONTENT` (`ID`) ON DELETE CASCADE ON UPDATE RESTRICT,
  ADD CONSTRAINT `CONST_J_FK_TAG` FOREIGN KEY (`FK_TAG`) REFERENCES `TAGS` (`ID`) ON DELETE CASCADE;

--
-- Contraintes pour la table `MENU`
--
ALTER TABLE `MENU`
  ADD CONSTRAINT `CONST_MENU_LANGUAGE` FOREIGN KEY (`R_LANG`) REFERENCES `REFERENCES_D` (`CLEF`),
  ADD CONSTRAINT `CONST_PARENT_ID` FOREIGN KEY (`PARENT_ID`) REFERENCES `MENU` (`ID`) ON DELETE SET NULL,
  ADD CONSTRAINT `CONST_MENU_CONTENT_LANG` FOREIGN KEY (`FK_CONTENT`) REFERENCES `CONTENT_LANG`(`ID`) ON DELETE SET NULL;


--
-- Contraintes pour la table `PERMISSIONS`
--
ALTER TABLE `PERMISSIONS`
  ADD CONSTRAINT `CONST_PERMISSIONS_USER` FOREIGN KEY (`FK_USER`) REFERENCES `USERS` (`ID`) ON DELETE CASCADE ON UPDATE RESTRICT;

--
-- Contraintes pour la table `REFERENCES_D`
--
ALTER TABLE `REFERENCES_D`
  ADD CONSTRAINT `CONST_FK_REF_T` FOREIGN KEY (`FK_REF`) REFERENCES `REFERENCES_T` (`REF`);

--
-- Contraintes pour la table `SETTINGS`
--
ALTER TABLE `SETTINGS`
  ADD CONSTRAINT `CONST_SETTING` FOREIGN KEY (`R_SETTING`) REFERENCES `REFERENCES_D` (`CLEF`);

--
-- Contraintes pour la table `USERS`
--
ALTER TABLE `USERS`
  ADD CONSTRAINT `CONST_USER_ROLE` FOREIGN KEY (`R_ROLE`) REFERENCES `REFERENCES_D` (`CLEF`),
  ADD CONSTRAINT `CHECK_WEB_BELONGS_TO_YVES`
  CHECK (`R_ROLE` <> 'WEB' OR UPPER(`NICKNAME`) = 'YVES');
