-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 12, 2026 at 05:21 PM
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
-- Database: `all_in_one_bazaar`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `name`, `email`, `password`, `created_at`) VALUES
(1, 'Super Admin', 'admin@allinonebazaar.com', 'admin123', '2025-12-23 14:14:22');

-- --------------------------------------------------------

--
-- Table structure for table `carts`
--

CREATE TABLE `carts` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `prod_qty` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(191) NOT NULL,
  `slug` varchar(191) NOT NULL,
  `description` mediumtext DEFAULT NULL,
  `image` varchar(191) DEFAULT NULL,
  `status` tinyint(4) DEFAULT 0 COMMENT '0=Visible, 1=Hidden',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `slug`, `description`, `image`, `status`, `created_at`) VALUES
(41, 'Mobile', 'Mobile', 'Mobile', 'mobile_1776762265.jpg', 0, '2026-04-21 09:04:25'),
(42, 'Laptop', 'Laptop', 'Laptop', 'laptop_1776763134.jfif', 0, '2026-04-21 09:18:54'),
(43, 'Fashion', 'Fashion', 'Fashion', 'fashion_1776763852.webp', 0, '2026-04-21 09:30:52'),
(44, 'Books', 'Books', 'Books', 'books_1776764332.webp', 0, '2026-04-21 09:38:52'),
(45, 'Electronics', 'Electronics', 'Electronics', 'electronics_1776764968.webp', 0, '2026-04-21 09:49:28');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `name`, `email`, `subject`, `message`, `created_at`) VALUES
(1, 'Ram', 'ram@gmail.com', 'AC', 'Offers page kayre Add kar so', '2025-12-28 10:12:29');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `tracking_no` varchar(50) NOT NULL,
  `total_price` decimal(10,2) NOT NULL,
  `address` text NOT NULL,
  `payment_method` varchar(50) DEFAULT 'COD',
  `status` varchar(20) DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `order_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `tracking_no`, `total_price`, `address`, `payment_method`, `status`, `created_at`, `order_date`) VALUES
(27, 4, 'ORD75087', 2798.00, 'home', 'COD', 'Completed', '2026-04-20 15:01:02', '2026-04-20 15:01:02'),
(28, 5, 'ORD42722', 134900.00, 'homee', 'UPI', 'Pending', '2026-04-21 09:56:46', '2026-04-21 09:56:46'),
(29, 5, 'ORD70478', 897.00, 'Home', 'UPI', 'Processing', '2026-04-21 10:21:43', '2026-04-21 10:21:43'),
(30, 5, 'ORD52936', 399.00, 'ok', 'COD', 'Pending', '2026-04-21 14:07:27', '2026-04-21 14:07:27'),
(31, 5, 'ORD58255', 1099.00, 'aaa', 'COD', 'Completed', '2026-04-21 16:20:19', '2026-04-21 16:20:19'),
(32, 5, 'ORD77923', 85999.00, 'rajkot\r\n1323244545\r\n\r\n', 'UPI', 'Cancelled', '2026-04-21 18:31:21', '2026-04-21 18:31:21'),
(33, 5, 'ORD55180', 11999.00, 'mmm', 'CARD', 'Processing', '2026-04-22 11:29:45', '2026-04-22 11:29:45'),
(34, 5, 'ORD52225', 113079.00, 'hh', 'COD', 'Pending', '2026-04-22 13:07:37', '2026-04-22 13:07:37');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `product_name`, `quantity`, `price`) VALUES
(17, 28, 317, 'MacBook Air M3', 1, 134900.00),
(18, 29, 321, 'The Psychology of Money', 3, 299.00),
(19, 30, 320, 'Atomic Habits by James Clear', 1, 399.00),
(20, 31, 323, 'boAt Airdopes 141 TWS Earbuds', 1, 1099.00),
(21, 32, 327, 'Dell Inspiron 14', 1, 85999.00),
(22, 33, 336, 'JBL Flip 6 Bluetooth Speaker', 1, 11999.00),
(23, 34, 337, 'Canon EOS M50 Mark II', 2, 55990.00),
(24, 34, 333, 'Unisex Hoodie Sweatshirt', 1, 1099.00);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `name` varchar(191) NOT NULL,
  `slug` varchar(191) NOT NULL,
  `small_description` mediumtext DEFAULT NULL,
  `description` longtext DEFAULT NULL,
  `original_price` decimal(10,2) NOT NULL,
  `selling_price` decimal(10,2) NOT NULL,
  `image` varchar(191) NOT NULL,
  `qty` int(11) NOT NULL,
  `status` tinyint(4) DEFAULT 0 COMMENT '0=Visible, 1=Hidden',
  `trending` tinyint(4) DEFAULT 0 COMMENT '0=No, 1=Yes',
  `meta_title` varchar(191) DEFAULT NULL,
  `meta_keywords` mediumtext DEFAULT NULL,
  `meta_description` mediumtext DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `category_id`, `name`, `slug`, `small_description`, `description`, `original_price`, `selling_price`, `image`, `qty`, `status`, `trending`, `meta_title`, `meta_keywords`, `meta_description`, `created_at`) VALUES
(315, 41, 'Samsung Galaxy S24 Ultra', 'samsung-galaxy-s24-ultra', 'Flagship smartphone with S Pen', 'Samsung Galaxy S24 Ultra with 200MP camera, S Pen, Snapdragon 8 Gen 3, 12GB RAM, 256GB Storage, 5000mAh battery.', 134999.00, 119990.00, 'samsung-galaxy-s24-ultra_1776762800.jfif', 25, 0, 1, '', '', '', '2026-04-21 09:13:20'),
(316, 41, 'iPhone 15 Pro Max', 'iPhone 15 Pro Max', 'iPhone 15 Pro Max', 'Apple iPhone 15 Pro Max with A17 Pro chip, 48MP camera, Titanium design, USB-C, 256GB storage.', 159900.00, 159900.00, 'iphone-15-pro-max_1776762982.jfif', 15, 0, 0, '', '', '', '2026-04-21 09:16:22'),
(317, 42, 'MacBook Air M3', 'macbook-air-m3', 'Thin and powerful Apple laptop', 'Apple MacBook Air with M3 chip, 15.3-inch Liquid Retina display, 8GB RAM, 256GB SSD, 18-hour battery.', 134900.00, 134900.00, 'macbook-air-m3_1776763300.jpg', 10, 0, 0, '', '', '', '2026-04-21 09:21:40'),
(318, 42, 'HP Pavilion 15', 'hp-pavilion-15', 'Everyday laptop', 'HP Pavilion 15 with Intel Core i5 13th Gen, 16GB RAM, 512GB SSD, 15.6\" FHD display, Windows 11.', 72999.00, 59999.00, 'hp-pavilion-15_1776763628.jpg', 20, 0, 0, '', '', '', '2026-04-21 09:27:08'),
(319, 43, 'Men Slim Fit Casual Shirt', 'men-slim-fit-casual-shirt', 'Premium cotton shirt', 'Men Slim Fit Cotton Casual Shirt, available in Blue, White, Black. Sizes S to XXL. Comfortable and stylish.', 1199.00, 1199.00, 'men-slim-fit-casual-shirt_1776764076.jpg', 100, 0, 0, '', '', '', '2026-04-21 09:34:36'),
(320, 44, 'Atomic Habits by James Clear', 'atomic-habits', 'Best-selling self-help book', 'An easy and proven way to build good habits and break bad ones. Over 10 million copies sold worldwide.', 699.00, 399.00, 'atomic-habits-by-james-clear_1776764564.jpg', 200, 0, 1, '', '', '', '2026-04-21 09:42:44'),
(321, 44, 'The Psychology of Money', 'psychology-of-money', 'Personal finance classic', 'By Morgan Housel. Timeless lessons on wealth, greed, and happiness. A must-read for everyone.', 499.00, 299.00, 'the-psychology-of-money_1776764807.jpg', 150, 0, 1, '', '', '', '2026-04-21 09:46:47'),
(322, 45, 'Sony WH-1000XM5 Headphones', 'sony-wh1000xm5', 'Best noise-cancelling headphones', 'Sony WH-1000XM5 Wireless Noise Cancelling Headphones. 30-hour battery, multipoint connection, premium sound.', 29990.00, 24990.00, 'sony-wh-1000xm5-headphones_1776765154.jpg', 15, 0, 1, '', '', '', '2026-04-21 09:52:34'),
(323, 45, 'boAt Airdopes 141 TWS Earbuds', ' boat-airdopes-141', 'Wireless earbuds', 'boAt Airdopes 141 TWS Earbuds with 42H playtime, ENx noise cancelling, IPX4, Bluetooth v5.3.', 2999.00, 1099.00, 'boat-airdopes-141-tws-earbuds_1776765295.jpg', 99, 0, 0, '', '', '', '2026-04-21 09:54:55'),
(324, 41, 'OnePlus 12', 'OnePlus 12', 'High-performance flagship', 'OnePlus 12 5G with Snapdragon 8 Gen 3, 100W SuperVOOC charging, 50MP Hasselblad camera, 16GB RAM, 512GB Storage.', 69999.00, 64999.00, 'oneplus-12_1776792563.jpg', 20, 0, 1, '', '', '', '2026-04-21 17:29:23'),
(325, 41, 'Redmi Note 13 Pro', 'redmi-note-13-pro', 'redmi-note-13-pro', 'Redmi Note 13 Pro 5G with 200MP camera, 1.5K AMOLED display, Snapdragon 7s Gen 2, 8GB RAM, 256GB Storage.', 29999.00, 29999.00, 'redmi-note-13-pro_1776793811.jpg', 50, 0, 0, '', '', '', '2026-04-21 17:50:11'),
(326, 41, 'Realme GT 5 Pro', 'realme-gt-5-pro', 'Premium performance phone', 'Realme GT 5 Pro with Snapdragon 8 Gen 3, 5400mAh battery, telephoto camera, 144Hz AMOLED display.', 45999.00, 45999.00, 'realme-gt-5-pro_1776794897.jpg', 30, 0, 1, '', '', '', '2026-04-21 18:08:17'),
(327, 42, 'Dell Inspiron 14', 'dell-inspiron-14', 'dell-inspiron-14', 'Dell Inspiron 14 with Intel Core i7 13th Gen, 16GB RAM, 512GB SSD, FHD display, Backlit Keyboard, Windows 11.', 85999.00, 85999.00, 'dell-inspiron-14_1776796106.jpg', 15, 0, 0, '', '', '', '2026-04-21 18:28:26'),
(328, 42, 'ASUS ROG Strix G16', 'asus-rog-strix-g16', 'Ultimate gaming laptop', 'ASUS ROG Strix G16 Gaming Laptop, Intel Core i9, RTX 4070 8GB, 16GB RAM, 1TB NVMe Gen4 SSD, 165Hz Display.', 169990.00, 169990.00, 'asus-rog-strix-g16_1776841263.jpg', 10, 0, 1, '', '', '', '2026-04-22 07:01:03'),
(329, 42, 'Lenovo IdeaPad Slim 3', 'lenovo-ideapad-slim-3', 'Lightweight student laptop', '', 45990.00, 45990.00, 'lenovo-ideapad-slim-3_1776841544.jpg', 40, 0, 1, '', '', '', '2026-04-22 07:05:44'),
(330, 43, 'Women Floral Kurti Set', 'women-floral-kurti-set', 'Elegant ethnic wear', 'Women Floral Printed Kurti with Palazzo Pants. Rayon fabric, available in multiple colours. Sizes S-XXL.', 1199.00, 849.00, 'women-floral-kurti-set_1776845664.jpg', 80, 0, 1, '', '', '', '2026-04-22 08:14:24'),
(331, 43, 'Men Denim Jeans', 'men-denim-jeans', 'Classic stretch denim', 'Men\'s Classic Stretch Denim Jeans. Slim straight fit, durable material. Available in Light Blue, Dark Wash.', 1999.00, 999.00, 'men-denim-jeans_1776846194.avif', 120, 0, 0, '', '', '', '2026-04-22 08:23:14'),
(332, 43, 'Women Western Dress', 'women-western-dress', 'Trendy party dress', ' Women\'s A-line Party Wear Dress. Comfortable crepe fabric, sleeveless design, vibrant prints.', 1599.00, 899.00, 'women-western-dress_1776846538.jpg', 60, 0, 0, '', '', '', '2026-04-22 08:28:58'),
(333, 43, 'Unisex Hoodie Sweatshirt', 'unisex-hoodie-sweatshirt', 'Winter wear essential', 'Cozy fleece unisex hoodie with front pocket. Soft material perfect for winters. Available in Black, Grey.', 1899.00, 1099.00, 'unisex-hoodie-sweatshirt_1776846825.jpg', 150, 0, 0, '', '', '', '2026-04-22 08:33:45'),
(334, 44, 'Harry Potter Box Set', 'harry-potter-box-set', 'Complete 7 books collection', 'The complete Harry Potter 7-Book Collection by J.K. Rowling. A magical adventure for all ages.', 3999.00, 2599.00, 'harry-potter-box-set_1776848910.jpg', 40, 0, 0, '', '', '', '2026-04-22 09:08:30'),
(335, 45, 'Samsung 55\" 4K Smart TV', 'samsung-55-4k-tv', 'Crystal 4K UHD TV', 'Samsung 138 cm (55 inches) Crystal 4K Vivid Pro Ultra HD Smart LED TV. PurColor, HDR10+, Tizen OS.', 64990.00, 64990.00, 'samsung-55---4k-smart-tv_1776856406.jpg', 10, 0, 1, '', '', '', '2026-04-22 11:13:26'),
(336, 45, 'JBL Flip 6 Bluetooth Speaker', 'jbl-flip-6-speaker', 'Portable waterproof speaker', 'JBL Flip 6 Wireless Portable Bluetooth Speaker with Bold JBL Pro Sound, IP67 Waterproof, 12 Hours Playtime.', 11999.00, 11999.00, 'jbl-flip-6-bluetooth-speaker_1776856522.webp', 25, 0, 0, '', '', '', '2026-04-22 11:15:22'),
(337, 45, 'Canon EOS M50 Mark II', 'Canon EOS M50 Mark II', 'Mirrorless vlogging camera', 'Canon EOS M50 Mark II Mirrorless Camera with 15-45mm Lens. 24.1 MP, Dual Pixel AF, 4K Video recording.', 55990.00, 55990.00, 'canon-eos-m50-mark-ii_1776856652.jfif', 12, 0, 1, 'anshul', 'anshul', '', '2026-04-22 11:17:32');

-- --------------------------------------------------------

--
-- Table structure for table `product_images`
--

CREATE TABLE `product_images` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int(11) NOT NULL,
  `site_name` varchar(150) DEFAULT NULL,
  `site_email` varchar(150) DEFAULT NULL,
  `site_phone` varchar(50) DEFAULT NULL,
  `site_address` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `full_name`, `email`, `password`, `phone`, `address`, `created_at`) VALUES
(5, 'Anshul Dave', 'ahdave1573@gmail.com', '$2y$10$MU7aqgLI5.9rvMGYPCMrfO7X4Dcnxt67dWenmaRDsDk/edcyi5tQC', '8849919418', 'Rajkot', '2026-04-21 09:44:17');

-- --------------------------------------------------------

--
-- Table structure for table `wishlist`
--

CREATE TABLE `wishlist` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `wishlist`
--

INSERT INTO `wishlist` (`id`, `user_id`, `product_id`, `created_at`) VALUES
(20, 5, 315, '2026-04-21 10:23:18');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `carts`
--
ALTER TABLE `carts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `product_images`
--
ALTER TABLE `product_images`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `wishlist`
--
ALTER TABLE `wishlist`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_like` (`user_id`,`product_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `carts`
--
ALTER TABLE `carts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=338;

--
-- AUTO_INCREMENT for table `product_images`
--
ALTER TABLE `product_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `wishlist`
--
ALTER TABLE `wishlist`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `carts`
--
ALTER TABLE `carts`
  ADD CONSTRAINT `carts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `carts_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
