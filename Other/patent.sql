-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Nov 15, 2014 at 08:18 PM
-- Server version: 5.6.17
-- PHP Version: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `patent`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `CheckInventions`(IN `p_name` VARCHAR(50), IN `p_description` TEXT, OUT `p_out` INT)
BEGIN
 SELECT COUNT(id)
 INTO p_out
 FROM `inventions`
 WHERE name = p_name OR description = p_description;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `inventions`
--

CREATE TABLE IF NOT EXISTS `inventions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `description` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `photo` varchar(500) DEFAULT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `author_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  KEY `fk_author_id` (`author_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=14 ;

--
-- Dumping data for table `inventions`
--

INSERT INTO `inventions` (`id`, `name`, `description`, `photo`, `date`, `author_id`) VALUES
(1, 'Дрон Prox Dynamics PD-100 Black Hornet', 'Этот миниатюрный летательный аппарат имеет собственную операционную систему и может держаться в воздухе до 25 минут. Причем дрон спокойно выдерживает сильные порывы ветра и выдерживает «марш-броски» до пары километров. \r\n\r\nАвтопилот дрона снабжен GPS-приемником и способен патрулировать территорию по указанным координатам. А весит аппарат всего 17 граммов.', 'http://bm.img.com.ua/berlin/storage/orig/d/59/f0e761a0830130b64420c31a4767f59d.jpg', '2014-11-04 09:45:29', 2),
(2, 'Kratos Defense And Security Solutions Laser Weapon', 'Настоящее лазерное оружие. Снабженная лазерным дальномером система Кратос стреляет направленным пучком энергии, выводя из строя электронику. В отличие от обычного оружия, лазер поражает цель практически мгновенно, а стоимость одного выстрела не превышает $1. \r\n\r\nПосле шести лет разработки система Кратос поступит на вооружение в 2014 году.', 'http://bm.img.com.ua/berlin/storage/orig/d/29/2b385b143c22abebde28a4731641729d.jpg', '2014-11-04 09:45:43', 3),
(3, 'Spaceship Two', 'Частный космический корабль Spaceship Two, созданный компанией миллиардера Ричарда Брэнсона Virgin Galactic, впервые превысил скорость звука. ', 'http://bm.img.com.ua/berlin/storage/orig/7/14/e291d4385b25cf63c4afb47df1b3c147.jpg', '2014-11-04 09:45:53', 4),
(4, 'BAE Systems Taranis', 'Этот аппарат развивает скорость до 1126 км/ч, т.е. практически до скоростей реактивных самолетов.\r\n\r\nДрон может совершать трансконтинентальные перелеты, а также вести бой воздух-земля.', 'http://bm.img.com.ua/berlin/storage/orig/e/99/1d895de08d39df1798528e3ac883599e.jpg', '2014-11-04 09:46:12', 4),
(5, 'NASA Chamber A', 'Вакуумная камера NASA, которую модернизировали для испытания телескопа Джеймса Вебба. Камера имеет объем 121 тыс куб м., при этом соблюдая условия «чистой комнаты».\r\n\r\nТакже в этой камере можно достичь температуры 11° Кельвина, что делает ее самым холодным местом на Земле.', 'http://bm.img.com.ua/berlin/storage/orig/9/24/eb53500c904400360c1bacfd4355a249.jpg', '2014-11-04 09:46:23', 5),
(6, 'DARPA Phoenix', 'Если какая-то часть спутника выходит из строя, погибает весь спутник. Чтобы избежать подобного, был создан проект DARPA Phoenix, который соединяет в себе мини-спутники с возможностью развертывания нового блока.', 'http://bm.img.com.ua/berlin/storage/orig/a/50/6940fc96a0b30295319aea044ae2d50a.jpg', '2014-11-04 09:46:37', 8),
(7, 'X-51 WaveRider', '1 мая был испытан 7,5 метровый макет ракеты весом 181 кг, которая в будущем станет новым видом транспорта — гиперзвуковой ракетой с прямоточным двигателем. Первый полет ракеты на кислороде и без подвижных частей длился три минуты и в 5 раз превысил скорость звука.', 'http://bm.img.com.ua/berlin/storage/orig/0/55/ba202d480e70656fb62f5d506810f550.jpg', '2014-11-04 09:46:47', 5),
(8, 'Aeros Aeroscraft', '121-метровый дирижабль, который разработал наш бывший соотечественник, может поднимать до 66 тонн груза. Кроме того, ему не обязательно приземляться, чтобы спустить груз, что позволяет его использовать в труднодоступных для другого транспорта местах.', 'http://bm.img.com.ua/berlin/storage/orig/7/61/4c52eb50d9ca7939ca41cdfa427c1617.jpg', '2014-11-04 09:47:03', 1),
(9, 'Martin Aircraft Company P12 Jetpack ', 'Реактивный ранец уже стал реальностью. Управляемый компьютером ранец позволяет взлететь на высоту до 2,5 км со скоростью 100 км/ч. \r\n\r\nP12 — первый персональный ранец, который позволит летать без специальной подготовки обычному человеку.', 'http://bm.img.com.ua/berlin/storage/orig/8/23/8f6a38b5ecc9177b29a58e96d4e3d238.jpg', '2014-11-04 09:47:12', 7),
(10, 'Gaia', 'Этот двухтонный спутник — настоящая обсерватория с мощнейшими цифровыми камерами, которые еще никогда не выходили в космос. Матрицы камеры содержит миллиард пикселей, а два телескопа на спутнике настолько мощные, что на одном снимке можно запечатлеть миллиарды звезд.\r\n\r\nЗапуск спутника намечен на 20 ноября с французской Гвианы. Доставит на орбиту обсерваторию российская ракета Союз.', 'http://bm.img.com.ua/berlin/storage/orig/7/00/0226b53fac1aa5c310a4df8c12f0e007.jpg', '2014-11-04 09:47:22', 2),
(11, 'Fairphone', 'Телефон, разработанный амстердамской компанией Fairphone, создан из материалов, которые при распаде не вредят окружающей среде. Правда, характеристики смартфона не самые крутые: 16 Гб памяти, 4-ядерный процессор, 8 Мп камера.\r\n\r\nПродается телефон только в Европе. Цена — $440.', 'http://bm.img.com.ua/berlin/storage/orig/2/fc/a05d5ea99c0c912b8e5af29be7061fc2.jpg', '2014-11-04 09:47:31', 6),
(12, 'Ecovative Mushroom Insulation', 'Легкий и пористый материал получают как побочный эффект жизнедеятельности грибницы на кукурузных стеблях. В итоге материал имеет 3,8 класс огнестойкости, а после демонтажа стен с подобным утеплителем может быть переработан в компост.\r\n\r\nЦена — $0.90 за квадратный метр.', 'http://bm.img.com.ua/berlin/storage/orig/2/55/e8ef46af736a024b8376f083fa3ae552.jpg', '2014-11-04 09:47:39', 5),
(13, 'ORPC TidGen Power System', 'Электростанция, получающая энергию от силы прилива. Такой же агрегат можно установить на дне реки, где течение будет вращать лопасти, генерируя около 150 кВт. \r\n\r\nПервый блок TidGen был установлен у побережья штата Мэн в прошлом году. В марте было установлено, что никакого вреда на экосистему моря установка не оказывает.', 'http://bm.img.com.ua/berlin/storage/orig/9/3b/3d62aa21ae94e51a88653ebc827353b9.jpg', '2014-11-04 09:47:49', 8);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(25) NOT NULL,
  `password` varchar(50) NOT NULL,
  `fname` varchar(25) CHARACTER SET cp1251 NOT NULL,
  `lname` varchar(25) CHARACTER SET cp1251 NOT NULL,
  `patronymic` varchar(25) CHARACTER SET cp1251 DEFAULT NULL,
  `email` varchar(25) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `login` (`login`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=10 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `login`, `password`, `fname`, `lname`, `patronymic`, `email`) VALUES
(1, 'admin', '21232f297a57a5a743894a0e4a801fc3', 'Админский', 'Админ', 'Админович', 'admin@mail.ru'),
(2, 'user1', 'ee11cbb19052e40b07aac0ca060c23ee', 'Харитонов', 'Евгений', 'Александрович', 'user1@mail.ru'),
(3, 'user2', 'ee11cbb19052e40b07aac0ca060c23ee', 'Макеев', 'Иван', '', 'user2@mail.ru'),
(4, 'user3', 'ee11cbb19052e40b07aac0ca060c23ee', 'Нестеров', 'Александр', '', 'user3@mail.ru'),
(5, 'user4', 'ee11cbb19052e40b07aac0ca060c23ee', 'Сорокин', 'Станислав', '', 'user4@mail.ru'),
(6, 'user5', 'ee11cbb19052e40b07aac0ca060c23ee', 'Малафеева', 'Анна', '', 'user5@mail.ru'),
(7, 'user6', 'ee11cbb19052e40b07aac0ca060c23ee', 'Асеев', 'Михаил', '', 'user6@mail.ru'),
(8, 'user7', 'ee11cbb19052e40b07aac0ca060c23ee', 'Белитова', 'Полина', '', 'user7@mail.ru'),
(9, 'user8', 'ee11cbb19052e40b07aac0ca060c23ee', 'Романов', 'Илюха', '', 'user8@gmail.ru');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `inventions`
--
ALTER TABLE `inventions`
  ADD CONSTRAINT `fk_author_id` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
