-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost
-- Tiempo de generación: 24-07-2024 a las 02:01:55
-- Versión del servidor: 10.4.28-MariaDB
-- Versión de PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `libreria`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Autores`
--

CREATE TABLE `Autores` (
  `ID` int(11) NOT NULL,
  `Nombre` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Volcado de datos para la tabla `Autores`
--

INSERT INTO `Autores` (`ID`, `Nombre`) VALUES
(9, 'Truman Capote'),
(10, 'Jane Austen'),
(11, 'Miguel de Cervantes'),
(12, 'Melissa Blair'),
(13, 'Ted Dekker'),
(14, 'J. K. Rowling');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Bodegas`
--

CREATE TABLE `Bodegas` (
  `ID` int(11) NOT NULL,
  `Nombre` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Volcado de datos para la tabla `Bodegas`
--

INSERT INTO `Bodegas` (`ID`, `Nombre`) VALUES
(1, 'Bodega Yungay'),
(2, 'Bodega Carmen'),
(3, 'Bodega Balmaceda'),
(9, 'Bodega Peña'),
(12, 'Bodega San Martin');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Editoriales`
--

CREATE TABLE `Editoriales` (
  `ID` int(11) NOT NULL,
  `Nombre` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Volcado de datos para la tabla `Editoriales`
--

INSERT INTO `Editoriales` (`ID`, `Nombre`) VALUES
(1, 'Acantilado'),
(3, 'Nórdica'),
(5, 'Impedimenta'),
(6, 'La Umbría y la Solana'),
(7, 'Zig Zag'),
(8, 'Editorial Planeta'),
(9, 'Grupo Nelson'),
(10, 'Bloomsbury Salamandra Scholastic');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Movimientos`
--

CREATE TABLE `Movimientos` (
  `ID` int(11) NOT NULL,
  `Fecha` datetime NOT NULL,
  `ID_bodegaOrigen` int(11) NOT NULL,
  `ID_bodegaDestino` int(11) NOT NULL,
  `ID_Usuario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Volcado de datos para la tabla `Movimientos`
--

INSERT INTO `Movimientos` (`ID`, `Fecha`, `ID_bodegaOrigen`, `ID_bodegaDestino`, `ID_Usuario`) VALUES
(31, '2024-07-18 17:28:17', 1, 2, 1),
(32, '2024-07-18 17:28:31', 2, 9, 1),
(33, '2024-07-18 17:28:51', 3, 1, 1),
(36, '2024-07-19 20:40:57', 1, 2, 1),
(37, '2024-07-19 20:46:27', 1, 2, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ProductoEnBodega`
--

CREATE TABLE `ProductoEnBodega` (
  `ID` int(11) NOT NULL,
  `ID_Producto` int(11) NOT NULL,
  `ID_Bodega` int(11) NOT NULL,
  `Cantidad` int(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Volcado de datos para la tabla `ProductoEnBodega`
--

INSERT INTO `ProductoEnBodega` (`ID`, `ID_Producto`, `ID_Bodega`, `Cantidad`) VALUES
(1, 1, 1, 25),
(2, 2, 1, 35),
(3, 3, 1, 25),
(4, 1, 2, 55),
(5, 2, 2, 70),
(6, 3, 2, 65),
(7, 1, 3, 70),
(8, 2, 3, 80),
(9, 3, 3, 80),
(10, 1, 9, 120),
(11, 2, 9, 110),
(12, 3, 9, 170);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ProductoEnMovimiento`
--

CREATE TABLE `ProductoEnMovimiento` (
  `ID` int(11) NOT NULL,
  `ID_Movimiento` int(11) NOT NULL,
  `ID_Producto` int(11) NOT NULL,
  `Cantidad` int(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Volcado de datos para la tabla `ProductoEnMovimiento`
--

INSERT INTO `ProductoEnMovimiento` (`ID`, `ID_Movimiento`, `ID_Producto`, `Cantidad`) VALUES
(1, 31, 1, 10),
(2, 32, 3, 10),
(3, 32, 1, 20),
(4, 33, 3, 10),
(5, 33, 1, 20),
(6, 33, 2, 30),
(7, 36, 3, 10),
(8, 36, 2, 20),
(9, 37, 3, 5),
(10, 37, 1, 5);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Productos`
--

CREATE TABLE `Productos` (
  `ID` int(11) NOT NULL,
  `Nombre` varchar(40) NOT NULL,
  `Descripcion` varchar(200) NOT NULL,
  `Tipo` varchar(20) NOT NULL,
  `ID_Autor` int(11) NOT NULL,
  `ID_Editorial` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Volcado de datos para la tabla `Productos`
--

INSERT INTO `Productos` (`ID`, `Nombre`, `Descripcion`, `Tipo`, `ID_Autor`, `ID_Editorial`) VALUES
(1, 'En un instante', 'Condición: Nuevo producto', 'Revista', 13, 9),
(2, 'Don Quijote de la Mancha', 'Novela moderna', 'Libro', 11, 7),
(3, 'La Asesina Del Rey', 'Primera parte de la Saga Sangre Mestiza', 'Libro', 12, 8),
(6, 'Harry Potter y la piedra filosofal', 'Reino Unido', 'Libro', 14, 10);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Usuarios`
--

CREATE TABLE `Usuarios` (
  `ID` int(11) NOT NULL,
  `Nombre` varchar(40) NOT NULL,
  `Apellido` varchar(40) NOT NULL,
  `Usuario` varchar(20) NOT NULL,
  `Clave` varchar(200) NOT NULL,
  `Rol` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Volcado de datos para la tabla `Usuarios`
--

INSERT INTO `Usuarios` (`ID`, `Nombre`, `Apellido`, `Usuario`, `Clave`, `Rol`) VALUES
(1, 'Administrador', 'Principal', 'admin', '$2y$10$FGoD9.qXCNQbMkhNARDpZeFulSSJ0kUpG47ZMhrzMVpitL4IFwvCe', 'Administrador'),
(25, 'Carlos', 'Rojas', 'crojas', '$2y$10$R2GY95zI3.2HSY37faJkt.94bn9qsXGilZ9P4DAgSBs98CJAzJ.j2', 'Bodeguero'),
(26, 'Javier', 'Mendez', 'jmendez', '$2y$10$xXHsTSdT5UtHo5dhMV9fbOeWv0Q19Vkfkw0TVDONYyn6HgiHG3WDG', 'Jefe de Bodega'),
(28, 'Luis', 'Fernandez', 'lfernandez', '$2y$10$vBkOICjfN5Y5LDL.G7f0b.GkNUKLcigsBkcLsz5cSoUr0xWsl57rK', 'Bodeguero'),
(30, 'Diego', 'Cabrera', 'dcabrera', '$2y$10$TTp/UoTpInoqK8A6QSb5HOcd8ax/Q3QqLhiiElqFH543zLdSq7lBm', 'Bodeguero'),
(31, 'Ernesto', 'Henriquez', 'ehenriquez', '$2y$10$Yb//LFVNhQSHKyNqvSPNzOGuVfZTYgXP9MlM.B4wVxVQBrNvnMlia', 'Jefe de Bodega'),
(32, 'Marcelo', 'Torres', 'mtorres', '$2y$10$EImDf/JS.Cr6LRtXes/5AO3ttvEe5jiROS5GjRva9N7gJ2M4L3w8m', 'Bodeguero'),
(33, 'Humberto', 'Daily', 'hdaily', '$2y$10$11rruWVLY7nI.W196ttXlOhjivb0pX.2QWupSKkome3W4KHXhlCmm', 'Jefe de Bodega');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `Autores`
--
ALTER TABLE `Autores`
  ADD PRIMARY KEY (`ID`);

--
-- Indices de la tabla `Bodegas`
--
ALTER TABLE `Bodegas`
  ADD PRIMARY KEY (`ID`);

--
-- Indices de la tabla `Editoriales`
--
ALTER TABLE `Editoriales`
  ADD PRIMARY KEY (`ID`);

--
-- Indices de la tabla `Movimientos`
--
ALTER TABLE `Movimientos`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `ID_bodegaOrigen` (`ID_bodegaOrigen`),
  ADD KEY `ID_bodegaDestino` (`ID_bodegaDestino`),
  ADD KEY `ID_Usuario` (`ID_Usuario`);

--
-- Indices de la tabla `ProductoEnBodega`
--
ALTER TABLE `ProductoEnBodega`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `ID_Producto` (`ID_Producto`),
  ADD KEY `ID_Bodega` (`ID_Bodega`);

--
-- Indices de la tabla `ProductoEnMovimiento`
--
ALTER TABLE `ProductoEnMovimiento`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `ID_Movimiento` (`ID_Movimiento`),
  ADD KEY `ID_Producto` (`ID_Producto`);

--
-- Indices de la tabla `Productos`
--
ALTER TABLE `Productos`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `ID_Autor` (`ID_Autor`),
  ADD KEY `ID_Editorial` (`ID_Editorial`);

--
-- Indices de la tabla `Usuarios`
--
ALTER TABLE `Usuarios`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `Autores`
--
ALTER TABLE `Autores`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de la tabla `Bodegas`
--
ALTER TABLE `Bodegas`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `Editoriales`
--
ALTER TABLE `Editoriales`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `Movimientos`
--
ALTER TABLE `Movimientos`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT de la tabla `ProductoEnBodega`
--
ALTER TABLE `ProductoEnBodega`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `ProductoEnMovimiento`
--
ALTER TABLE `ProductoEnMovimiento`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `Productos`
--
ALTER TABLE `Productos`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `Usuarios`
--
ALTER TABLE `Usuarios`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `Movimientos`
--
ALTER TABLE `Movimientos`
  ADD CONSTRAINT `movimientos_ibfk_1` FOREIGN KEY (`ID_bodegaOrigen`) REFERENCES `Bodegas` (`ID`),
  ADD CONSTRAINT `movimientos_ibfk_2` FOREIGN KEY (`ID_bodegaDestino`) REFERENCES `Bodegas` (`ID`),
  ADD CONSTRAINT `movimientos_ibfk_3` FOREIGN KEY (`ID_Usuario`) REFERENCES `Usuarios` (`ID`);

--
-- Filtros para la tabla `ProductoEnBodega`
--
ALTER TABLE `ProductoEnBodega`
  ADD CONSTRAINT `productoenbodega_ibfk_1` FOREIGN KEY (`ID_Producto`) REFERENCES `Productos` (`ID`),
  ADD CONSTRAINT `productoenbodega_ibfk_2` FOREIGN KEY (`ID_Bodega`) REFERENCES `Bodegas` (`ID`);

--
-- Filtros para la tabla `ProductoEnMovimiento`
--
ALTER TABLE `ProductoEnMovimiento`
  ADD CONSTRAINT `productoenmovimiento_ibfk_1` FOREIGN KEY (`ID_Movimiento`) REFERENCES `Movimientos` (`ID`),
  ADD CONSTRAINT `productoenmovimiento_ibfk_2` FOREIGN KEY (`ID_Producto`) REFERENCES `Productos` (`ID`);

--
-- Filtros para la tabla `Productos`
--
ALTER TABLE `Productos`
  ADD CONSTRAINT `productos_ibfk_1` FOREIGN KEY (`ID_Autor`) REFERENCES `Autores` (`ID`),
  ADD CONSTRAINT `productos_ibfk_2` FOREIGN KEY (`ID_Editorial`) REFERENCES `Editoriales` (`ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
