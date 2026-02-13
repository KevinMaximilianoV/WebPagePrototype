-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 12-06-2025 a las 03:27:07
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
-- Base de datos: `rebibanelserber`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `date_added` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `order_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `total_amount`, `order_date`) VALUES
(1, 1, 3849.88, '2025-03-25 19:41:00'),
(2, 1, 599.99, '2025-03-26 19:01:56'),
(3, 1, 599.99, '2025-03-26 19:12:53'),
(4, 1, 599.99, '2025-03-26 19:20:45'),
(5, 2, 629.98, '2025-03-26 19:30:02'),
(6, 2, 399.99, '2025-03-26 19:31:34'),
(7, 2, 179.99, '2025-03-26 19:32:16');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `quantity`, `price`) VALUES
(1, 1, 1, 1, 599.99),
(2, 1, 2, 1, 549.99),
(3, 1, 3, 1, 449.99),
(4, 1, 4, 1, 179.99),
(5, 1, 5, 1, 399.99),
(6, 1, 6, 1, 139.99),
(7, 1, 7, 1, 129.99),
(8, 1, 8, 1, 149.99),
(9, 1, 9, 1, 799.99),
(10, 1, 10, 1, 149.99),
(11, 1, 11, 1, 169.99),
(12, 1, 12, 1, 129.99),
(13, 2, 1, 1, 599.99),
(14, 3, 1, 1, 599.99),
(15, 4, 1, 1, 599.99),
(16, 5, 3, 1, 449.99),
(17, 5, 4, 1, 179.99),
(18, 6, 5, 1, 399.99),
(19, 7, 4, 1, 179.99);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `category` varchar(50) DEFAULT NULL,
  `stock` int(11) DEFAULT 10
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `price`, `image_url`, `category`, `stock`) VALUES
(1, 'NVIDIA RTX 4070', '12GB GDDR6X Graphics Card', 599.99, 'https://i.imgur.com/cobvC3o.png', 'Graphics Cards', 13),
(2, 'Intel Core i9-13900K', '24-Core, 32-Thread CPU', 549.99, 'https://i.imgur.com/O2ATPn0.png', 'Processors', 20),
(3, 'AMD Ryzen 9 7900X', '12-Core, 24-Thread CPU', 449.99, 'https://i.imgur.com/DRiQWv2.png', 'Processors', 24),
(4, 'Samsung 990 PRO', '2TB NVMe PCIe 4.0 SSD', 179.99, 'https://i.imgur.com/YIVIoEM.png', 'Storage', 28),
(5, 'ASUS ROG Strix Z790-E', 'ATX Motherboard for Intel CPUs', 399.99, 'https://i.imgur.com/K2BJunj.png', 'Motherboards', 11),
(6, 'Corsair Vengeance RGB', '32GB (2x16GB) DDR5 6000MHz', 139.99, 'https://i.imgur.com/heQAUAR.png', 'Memory', 40),
(7, 'NZXT H7 Flow', 'ATX Mid-Tower Case', 129.99, 'https://i.imgur.com/pZ4LMu1.png', 'Cases', 18),
(8, 'Corsair RM850x', '850W 80+ Gold PSU', 149.99, 'https://i.imgur.com/ooOm7FF.png', 'Power Supplies', 22),
(9, 'MSI Optix MPG321UR-QD', '32\" 4K 144Hz Gaming Monitor', 799.99, 'https://i.imgur.com/EvvCZrx.png', 'Monitors', 10),
(10, 'Logitech G Pro X Superlight', 'Wireless Gaming Mouse', 149.99, 'https://i.imgur.com/SBzPBQB.png', 'Peripherals', 35),
(11, 'Keychron Q1 Pro', 'Wireless Mechanical Keyboard', 169.99, 'https://i.imgur.com/5eDiNow.png', 'Peripherals', 15),
(12, 'Arctic Liquid Freezer II 360', '360mm AIO CPU Cooler', 129.99, 'https://i.imgur.com/XAz1m60.png', 'Cooling', 20);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `created_at`) VALUES
(1, 'ola', 'ola@gmail.com', '$2y$10$J5kf5gNuWoFmSxDQhuWKBueY20dHdVqEaXA8DXLppaNV4oiFCkQ9e', '2025-03-25 19:13:49'),
(2, 'hola', 'hola@gmail.com', '$2y$10$hgItUp/jg/u/5LaFX41.Re2AI5UzKRTbJpnB6m54D5ItCiZFKnPpm', '2025-03-26 19:29:13');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indices de la tabla `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indices de la tabla `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indices de la tabla `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT de la tabla `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT de la tabla `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Filtros para la tabla `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
