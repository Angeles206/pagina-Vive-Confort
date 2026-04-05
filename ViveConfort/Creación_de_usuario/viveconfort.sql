-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 24-02-2026 a las 03:31:24
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `viveconfort`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `productos`
--

CREATE TABLE `productos` (
  `nombre_producto` text DEFAULT NULL,
  `categoria` text DEFAULT NULL,
  `marca` text DEFAULT NULL,
  `precio` int(11) DEFAULT NULL,
  `cantidad` int(10) DEFAULT NULL,
  `tonalidades` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `productos`
--

INSERT INTO `productos` (`nombre_producto`, `categoria`, `marca`, `precio`, `cantidad`, `tonalidades`) VALUES
('Sombras muñeca animal print x9 tonos', 'sombras', 'Kevin&Coco', 15, 24, ''),
('Voluminizador de labios', 'Gloss', 'Trendy', 12, 42, ''),
('Gloss con color (efecto humedo)', 'Gloss', 'Kiss Beauty', 6, 57, '01, 02, 03 y 05'),
('Pestañina a prueba de agua pastel', 'Pestañina', 'Trendy', 17, 63, 'Negro'),
('Delineador liquido tonos neon', 'Delineador', 'Ushas', 20, 30, 'Verde, azul, rojo y blanco'),
('Rubor en crema Aurora', 'Rubor', 'Trendy', 24, 46, '01, 03 y 04');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `nombres` varchar(30) NOT NULL,
  `apellidos` varchar(30) NOT NULL,
  `pais` varchar(40) NOT NULL,
  `departamento` varchar(40) NOT NULL,
  `ciudad` varchar(40) NOT NULL,
  `codigoPostal` int(11) NOT NULL,
  `direccion` varchar(40) DEFAULT NULL,
  `email` varchar(50) NOT NULL,
  `telefono` int(11) NOT NULL,
  `contraseña` varchar(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`nombres`, `apellidos`, `pais`, `departamento`, `ciudad`, `codigoPostal`, `direccion`, `email`, `telefono`, `contraseña`) VALUES
('Gina', 'Velasquez Vaquiro', 'Colombia', 'Huila', 'Neiva', 4100012, 'calle 7 sur #21-58', 'velasquez.gina@gmail.com', 2147483647, '12340'),
('Alejandra', 'Serpa Garcia', 'Colombia', 'Cundinamarca', 'Bogotá', 210004, 'calle 4 #54-14 bis', 'SerpaAle@gmail.com', 300123723, '0987a'),
('Carlos', 'Restrepo Córdoba', 'Colombia', 'Antioquia', 'Medellin', 50012, 'Calle 10 #43.23', 'Carlos.res@gmail.com', 2147483647, 'Admin123*'),
('Mariana', 'Castro Perez', 'Colombia', 'Atlantico', 'Barranquilla', 80003, 'Calle 72 $32-65', 'MarianaCastro@gmail.com', 2147483647, 'Mariana3'),
('Sol Angie', 'Figueroa Rodriguez', 'Colombia', 'Valle del cauca', 'Cali', 760001, 'calle 5 # 55-43 sur', 'FigueroaSolAngie@gmail.com', 320123827, 'asdw123'),
('Sara', 'Joven Losada', 'Colombia', 'Huila', 'Neiva', 410001, 'cra 5 #12-43', 'Sarajoven04@gmail.com', 2147483647, '08adrl'),
('Jhon Andres', 'Rodriguez Vega', 'Colombia', 'Caldas', 'Manizales', 170004, 'cra 6 #12-75', 'Jhonandres732@gmail.com', 2147483647, 'ja019283*');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
