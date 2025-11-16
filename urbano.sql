-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 16, 2025 at 02:49 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `urbano`
--

-- --------------------------------------------------------

--
-- Table structure for table `detalle_reservacion`
--

CREATE TABLE `detalle_reservacion` (
  `id_detalle` int(11) NOT NULL,
  `id_reservacion` int(11) DEFAULT NULL,
  `id_habitacion` int(11) DEFAULT NULL,
  `cantidad` int(11) NOT NULL,
  `precio_unitario` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `habitaciones`
--

CREATE TABLE `habitaciones` (
  `id_habitacion` int(11) NOT NULL,
  `codigo` varchar(5) NOT NULL,
  `categoria` varchar(50) NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `disponibles` int(11) NOT NULL,
  `capacidad` int(11) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `imagen` varchar(255) DEFAULT NULL,
  `activo` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `habitaciones`
--

INSERT INTO `habitaciones` (`id_habitacion`, `codigo`, `categoria`, `precio`, `disponibles`, `capacidad`, `descripcion`, `imagen`, `activo`) VALUES
(1, '101', 'Sencilla', 800.00, 5, 1, 'Habitación sencilla ideal para viajeros de negocios', NULL, 1),
(2, '201', 'Doble', 1200.00, 8, 2, 'Habitación doble con vista a la ciudad', NULL, 1),
(3, '301', 'Suite', 2500.00, 3, 4, 'Suite ejecutiva con sala de reuniones', NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `reservaciones`
--

CREATE TABLE `reservaciones` (
  `id_reservacion` int(11) NOT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `fecha_reservacion` timestamp NOT NULL DEFAULT current_timestamp(),
  `fecha_checkin` date NOT NULL,
  `fecha_checkout` date NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `estado` enum('pendiente','aceptada','cancelada') DEFAULT 'pendiente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL,
  `nombre_usuario` varchar(50) NOT NULL,
  `contrasena` varchar(255) NOT NULL,
  `email` varchar(50) DEFAULT NULL,
  `tipo_usuario` enum('admin','huesped') DEFAULT NULL,
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `nombre_usuario`, `contrasena`, `email`, `tipo_usuario`, `fecha_registro`) VALUES
(1, 'admin', 'admin123', 'admin@hotel.com', 'admin', '2025-11-08 17:15:22'),
(2, 'huesped1', 'huesped123', 'huesped@email.com', 'huesped', '2025-11-08 17:15:22');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `detalle_reservacion`
--
ALTER TABLE `detalle_reservacion`
  ADD PRIMARY KEY (`id_detalle`),
  ADD KEY `id_reservacion` (`id_reservacion`),
  ADD KEY `id_habitacion` (`id_habitacion`);

--
-- Indexes for table `habitaciones`
--
ALTER TABLE `habitaciones`
  ADD PRIMARY KEY (`id_habitacion`),
  ADD UNIQUE KEY `numero` (`codigo`);

--
-- Indexes for table `reservaciones`
--
ALTER TABLE `reservaciones`
  ADD PRIMARY KEY (`id_reservacion`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indexes for table `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `nombre_usuario` (`nombre_usuario`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `detalle_reservacion`
--
ALTER TABLE `detalle_reservacion`
  MODIFY `id_detalle` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `habitaciones`
--
ALTER TABLE `habitaciones`
  MODIFY `id_habitacion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `reservaciones`
--
ALTER TABLE `reservaciones`
  MODIFY `id_reservacion` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `detalle_reservacion`
--
ALTER TABLE `detalle_reservacion`
  ADD CONSTRAINT `detalle_reservacion_ibfk_1` FOREIGN KEY (`id_reservacion`) REFERENCES `reservaciones` (`id_reservacion`),
  ADD CONSTRAINT `detalle_reservacion_ibfk_2` FOREIGN KEY (`id_habitacion`) REFERENCES `habitaciones` (`id_habitacion`);

--
-- Constraints for table `reservaciones`
--
ALTER TABLE `reservaciones`
  ADD CONSTRAINT `reservaciones_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;