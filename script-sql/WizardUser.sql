-- phpMyAdmin SQL Dump
-- version 4.4.0
-- http://www.phpmyadmin.net
--
-- Client :  localhost:8889
-- Généré le :  Mer 06 Juillet 2016 à 22:15
-- Version du serveur :  5.5.38
-- Version de PHP :  5.5.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Base de données :  `wizardalley`
--

--
-- Contenu de la table `wizard_user`
--

INSERT INTO `wizard_user` (`id`, `username`, `username_canonical`, `email`, `email_canonical`, `enabled`, `salt`, `password`, `last_login`, `locked`, `expired`, `expires_at`, `confirmation_token`, `password_requested_at`, `roles`, `credentials_expired`, `credentials_expire_at`, `lastname`, `firstname`, `path_profile`, `path_couverture`, `twitter`, `facebook`, `sexe`) VALUES
(2, 'admin', 'admin', 'yunai39@gmail.com', 'yunai39@gmail.com', 1, 'tlfxbhjrrlwkog8s40c4o8occgg0kwg', 'jlxj+OHfQHuTv5KZwRYLnmuak1tKdKFlLp4t+U9MU9Do4LkgBGujsOGTd956/mlw8+5e/StwyDvAyUYIDOlHOg==', '2016-07-06 21:36:07', 0, 0, NULL, NULL, NULL, 'a:1:{i:0;s:10:"ROLE_ADMIN";}', 0, NULL, 'admin', 'AA', 'profile.jpg', 'couverture.jpg', 'm', 'j', 1),
(3, 'Toto', 'toto', 'toto@totolan.com', 'toto@totolan.com', 1, '58ohmoy3piscooksc08c884gcc0wo8g', 'MpjquOPsK7SKfm07YCVHyGPpvemo89Gleb1MTS0M5DcsoN/M+IZ4bk/MXYhG11V/S9nut+2kSlwlU5yXwRqVeA==', '2016-06-25 21:23:22', 0, 0, NULL, NULL, NULL, 'a:0:{}', 0, NULL, 'toto', 'toto', 'profile.png', NULL, '', '', 0),
(4, 'admin2', 'admin2', 'admin2@admin.com', 'admin2@admin.com', 1, 'tlfxbhjrrlwkog8s40c4o8occgg0kwg', 'jlxj+OHfQHuTv5KZwRYLnmuak1tKdKFlLp4t+U9MU9Do4LkgBGujsOGTd956/mlw8+5e/StwyDvAyUYIDOlHOg==', '2015-09-26 17:01:34', 0, 0, NULL, NULL, NULL, 'a:1:{i:0;s:10:"ROLE_ADMIN";}', 0, NULL, 'admin', 'AA', NULL, NULL, '', '', 0),
(5, 'Toto2', 'toto2', 'toto2@totolan.com', 'toto2@totolan.com', 1, '58ohmoy3piscooksc08c884gcc0wo8g', 'MpjquOPsK7SKfm07YCVHyGPpvemo89Gleb1MTS0M5DcsoN/M+IZ4bk/MXYhG11V/S9nut+2kSlwlU5yXwRqVeA==', '2015-07-30 20:40:18', 0, 0, NULL, NULL, NULL, 'a:0:{}', 0, NULL, 'toto', 'toto', NULL, NULL, '', '', 0),
(8, 'Toto3', 'toto3', 'toto3@totolan.com', 'toto3@totolan.com', 1, '58ohmoy3piscooksc08c884gcc0wo8g', 'MpjquOPsK7SKfm07YCVHyGPpvemo89Gleb1MTS0M5DcsoN/M+IZ4bk/MXYhG11V/S9nut+2kSlwlU5yXwRqVeA==', '2015-09-30 22:16:17', 0, 0, NULL, NULL, NULL, 'a:0:{}', 0, NULL, 'toto', 'toto', NULL, NULL, '', '', 0),
(9, 'Toto4', 'Toto4', 'Toto4@totolan.com', 'Toto4@totolan.com', 1, '58ohmoy3piscooksc08c884gcc0wo8g', 'MpjquOPsK7SKfm07YCVHyGPpvemo89Gleb1MTS0M5DcsoN/M+IZ4bk/MXYhG11V/S9nut+2kSlwlU5yXwRqVeA==', '2015-07-30 20:40:18', 0, 0, NULL, NULL, NULL, 'a:0:{}', 0, NULL, 'toto', 'toto', NULL, NULL, '', '', 0),
(10, 'qwerty', 'qwerty', 'qwerty@hew.com', 'qwerty@hew.com', 1, 'g1znxwrgvk848ok8g4csk08kgwkkgco', 'B12xG/x4FRtZgANcW+YwgluBAB29yxcV5daMisaQdbbAtXE6DNyANpMf/20zF+aKLi0oiyh6yDm4SHpves/Rog==', '2015-08-13 22:16:02', 0, 0, NULL, NULL, NULL, 'a:0:{}', 0, NULL, 'qwerty', 'qwerty', NULL, NULL, NULL, NULL, 0);
