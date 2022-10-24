-- phpMyAdmin SQL Dump
-- version 5.1.3
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 01-06-2022 a las 17:03:04
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
-- Estructura de tabla para la tabla `ev_evento`
--

CREATE TABLE `ev_evento` (
  `idEvento` int(11) NOT NULL,
  `tipo` varchar(50) COLLATE utf8_unicode_ci NOT NULL,
  `titulo` text COLLATE utf8_unicode_ci NOT NULL,
  `nombreClave` varchar(80) COLLATE utf8_unicode_ci NOT NULL,
  `fechaE` date DEFAULT NULL,
  `fechaDisponible` datetime NOT NULL,
  `fechaLimite` datetime NOT NULL,
  `limiteProspectos` int(11) NOT NULL,
  `duracion` float NOT NULL,
  `tipoDuracion` varchar(2) COLLATE utf8_unicode_ci NOT NULL COMMENT 'h:hora, d:dia, s:semana, m:mes',
  `direccion` varchar(180) COLLATE utf8_unicode_ci NOT NULL,
  `estado` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `pais` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `codigoPromocional` varchar(9) COLLATE utf8_unicode_ci NOT NULL,
  `estatus` int(11) NOT NULL,
  `modalidadEvento` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  `idInstitucion` int(11) NOT NULL,
  `imagen` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `imgFondo` varchar(200) COLLATE utf8_unicode_ci NOT NULL,
  `plantilla_bienvenida` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `descripcion` text COLLATE utf8_unicode_ci NOT NULL,
  `video_url` text COLLATE utf8_unicode_ci NOT NULL,
  `plantilla_constancia` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `webex_id` int(11) DEFAULT NULL,
  `limite_taller` tinyint(3) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Volcado de datos para la tabla `ev_evento`
--

INSERT INTO `ev_evento` (`idEvento`, `tipo`, `titulo`, `nombreClave`, `fechaE`, `fechaDisponible`, `fechaLimite`, `limiteProspectos`, `duracion`, `tipoDuracion`, `direccion`, `estado`, `pais`, `codigoPromocional`, `estatus`, `modalidadEvento`, `idInstitucion`, `imagen`, `imgFondo`, `plantilla_bienvenida`, `descripcion`, `video_url`, `plantilla_constancia`, `webex_id`, `limite_taller`) VALUES
(2, 'CONGRESO', 'CISMAC 2021', 'cismac-congreso', '2021-11-12', '2021-08-07 00:00:00', '2021-11-13 00:00:00', 300, 5, 'h', 'Aurelio Aceves 225 Vallarta Poniente, CP 44110 Guadalajara', '14', '37', 'CISMAC201', 2, 'Presencial', 13, 'cismac.png', 'slide1.jpg', 'eventos/plantilla_confirmar_registro.html', 'Evento patrocinado por CONACON', '[[\"Neurociencias en las adicciones\",\"https:\\/\\/player.vimeo.com\\/video\\/654805633?h=3096390b9b\"],[\"El Tratamiento Humanitario\",\"https:\\/\\/player.vimeo.com\\/video\\/654658045?h=e44e4aa62f\"],[\"La Profesionalizaci\\u00f3n\",\"https:\\/\\/player.vimeo.com\\/video\\/654654399?h=9133d38a34\"],[\"Inteligencia Emocional\",\"https:\\/\\/player.vimeo.com\\/video\\/654654399?h=9133d38a34\"],[\"El G\\u00e9nero s\\u00ed importa\",\"https:\\/\\/player.vimeo.com\\/video\\/654380441?h=16cb5b6818\"],[\"Tratamiento de las conductas antisociales\",\"https:\\/\\/player.vimeo.com\\/video\\/654379970?h=f978b9523c\"],[\"presentacion app sigo\",\"https:\\/\\/player.vimeo.com\\/video\\/654378675?h=967cfd1bdd\"],[\"Consejer\\u00eda en la sociedad\",\"https:\\/\\/player.vimeo.com\\/video\\/654376662?h=3f07c55ec2\"]]', '', NULL, 2),
(5, 'CONGRESO', 'La familia', 'familia', '2020-12-30', '2020-12-31 00:35:38', '2020-12-31 00:35:38', 100, 4, 'h', '', '21', '37', '', 2, '', 2, '/La%20familia.jpg', '/La%20familia.jpg', '', '', '[[\"LA FAMILIA\",\"https:\\/\\/conacon.org\\/sandbox\\/videos\\/La%20familia.mp4\"]]', '', NULL, NULL),
(6, 'CONGRESO', 'Responder a la vida sin violencia', 'responder-sin-violencia', '2020-12-30', '2020-12-31 00:35:38', '2020-12-31 00:35:38', 100, 4, 'h', '', '21', '37', '', 2, '', 2, '/sinviolencia.jpg', '/sinviolencia.jpg', '', '', '[[\"RESPONDER SIN VIOLENCIA\",\"https:\\/\\/conacon.org\\/sandbox\\/videos\\/responder%20a%20la%20vida%20sin%20violencia.mp4\"]] ', '', NULL, NULL),
(9, 'CONGRESO', 'Logoterapia aplicada en consejería terapéutica y salud mental', 'logoterapia-aplicada', '2020-12-30', '2020-12-31 00:35:38', '2020-12-31 00:35:38', 100, 4, 'h', '', '21', '37', '', 2, '', 2, '/SaludMental.jpg', '/SaludMental.jpg', '', '', '[[\"JUAN JOSE VAZQUEZ\",\"https:\\/\\/conacon.org\\/sandbox\\/videos\\/logoterapia\\/logoterapia%20parte%201.mp4\"],[\"MARIA ELVIRA SANDOVAL SANCHEZ\",\"https:\\/\\/conacon.org\\/sandbox\\/videos\\/logoterapia\\/logoterapia%20parte%202.mp4\"],[\"MIGUEL FLORES GOMEZ\",\"https:\\/\\/conacon.org\\/sandbox\\/videos\\/logoterapia\\/logoterapia%20parte%203.mp4\"],[\"ESTEBAN RAMIREZ ALVAREZ\",\"https:\\/\\/conacon.org\\/sandbox\\/videos\\/logoterapia\\/logoterapia%20parte%204.mp4\"],[\"LUIS ARTURO GONZALEZ LOZANO\",\"https:\\/\\/conacon.org\\/sandbox\\/videos\\/logoterapia\\/logoterapia%20parte%205.mp4\"]] ', '', NULL, NULL),
(10, 'CONGRESO', 'Intervención en crisis', 'intervencion-en-crisis', '2020-12-30', '2020-12-31 00:35:38', '2020-12-31 00:35:38', 100, 4, 'h', '', '21', '37', '', 2, '', 2, '/crisis.jpg', '/crisis.jpg', '', '', '[[\"INTERVENCI\\u00d3N EN CRISIS\",\"https:\\/\\/conacon.org\\/sandbox\\/videos\\/Intervenci%C3%B3n%20en%20Crisis.mp4\"]] ', '', NULL, NULL),
(11, 'CONGRESO', 'Consejería y sus alcances en el acompañamiento', 'acompanamiento', '2020-12-30', '2020-12-31 00:35:38', '2020-12-31 00:35:38', 100, 4, 'h', '', '21', '37', '', 2, '', 2, '/AlcancesAcompanamiento.jpg', '/AlcancesAcompanamiento.jpg', '', '', '[[\"CONSEJER\\u00cdA Y SUS ALCANCES\",\"https:\\/\\/conacon.org\\/sandbox\\/videos\\/consejeria%20y%20sus%20alcances%20en%20el%20acompa%C3%B1amiento.mp4\"]] ', '', NULL, NULL),
(12, 'CONGRESO', '1er Foro nacional en salud mental y adicciones', 'foro-adicciones', '2020-12-30', '2020-12-31 00:35:38', '2020-12-31 00:35:38', 100, 4, 'h', '', '21', '37', '', 2, '', 2, '/Foro.jpg', '/Foro.jpg', '', '', '[[\"VIDEO\",\"https:\\/\\/conacon.org\\/sandbox\\/videos\\/1ER%20FORO%20NACIONAL%20EN%20SALUD%20MENTAL%20Y%20ADICCIONES.mp4\"]] ', '', NULL, NULL),
(13, 'CONGRESO', '5to Foro nacional fortaleciendo ideas para ser mejores', 'foro-nacional-ser-mejores', '2020-12-30', '2020-12-31 00:35:38', '2020-12-31 00:35:38', 100, 4, 'h', '', '21', '37', '', 2, '', 2, '/ForoIdeas.jpg', '/ForoIdeas.jpg', '', '', '[[\"INTRODUCCION\",\"https:\\/\\/conacon.org\\/sandbox\\/videos\\/5toForo\\/5to%20Foro%20nacional%20fortaleciendo%20ideas%20parte%201.mp4\"],[\"JORGE INZUNZA\",\"https:\\/\\/conacon.org\\/sandbox\\/videos\\/5toForo\\/5to%20Foro%20nacional%20fortaleciendo%20ideas%20parte%202.mp4\"],[\"MARIA ELVIRA SANDOVAL SANCHEZ\",\"https:\\/\\/conacon.org\\/sandbox\\/videos\\/5toForo\\/5to%20Foro%20nacional%20fortaleciendo%20ideas%20parte%203.mp4\"],[\"TOMAS LOPEZ HERNANDEZ\",\"https:\\/\\/conacon.org\\/sandbox\\/videos\\/5toForo\\/5to%20Foro%20nacional%20fortaleciendo%20ideas%20parte%204.mp4\"],[\"PEDRO DAMIAN ACEVES\",\"https:\\/\\/conacon.org\\/sandbox\\/videos\\/5toForo\\/5to%20Foro%20nacional%20fortaleciendo%20ideas%20parte%205.mp4\"],[\"ROBERTO ORTIZ CERVANTES\",\"https:\\/\\/conacon.org\\/sandbox\\/videos\\/5toForo\\/5to%20Foro%20nacional%20fortaleciendo%20ideas%20parte%206.mp4\"],[\"JUAN PATRICIO RUIZ ESPARZA HERRRERA\",\"https:\\/\\/conacon.org\\/sandbox\\/videos\\/5toForo\\/5to%20Foro%20nacional%20fortaleciendo%20ideas%20parte%207.mp4\"],[\"PSIC LUIS JESUS OROS YA\\u00d1EZ\",\"https:\\/\\/conacon.org\\/sandbox\\/videos\\/5toForo\\/5to%20Foro%20nacional%20fortaleciendo%20ideas%20parte%208.mp4\"]] ', '', NULL, NULL),
(14, 'CONGRESO', 'La salud mental en tiempos de coronavirus', 'salud-menta-en-tiempos-de-coronavirus', '2020-12-31', '2020-12-31 00:35:38', '2020-12-31 00:35:38', 100, 4, 'h', '', '21', '37', '', 2, '', 2, '/SaludMental_Coronavirus.jpg', '/SaludMental_Coronavirus.jpg', '', '', '[[\"NOEMI GOMEZ\",\"https:\\/\\/conacon.org\\/sandbox\\/videos\\/saludCoronavirus\\/LA%20SALUD%20MENTAL%20EN%20TIEMPO%20DEL%20CORONAVIRUS%20NOEMI%20GOMEZ%20P1.mp4\"],[\"MIGUEL FLORES GOMEZ\",\"https:\\/\\/conacon.org\\/sandbox\\/videos\\/saludCoronavirus\\/LA%20SALUD%20MENTAL%20EN%20TIEMPO%20DEL%20CORONAVIRUS%20MIGUEL%20FLORES%20GOMEZ%20P2.mp4\"]] ', '', NULL, NULL),
(15, 'PRESENTACION', 'Presentación de libro SOY ADICTO SOY ADICTA', 'presentacion-libro-soy-adicto', '2020-12-31', '2020-12-31 00:35:38', '2020-12-31 00:35:38', 100, 4, 'h', '', '21', '37', '', 2, '', 2, '/PresentacionLibro.jpg', '/PresentacionLibro.jpg', '', '', '[[\"MTRO MIGUEL FLORES GOMEZ\",\"https:\\/\\/conacon.org\\/sandbox\\/videos\\/libro\\/presentacion%20del%20libro%20p1.mp4\"],[\"DRA NOHEMI GOMEZ\",\"https:\\/\\/conacon.org\\/sandbox\\/videos\\/libro\\/presentacion%20del%20libro%20p2.mp4\"],[\"MTRO MIGUEL FLORES GOMEZ MITZA PEREZ CASIMIRO ARCE MTRO MIGUEL FLORES GOMEZ\",\"https:\\/\\/conacon.org\\/sandbox\\/videos\\/libro\\/presentacion%20del%20libro%20p3.mp4\"]] ', '', NULL, NULL),
(16, 'CONGRESO', 'relaciones de pareja PSIC. ELEZABETH BONOSO', 'relaciones-de-pareja', '2020-12-31', '2020-12-31 00:35:38', '2020-12-31 00:35:38', 100, 4, 'h', '', '21', '37', '', 3, '', 2, '/RelacionesDePareja.jpg', '/RelacionesDePareja.jpg', '', '', '[[\"RELACI\\u00d3N DE PAREJA\",\"https:\\/\\/conacon.org\\/sandbox\\/videos\\/relaciones%20de%20pareja%20PSIC%20ELEZABETH%20BONOSO.mp4\"]] ', '', NULL, NULL),
(17, 'CONGRESO', 'PSICOPATOLOGIA CRIMINAL', 'psicopatologia-criminal', '2020-12-31', '2020-12-31 00:35:38', '2020-12-31 00:35:38', 100, 4, 'h', '', '21', '37', '', 2, '', 2, '/Psicopatologia.jpg', '/Psicopatologia.jpg', '', '', '[[\"PSICOPATOLOGIA CRIMINAL\",\"https:\\/\\/conacon.org\\/sandbox\\/videos\\/PSICOPATOLOGIA%20CRIMINAL%20MARIA%20ELVIRA%20SANDOVAL%20SANCHEZ.mp4\"]] ', '', NULL, NULL),
(18, 'CONGRESO', 'CONDUCTA ANTISOCIAL DESDE LA SALUD MENTAL FORENSE', 'conducta-antisocial-desde-la-salud-mental-forense', '2020-12-31', '2020-12-31 00:35:38', '2020-12-31 00:35:38', 100, 4, 'h', '', '21', '37', '', 2, '', 2, '/ConductaAntisocial.jpg', '/ConductaAntisocial.jpg', '', '', '[[\"CONDUCTA ANTISOCIAL DESDE LA SALUD MENTAL\",\"https:\\/\\/conacon.org\\/sandbox\\/videos\\/CONDUCTA%20ANTISOCIAL%20DESDE%20LA%20SALUD%20MENTAL%20FORENSE%20J%20NICOLAS%20IVAN%20MARTINEZ.mp4\"]] ', '', NULL, NULL),
(19, 'CONGRESO', 'APRENDER A TRAVÉS DE LA CRISIS', 'aprender-a-traves-de-la-crisis', '2020-12-31', '2020-12-31 00:35:38', '2020-12-31 00:35:38', 100, 4, 'h', '', '21', '37', '', 2, '', 2, '/AprenderCrisis.jpg', '/AprenderCrisis.jpg', '', '', '[[\"APRENDER A TRAV\\u00c9S DE LA CRISIS\",\"https:\\/\\/conacon.org\\/sandbox\\/videos\\/aprender%20de%20la%20crisis.mp4\"]] ', '', NULL, NULL),
(20, 'SEMINARIO', 'LA SALUD MENTAL EN TIEMPO DEL CORONAVIRUS', 'salud-mental-en-coronavirus', '2020-04-27', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 100, 2, 'h', 'En linea', '', '', '', 0, 'en linea', 2, '', '', '', '', '', '', NULL, NULL),
(21, 'SEMINARIO', 'PSICOPATOLOGÍA CRIMINAL', 'psicopatoligía-criminal', '2020-08-03', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 100, 2, 'h', 'En linea', '', '', '', 0, 'en linea', 2, '', '', '', '', '', '', NULL, NULL),
(22, 'FORO', '5to FORO NACIONAL: FORTALECIENDO IDEAS PARA SER MEJORES', 'fortaleciendo-ideas-ser-mejores', '2020-08-21', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 100, 4, 'h', 'En linea', '', '', '', 0, 'en linea', 2, '', '', '', '', '', '', NULL, NULL),
(23, 'FORO', '2do FORO NACIONAL ADICCIONES, CONDUCTAS ANTISOCIALES VIOLENTAS Y PERSPECTIVA DE GÉNERO', 'adicciones-conductas-antisociales', '2020-09-25', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 100, 4, 'h', 'En linea', '', '', '', 0, 'en linea', 2, '', '', '', '', '', '', NULL, NULL),
(24, 'FORO', 'PRIMER FORO NACIONAL DE SALUD MENTAL', 'foro-nacional-salud-mental', '2020-10-09', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 100, 4, 'h', 'En linea', '', '', '', 0, 'en linea', 2, '', '', '', '', '', '', NULL, NULL),
(25, 'FORO', 'LOGOTERAPIA APLICADA EN CONSEJERÍA TERAPÉUTICA Y SALUD MENTAL', 'logoterapia-fe-sentido-de-vida', '2021-02-13', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 100, 4, 'h', 'En linea', '', '', '', 0, 'en linea', 2, '', '', '', '', '', '', NULL, NULL),
(26, 'FORO', 'MISERICORDIA, FE Y SENTIDO DE VIDA', 'misericordia-fe-sentido-de-vida', '2021-06-22', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 100, 3, 'h', 'En linea', '', '', '', 0, 'en linea', 2, '', '', '', '', '', '', NULL, NULL),
(27, 'FORO', '1ER. FORO PARA LA ATENCIÓN Y PREVENCIÓN DE CONDUCTAS ANTISOCIALES Y SALUD MENTAL', 'atención-y-prevención-conductas-antisociales', '2021-06-23', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 100, 2, 'h', 'En linea', '', '', '', 0, 'en linea', 2, '', '', '', '', '', '', NULL, NULL),
(28, 'FORO', '1ER. FORO PARA LA ATENCIÓN Y PREVENCIÓN DE CONDUCTAS ANTISOCIALES Y SALUD MENTAL', 'atención-y-prevención-conductas-antisociales-2', '2021-06-24', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 100, 2, 'h', 'En linea', '', '', '', 0, 'en linea', 2, '', '', '', '', '', '', NULL, NULL),
(29, 'TALLER', 'TALLER CONSEJERÍA Y OPERACIÓN TERAPÉUTICA EN ADICCIONES', 'taller-consejería-y-operación-terapéutica', '2021-09-14', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 100, 3, 'h', 'En linea', '', '', '', 0, 'en linea', 2, '', '', '', '', '', '', NULL, NULL),
(30, 'CURSO', 'Logoterapia', 'curso-logoterapia', '2021-11-08', '2021-10-22 00:00:00', '2021-11-11 00:00:00', 100, 20, 'h', ' Aurelio Aceves 225 Vallarta Poniente, CP 44110  Guadalajara, Jal. México.', '14', '37', ' ', 0, 'Mixta', 13, 'imagenEvento233911477.jpg', 'fondoEvento1929819997.jpg', 'eventos/plantilla_confirmar_registro.html', 'Dirigido a consejeros en adicciones, operadores terapéuticos, psicólogos, responsables de programas de tratamiento en adicciones y su personal clínico, responsables de programas ambulatorios, guías espirituales y personas interesadas en su auto superación.', '', '', NULL, NULL),
(35, 'CONGRESO', 'CIRUGÍA Y MEDICINA ESTÉTICA', 'congreso_medicina_2022', '2022-01-27', '2021-11-22 17:10:18', '2022-01-30 10:10:23', 5000, 4, 'd', 'Av. Paseo de la Reforma 80, Juárez, Cuauhtémoc, 06600 Ciudad de México, CDMX', '9', '37', '', 1, 'Presencial', 19, '', '', 'eventos/nueva_plantilla_cirugia', 'Congreso de Medicina y Cirugía Estetica', '', 'plantilla_prueba_evento.jpg', NULL, NULL),
(36, 'TALLER', 'Pasantía para la Operación del Modelo de Comunidad Terapéutica para drogodependientes', 'taller-pasantia-drogode', '2021-11-29', '0000-00-00 00:00:00', '0000-00-00 00:00:00', 100, 40, 'h', '', '19', '37', '', 1, 'Presencial', 2, 'diplomaconacon2021dec.jpg', 'diplomaconacon2021dec.jpg', '', '', '', '', NULL, NULL),
(39, 'PROGRAMA', 'Amor con Amor se Paga', 'amor-con-amor-se-paga', '2022-03-16', '2022-03-16 00:00:00', '2023-03-31 00:00:00', 5000, 0, '', '', '21', '37', '', 1, 'Mixta', 13, 'imagenEvento189006854.jpg', 'fondoEvento744755512.jpg', 'carreras/nueva_plantilla_amor.html', '', '', '', NULL, NULL),
(40, 'AFILIACION', 'Afiliación Carrera', 'afiliaciontest', '2022-04-07', '2022-04-07 00:00:00', '2022-04-07 00:00:00', 5, 5, 'h', 'direccion ', '15', '37', 'no tiee', 1, 'En linea', 33, 'imagenEvento1208489967.jpg', 'fondoEvento898132982.jpg', 'carreras/nueva_plantilla_tsu.html', 'se selecciona la plantilla carreras/plantilla_afiliacion_registro.html', '', '', NULL, NULL),
(41, 'amor-con-amor', 'LA TRIADA DE LA ADICCION LA RECUPERACION Y LA ABSTINENCIA', 'triada-de-la-adiccion', '2022-05-04', '2022-05-04 00:00:00', '2022-05-10 00:00:00', 100000, 1, 'h', 'online', '21', '37', '.', 1, 'En linea', 13, 'imagenEvento188257535.jpg', 'fondoEvento1203879879.jpg', 'carreras/nueva_plantilla_amor.html', 'LA TRIADA DE LA ADICCION LA RECUPERACION Y LA ABSTINENCIA', '', '', 19, NULL),
(42, 'amor-con-amor', 'EVENTO SECUNDARIO DE PRUEBA DE AMOR CON AMOR', 'pruebanuevo', '2022-05-07', '2022-05-07 00:00:00', '2022-05-10 00:00:00', 5, 5, 'h', 'Dirección 12345', '18', '37', 'SINCODIGO', 1, 'En linea', 36, 'imagenEvento1455458280.jpg', 'fondoEvento844547858.jpg', 'carreras/nueva_plantilla_distintivo_conacon.html', 'ES UN EVENTO DE PRUEBA NO VALIDAR GRACIAS.', '', '', NULL, NULL);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `ev_evento`
--
ALTER TABLE `ev_evento`
  ADD PRIMARY KEY (`idEvento`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `ev_evento`
--
ALTER TABLE `ev_evento`
  MODIFY `idEvento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
