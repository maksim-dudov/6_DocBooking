-- phpMyAdmin SQL Dump
-- version 4.2.11
-- http://www.phpmyadmin.net
--
-- Хост: 127.0.0.1
-- Время создания: Июн 09 2018 г., 12:51
-- Версия сервера: 5.6.21
-- Версия PHP: 5.6.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- База данных: `myself_6`
--

-- --------------------------------------------------------

--
-- Структура таблицы `app_types`
--

DROP TABLE IF EXISTS `app_types`;
CREATE TABLE IF NOT EXISTS `app_types` (
`id` int(10) unsigned NOT NULL,
  `title` tinytext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `clients`
--

DROP TABLE IF EXISTS `clients`;
CREATE TABLE IF NOT EXISTS `clients` (
`id` int(11) NOT NULL,
  `fio` tinytext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `dashboard`
--

DROP TABLE IF EXISTS `dashboard`;
CREATE TABLE IF NOT EXISTS `dashboard` (
`id` int(10) unsigned NOT NULL,
  `doctor_id` int(10) unsigned NOT NULL,
  `datetime` datetime NOT NULL,
  `duration` int(10) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `doctors`
--

DROP TABLE IF EXISTS `doctors`;
CREATE TABLE IF NOT EXISTS `doctors` (
`id` int(10) unsigned NOT NULL,
  `fio` tinytext NOT NULL,
  `speciality_id` int(10) unsigned NOT NULL,
  `deleted` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `lnk_app_doctors`
--

DROP TABLE IF EXISTS `lnk_app_doctors`;
CREATE TABLE IF NOT EXISTS `lnk_app_doctors` (
`id` int(10) unsigned NOT NULL,
  `doctor_id` int(10) unsigned NOT NULL,
  `app_id` int(10) unsigned NOT NULL,
  `duration` tinyint(3) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `orders`
--

DROP TABLE IF EXISTS `orders`;
CREATE TABLE IF NOT EXISTS `orders` (
`id` int(10) unsigned NOT NULL,
  `client_id` int(10) unsigned NOT NULL,
  `doctor_id` int(10) unsigned NOT NULL,
  `datetime_app` datetime NOT NULL,
  `datetime_creation` datetime NOT NULL,
  `datetime_cancellation` datetime NOT NULL,
  `duration` tinyint(3) unsigned NOT NULL,
  `app_type_id` int(10) unsigned NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `app_types`
--
ALTER TABLE `app_types`
 ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `clients`
--
ALTER TABLE `clients`
 ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `dashboard`
--
ALTER TABLE `dashboard`
 ADD PRIMARY KEY (`id`), ADD KEY `doctor_id` (`doctor_id`), ADD KEY `duration` (`duration`);

--
-- Индексы таблицы `doctors`
--
ALTER TABLE `doctors`
 ADD PRIMARY KEY (`id`), ADD KEY `speciality_id` (`speciality_id`);

--
-- Индексы таблицы `lnk_app_doctors`
--
ALTER TABLE `lnk_app_doctors`
 ADD PRIMARY KEY (`id`), ADD KEY `doctor_id` (`doctor_id`), ADD KEY `app_id` (`app_id`);

--
-- Индексы таблицы `orders`
--
ALTER TABLE `orders`
 ADD PRIMARY KEY (`id`), ADD KEY `client_id` (`client_id`), ADD KEY `doctor_id` (`doctor_id`), ADD KEY `app_type_id` (`app_type_id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `app_types`
--
ALTER TABLE `app_types`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `clients`
--
ALTER TABLE `clients`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `dashboard`
--
ALTER TABLE `dashboard`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `doctors`
--
ALTER TABLE `doctors`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `lnk_app_doctors`
--
ALTER TABLE `lnk_app_doctors`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT для таблицы `orders`
--
ALTER TABLE `orders`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT;

ALTER TABLE `orders` ADD `cancelled` BOOLEAN NOT NULL , ADD INDEX (`cancelled`) ;