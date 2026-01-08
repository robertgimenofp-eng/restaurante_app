-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 08-01-2026 a las 18:25:14
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
-- Base de datos: `restaurante_app`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `estado_pedido`
--

CREATE TABLE `estado_pedido` (
  `id_estado` int(11) NOT NULL,
  `nombre_estado` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `estado_pedido`
--

INSERT INTO `estado_pedido` (`id_estado`, `nombre_estado`) VALUES
(1, 'Confirmado'),
(2, 'En Cocina'),
(3, 'En Reparto'),
(4, 'Entregado');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ingrediente`
--

CREATE TABLE `ingrediente` (
  `id_ingrediente` int(11) NOT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `alergenos` text DEFAULT NULL,
  `id_producto` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `linea_pedido`
--

CREATE TABLE `linea_pedido` (
  `id_linea` int(11) NOT NULL,
  `cantidad` int(11) DEFAULT NULL,
  `precio_unitario` decimal(10,2) DEFAULT NULL,
  `id_pedido` int(11) DEFAULT NULL,
  `id_producto` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `linea_pedido`
--

INSERT INTO `linea_pedido` (`id_linea`, `cantidad`, `precio_unitario`, `id_pedido`, `id_producto`) VALUES
(1, 2, 10.00, 1, 1),
(2, 1, 5.50, 1, 1),
(3, 1, 11.50, 2, 2),
(4, 1, 11.50, 2, 3),
(5, 1, 11.50, 3, 2),
(6, 1, 11.50, 3, 3),
(7, 1, 11.50, 4, 2),
(8, 1, 30.00, 4, 34),
(9, 1, 30.00, 5, 34),
(10, 1, 11.50, 5, 2),
(11, 1, 11.50, 5, 2);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `log`
--

CREATE TABLE `log` (
  `id_log` int(11) NOT NULL,
  `id_usuario` int(11) DEFAULT NULL,
  `id_entidad` int(11) DEFAULT NULL,
  `entidad_afectada` varchar(50) DEFAULT NULL,
  `accion` enum('INSERT','UPDATE','DELETE','CREATE') DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `fecha_hora` datetime DEFAULT current_timestamp(),
  `ip` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `log`
--

INSERT INTO `log` (`id_log`, `id_usuario`, `id_entidad`, `entidad_afectada`, `accion`, `descripcion`, `fecha_hora`, `ip`) VALUES
(1, 1, 1, 'producto', 'DELETE', 'Se eliminó el producto con ID: 1', '2026-01-08 15:09:32', '::1'),
(2, 1, 1, 'producto', 'UPDATE', 'Se actualizó el producto: Spicy Grill Burger (ID: 1)', '2026-01-08 15:11:59', '::1'),
(3, 1, 39, 'producto', '', 'Se creó el producto: dabid', '2026-01-08 15:12:09', '::1'),
(4, 1, 39, 'producto', 'DELETE', 'Se eliminó el producto con ID: 39', '2026-01-08 15:12:13', '::1'),
(5, 1, 40, 'producto', 'CREATE', 'Se creó el producto: asddas', '2026-01-08 15:15:01', '::1'),
(6, 1, 40, 'producto', 'DELETE', 'Se eliminó el producto con ID: 40', '2026-01-08 15:15:05', '::1'),
(7, 1, 1, 'producto', 'UPDATE', 'Se actualizó el producto: Spicy Grill Burger (ID: 1)', '2026-01-08 15:48:44', '::1');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `oferta`
--

CREATE TABLE `oferta` (
  `id_oferta` int(11) NOT NULL,
  `tipo` varchar(50) DEFAULT NULL,
  `valor` decimal(5,2) DEFAULT NULL,
  `condiciones_min_cantid` int(11) DEFAULT NULL,
  `fecha_inicio` date DEFAULT NULL,
  `fecha_fin` date DEFAULT NULL,
  `codigo_opcional` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `oferta`
--

INSERT INTO `oferta` (`id_oferta`, `tipo`, `valor`, `condiciones_min_cantid`, `fecha_inicio`, `fecha_fin`, `codigo_opcional`) VALUES
(1, 'porcentaje', 20.00, 0, '2024-01-01', '2030-12-31', 'HOLA20'),
(2, 'porcentaje', 50.00, 0, '2024-01-01', '2030-12-31', 'JUEVESFIT'),
(3, 'envio', 100.00, 15, '2024-01-01', '2030-12-31', 'ENVIOFREE');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pago`
--

CREATE TABLE `pago` (
  `id_pago` int(11) NOT NULL,
  `metodo` enum('Tarjeta','PayPal','Efectivo') DEFAULT NULL,
  `estado_pago` enum('Pendiente','Completado','Fallido') DEFAULT NULL,
  `fecha_pago` datetime DEFAULT NULL,
  `id_pedido` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pedido`
--

CREATE TABLE `pedido` (
  `id_pedido` int(11) NOT NULL,
  `fecha` datetime DEFAULT current_timestamp(),
  `total` decimal(10,2) DEFAULT NULL,
  `id_estado` int(11) DEFAULT NULL,
  `id_usuario` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pedido`
--

INSERT INTO `pedido` (`id_pedido`, `fecha`, `total`, `id_estado`, `id_usuario`) VALUES
(1, '2026-01-02 05:12:04', 25.50, 1, 1),
(2, '2026-01-08 00:00:00', 0.00, 1, 1),
(3, '2026-01-08 00:00:00', 18.40, 1, 2),
(4, '2026-01-08 00:00:00', 41.50, 1, 1),
(5, '2026-01-08 00:00:00', 53.00, 2, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `producto`
--

CREATE TABLE `producto` (
  `id_producto` int(11) NOT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `precio` decimal(10,2) DEFAULT NULL,
  `categoria` varchar(100) DEFAULT NULL,
  `imagen_url` varchar(255) DEFAULT NULL,
  `id_oferta` int(11) DEFAULT NULL,
  `stock` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `producto`
--

INSERT INTO `producto` (`id_producto`, `nombre`, `descripcion`, `precio`, `categoria`, `imagen_url`, `id_oferta`, `stock`) VALUES
(1, 'Spicy Grill Burger', 'Pan integral, hamburguesa de pollo o soja, queso light, tomate, lechuga, cebolla morada y mostaza.', 12.50, 'Fit Burgers', 'spicygrill.webp', NULL, 55),
(2, 'Fit Burger Pollo', 'Pan integral, hamburguesa de pollo o soja, queso light, tomate, lechuga y pepinillo.', 11.50, 'Fit Burgers', 'pollofit.webp', NULL, 50),
(3, 'BBQ Fit Burger', 'Pan de centeno, hamburguesa de pavo, queso fundido bajo en grasa, cebolla morada y salsa barbacoa zero.', 11.50, 'Fit Burgers', 'bbqfit.webp', NULL, 50),
(4, 'Vegan Crunch Burger', 'Pan de avena, hamburguesa de lentejas y quinoa, tomate seco, rúcula, pepino y hummus.', 11.50, 'Fit Burgers', 'vegancrunch.webp', NULL, 50),
(5, 'Vegan Energy Wrap', 'Tortilla de espinaca, hummus de cúrcuma, garbanzos crujientes, rúcula, zanahoria y remolacha.', 10.50, 'Wraps & Bowls', 'veganwrap.webp', NULL, 50),
(6, 'Chicken Protein Wrap', 'Tortilla integral, pollo marinado, espinacas, pimientos asados, queso fresco y aguacate.', 10.50, 'Wraps & Bowls', 'chickenwrap.webp', NULL, 50),
(7, 'Power Green Bowl', 'Quinoa tricolor, pollo o tofu, aguacate, espinacas, brócoli, pepino y semillas de calabaza.', 10.50, 'Wraps & Bowls', 'veganbowl.webp', NULL, 50),
(8, 'Mediterranean Glow Bowl', 'Cuscús integral, garbanzos especiados, calabacín, pimientos, tomate cherry, aceitunas y feta.', 10.50, 'Wraps & Bowls', 'mediterraneanbowl.webp', NULL, 50),
(9, 'Bolas Dátil', 'Bolitas energéticas de dátil, avena y cacao puro (Pack de 3).', 3.50, 'Snacks Saludables', 'bolasdatil.webp', NULL, 100),
(10, 'Yogur Granola', 'Yogur griego con granola casera y frutos rojos frescos.', 3.30, 'Snacks Saludables', 'yogurgranola.webp', NULL, 100),
(11, 'Kale Chips', 'Chips de kale al horno crujientes con un toque de sal marina.', 2.50, 'Snacks Saludables', 'kale.webp', NULL, 100),
(12, 'Barritas Proteicas', 'Barrita proteica de frutos secos y miel, ideal para post-entreno. (Incluyen 4)', 1.50, 'Snacks Saludables', 'barritasproteina.webp', NULL, 200),
(13, 'Smoothie Verde', 'Manzana, espinaca, apio, pepino, limón y jengibre.', 2.50, 'Bebidas', 'smothie.webp', NULL, 50),
(14, 'Agua 1L', 'Botella de agua mineral natural (Envase reciclable).', 1.50, 'Bebidas', 'agua1l.webp', NULL, 200),
(15, 'Kombucha', 'Bebida fermentada probiótica sabor frutos del bosque.', 2.00, 'Bebidas', 'kombucha.webp', NULL, 50),
(16, 'Zumo Naranja', 'Zumo de naranja 100% natural exprimido al momento.', 2.00, 'Bebidas', 'zumo.webp', NULL, 50),
(33, 'Menú Personalizado', 'Menú Personalizado', 13.50, 'Menús', 'menupersonalizado.webp', NULL, 100),
(34, 'Pack Amigos', '2 Burgers, 1 Wrap, 1 Bowl...', 30.00, 'Menús', 'packamigos.webp', NULL, 100),
(35, 'Pack Familiar', '3 Burgers, 2 Wraps...', 50.00, 'Menús', 'packfamiliar.webp', NULL, 100),
(36, 'Pack Vegano', 'Pack vegano', 18.00, 'Menús', 'packvegano.webp', NULL, 100);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `id_usuario` int(11) NOT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `contraseña` varchar(255) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `direccion` varchar(200) DEFAULT NULL,
  `rol` enum('cliente','admin') NOT NULL DEFAULT 'cliente',
  `fecha_registro` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`id_usuario`, `nombre`, `email`, `contraseña`, `telefono`, `direccion`, `rol`, `fecha_registro`) VALUES
(1, 'Admin', 'admin@restaurante.com', '$2y$10$TEygrWugQEB68ox7WkmFnuCgYDJoOslGGbR7Om98xgCfk1SPY4Biu', '638001292', 'Carrer dAnselm Clavé 64', 'admin', '2025-12-05 18:06:53'),
(2, 'dabid', 'davidrojasfp24@gmail.com', '$2y$10$pZUmbWqIcWQJ1LtuYNEPWe5E4ewiL2aBCM9Xhw47OlAAHL5PoPhPS', '617544515', 'Anselmo Clavel 65', 'cliente', '2025-12-11 18:13:52');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `estado_pedido`
--
ALTER TABLE `estado_pedido`
  ADD PRIMARY KEY (`id_estado`);

--
-- Indices de la tabla `ingrediente`
--
ALTER TABLE `ingrediente`
  ADD PRIMARY KEY (`id_ingrediente`),
  ADD KEY `id_producto` (`id_producto`);

--
-- Indices de la tabla `linea_pedido`
--
ALTER TABLE `linea_pedido`
  ADD PRIMARY KEY (`id_linea`),
  ADD KEY `id_pedido` (`id_pedido`),
  ADD KEY `id_producto` (`id_producto`);

--
-- Indices de la tabla `log`
--
ALTER TABLE `log`
  ADD PRIMARY KEY (`id_log`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `oferta`
--
ALTER TABLE `oferta`
  ADD PRIMARY KEY (`id_oferta`);

--
-- Indices de la tabla `pago`
--
ALTER TABLE `pago`
  ADD PRIMARY KEY (`id_pago`),
  ADD UNIQUE KEY `id_pedido` (`id_pedido`);

--
-- Indices de la tabla `pedido`
--
ALTER TABLE `pedido`
  ADD PRIMARY KEY (`id_pedido`),
  ADD KEY `id_estado` (`id_estado`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `producto`
--
ALTER TABLE `producto`
  ADD PRIMARY KEY (`id_producto`),
  ADD KEY `id_oferta` (`id_oferta`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `estado_pedido`
--
ALTER TABLE `estado_pedido`
  MODIFY `id_estado` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `ingrediente`
--
ALTER TABLE `ingrediente`
  MODIFY `id_ingrediente` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `linea_pedido`
--
ALTER TABLE `linea_pedido`
  MODIFY `id_linea` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `log`
--
ALTER TABLE `log`
  MODIFY `id_log` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `oferta`
--
ALTER TABLE `oferta`
  MODIFY `id_oferta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `pago`
--
ALTER TABLE `pago`
  MODIFY `id_pago` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `pedido`
--
ALTER TABLE `pedido`
  MODIFY `id_pedido` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `producto`
--
ALTER TABLE `producto`
  MODIFY `id_producto` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `ingrediente`
--
ALTER TABLE `ingrediente`
  ADD CONSTRAINT `ingrediente_ibfk_1` FOREIGN KEY (`id_producto`) REFERENCES `producto` (`id_producto`);

--
-- Filtros para la tabla `linea_pedido`
--
ALTER TABLE `linea_pedido`
  ADD CONSTRAINT `linea_pedido_ibfk_1` FOREIGN KEY (`id_pedido`) REFERENCES `pedido` (`id_pedido`),
  ADD CONSTRAINT `linea_pedido_ibfk_2` FOREIGN KEY (`id_producto`) REFERENCES `producto` (`id_producto`);

--
-- Filtros para la tabla `log`
--
ALTER TABLE `log`
  ADD CONSTRAINT `log_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`);

--
-- Filtros para la tabla `pago`
--
ALTER TABLE `pago`
  ADD CONSTRAINT `pago_ibfk_1` FOREIGN KEY (`id_pedido`) REFERENCES `pedido` (`id_pedido`);

--
-- Filtros para la tabla `pedido`
--
ALTER TABLE `pedido`
  ADD CONSTRAINT `pedido_ibfk_1` FOREIGN KEY (`id_estado`) REFERENCES `estado_pedido` (`id_estado`),
  ADD CONSTRAINT `pedido_ibfk_2` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id_usuario`);

--
-- Filtros para la tabla `producto`
--
ALTER TABLE `producto`
  ADD CONSTRAINT `producto_ibfk_1` FOREIGN KEY (`id_oferta`) REFERENCES `oferta` (`id_oferta`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
