-- phpMyAdmin SQL Dump
-- version 5.1.3
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 01-06-2022 a las 17:02:19
-- Versión del servidor: 10.4.24-MariaDB
-- Versión de PHP: 7.4.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `moni_dev`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ev_ponencias`
--

CREATE TABLE `ev_ponencias` (
  `id_ponencia` int(11) NOT NULL,
  `id_evento` int(11) DEFAULT NULL,
  `nombre` varchar(150) NOT NULL,
  `clave` varchar(80) NOT NULL,
  `evento_privado` smallint(6) NOT NULL DEFAULT 2 COMMENT '1:si 2:no',
  `cupo` int(11) NOT NULL,
  `salon` varchar(80) NOT NULL,
  `duracionTotal` int(11) DEFAULT NULL,
  `fecha` datetime DEFAULT NULL,
  `costo` float NOT NULL,
  `nombre_ponente` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Volcado de datos para la tabla `ev_ponencias`
--

INSERT INTO `ev_ponencias` (`id_ponencia`, `id_evento`, `nombre`, `clave`, `evento_privado`, `cupo`, `salon`, `duracionTotal`, `fecha`, `costo`, `nombre_ponente`) VALUES
(1, 2, 'Ponencia Ejemplo 1', '', 2, 50, 'Puebla', 3, '2022-06-01 11:05:00', 0, NULL),
(2, 2, 'Ponencia Ejemplo 2', '', 2, 50, 'Veracruz', 3, '2022-06-03 19:15:05', 0, NULL),
(3, 2, 'Ponencia Ejemplo 3', '', 2, 50, 'Oaxaca', 3, '2022-06-01 13:20:00', 0, 'ponente 4'),
(4, 2, 'Ponencia Ejemplo 4.', '', 2, 50, 'Extranjero', 3, '2022-06-01 00:00:00', 0, 'ponente 1'),
(5, 2, 'Ponencia ejemplo 4', '', 2, 50, 'Mexico', 3, '2022-06-01 13:13:02', 0, 'ponente 2'),
(6, 1, 'Ponencia ejemplo 5', '', 2, 50, 'Las vegas', 3, '2022-06-01 13:13:20', 0, NULL),
(7, 2, 'Ponencia ejemplo 6', '', 2, 50, 'Example6', 3, '2022-06-02 13:30:20', 0, NULL),
(8, 2, 'Ponencia ejemplo 7', '', 2, 50, 'Example7', 3, '2022-06-02 13:00:20', 0, NULL),
(9, 2, 'Ponencia ejemplo 8', '', 2, 50, 'Example8', 3, '2022-06-02 12:13:20', 0, 'ponente 5'),
(10, 2, 'Ponencia ejemplo 9', '', 2, 50, 'Example9', 3, '2022-06-02 11:13:20', 0, NULL),
(11, 2, 'Ponencia ejemplo 10', '', 2, 50, 'Example10', 3, '2022-06-02 01:13:20', 0, NULL),
(12, 2, 'Ponencia ejemplo 11', '', 2, 50, 'Example11', 3, '2022-06-03 07:13:20', 0, 'ponente 6'),
(13, 2, 'Ponencia ejemplo 12', '', 2, 50, 'Example12', 3, '2022-06-03 08:13:20', 0, NULL),
(14, 2, 'Ponencia ejemplo 13', '', 2, 50, 'Example13', 3, '2022-06-03 12:13:20', 0, NULL),
(15, 2, 'Ponencia ejemplo 14', '', 2, 50, 'Example14', 3, '2022-06-03 16:13:20', 0, NULL),
(16, 3, 'Ponencia ejemplo 15', '', 2, 50, 'Example15', 3, '2022-06-03 13:13:20', 0, 'ponente 7');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `ev_ponencias`
--
ALTER TABLE `ev_ponencias`
  ADD PRIMARY KEY (`id_ponencia`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `ev_ponencias`
--
ALTER TABLE `ev_ponencias`
  MODIFY `id_ponencia` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
