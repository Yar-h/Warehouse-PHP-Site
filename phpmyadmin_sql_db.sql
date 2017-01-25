-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Хост: 127.0.0.1
-- Время создания: Ноя 22 2015 г., 23:24
-- Версия сервера: 5.5.25
-- Версия PHP: 5.2.12

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- База данных: `warehousedb`
--

-- --------------------------------------------------------

--
-- Структура таблицы `materials`
--

CREATE TABLE IF NOT EXISTS `materials` (
  `id` smallint(6) NOT NULL AUTO_INCREMENT,
  `title` varchar(31) DEFAULT NULL,
  `img_path` varchar(64) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Дамп данных таблицы `materials`
--

INSERT INTO `materials` (`id`, `title`, `img_path`) VALUES
(1, 'Wood', '/MyTravelNotes/photo/wood.jpg'),
(6, 'Iron', '/MyTravelNotes/photo/iron.jpg');

-- --------------------------------------------------------

--
-- Структура таблицы `materials_main`
--

CREATE TABLE IF NOT EXISTS `materials_main` (
  `id` smallint(6) NOT NULL AUTO_INCREMENT,
  `id_material` smallint(6) NOT NULL,
  `id_supplier` smallint(6) NOT NULL,
  `income_date` date NOT NULL,
  `id_waybill` int(11) NOT NULL,
  `id_warehouse` smallint(6) NOT NULL,
  `MR_employee` varchar(32) NOT NULL,
  `count` int(11) NOT NULL,
  `metrics` varchar(16) NOT NULL,
  `price` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_supplier` (`id_supplier`),
  KEY `id` (`id_material`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Дамп данных таблицы `materials_main`
--

INSERT INTO `materials_main` (`id`, `id_material`, `id_supplier`, `income_date`, `id_waybill`, `id_warehouse`, `MR_employee`, `count`, `metrics`, `price`) VALUES
(1, 1, 1, '2015-10-29', 2147483647, 12, 'Petrov Petro Petrovuch', 200, 'square meters', 100),
(2, 6, 1, '2015-10-19', 1, 12, '1', 1, '1', 50),
(6, 6, 3, '2015-11-22', 222222222, 2222, 'Lena', 69, 'kg', 200);

-- --------------------------------------------------------

--
-- Структура таблицы `suppliers`
--

CREATE TABLE IF NOT EXISTS `suppliers` (
  `id` smallint(6) NOT NULL AUTO_INCREMENT,
  `name` varchar(31) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Дамп данных таблицы `suppliers`
--

INSERT INTO `suppliers` (`id`, `name`) VALUES
(1, 'Ivanov Ivan Ivanovich'),
(3, 'Andrej');

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` smallint(6) NOT NULL AUTO_INCREMENT,
  `login` varchar(20) NOT NULL,
  `password` varchar(20) NOT NULL,
  `rights` varchar(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`id`, `login`, `password`, `rights`) VALUES
(1, 'admin', 'admin', 'a'),
(2, 'legioner', '03031996', 'u'),
(4, 'lena', 'lena', 'u');

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `materials_main`
--
ALTER TABLE `materials_main`
  ADD CONSTRAINT `materials_main_ibfk_2` FOREIGN KEY (`id_supplier`) REFERENCES `suppliers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `materials_main_ibfk_3` FOREIGN KEY (`id_material`) REFERENCES `materials` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
