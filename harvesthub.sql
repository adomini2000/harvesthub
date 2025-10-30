-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Oct 28, 2025 at 10:35 PM
-- Server version: 8.0.31
-- PHP Version: 8.0.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `harvesthub`
--

-- --------------------------------------------------------

--
-- Table structure for table `buyer_points`
--

DROP TABLE IF EXISTS `buyer_points`;
CREATE TABLE IF NOT EXISTS `buyer_points` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `buyer_id` bigint UNSIGNED NOT NULL,
  `total_points` decimal(10,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `buyer_points_buyer_id_foreign` (`buyer_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `buyer_points`
--

INSERT INTO `buyer_points` (`id`, `buyer_id`, `total_points`, `created_at`, `updated_at`) VALUES
(1, 1, '32.00', '2025-10-26 17:53:00', '2025-10-28 09:56:40'),
(2, 2, '100.00', '2025-10-26 17:53:00', '2025-10-26 17:53:00');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
CREATE TABLE IF NOT EXISTS `categories` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `icon` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `color` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '#7CB342',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `categories_slug_unique` (`slug`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `slug`, `icon`, `color`, `created_at`, `updated_at`) VALUES
(1, 'Fresh Produce', 'fresh-produce', 'fas fa-leaf', '#7CB342', '2025-10-27 21:37:02', '2025-10-27 21:37:02'),
(2, 'Animal Products', 'animal-products', 'fas fa-egg', '#FFA726', '2025-10-27 21:37:11', '2025-10-27 21:37:11'),
(3, 'Ingredient Bundle', 'ingredient-bundle', 'fas fa-box-open', '#EF5350', '2025-10-27 21:37:19', '2025-10-27 21:37:19');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2025_10_27_013455_add_role_to_users_table', 2),
(6, '2025_10_27_013500_create_sellers_table', 2),
(7, '2025_10_27_013505_create_riders_table', 2),
(8, '2025_10_27_013509_create_products_table', 2),
(9, '2025_10_27_013514_create_orders_table', 2),
(10, '2025_10_27_013519_create_order_items_table', 2),
(11, '2025_10_27_013524_create_buyer_points_table', 2),
(12, '2025_10_27_013528_create_ratings_table', 2),
(13, '2025_10_28_044507_create_categories_table', 3);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
CREATE TABLE IF NOT EXISTS `orders` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `buyer_id` bigint UNSIGNED NOT NULL,
  `seller_id` bigint UNSIGNED NOT NULL,
  `rider_id` bigint UNSIGNED DEFAULT NULL,
  `order_number` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  `points_discount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `total` decimal(10,2) NOT NULL,
  `total_weight_kg` decimal(8,2) NOT NULL,
  `status` enum('ordered','preparing','ready_for_pickup','picked_up','out_for_delivery','delivered','cancelled') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'ordered',
  `delivery_address` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `eta` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `orders_order_number_unique` (`order_number`),
  KEY `orders_buyer_id_foreign` (`buyer_id`),
  KEY `orders_seller_id_foreign` (`seller_id`),
  KEY `orders_rider_id_foreign` (`rider_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `buyer_id`, `seller_id`, `rider_id`, `order_number`, `subtotal`, `points_discount`, `total`, `total_weight_kg`, `status`, `delivery_address`, `eta`, `created_at`, `updated_at`) VALUES
(1, 1, 1, NULL, 'ORD-68FFB70DCD6AB', '600.00', '0.00', '600.00', '12.00', 'ready_for_pickup', '123 Test Street', NULL, '2025-10-27 10:16:45', '2025-10-27 16:26:17'),
(2, 1, 1, 1, 'ORD-68FFB74F8F451', '300.00', '12.00', '288.00', '6.00', 'delivered', '123 Test Street', '15 minutes', '2025-10-27 10:17:51', '2025-10-27 16:20:39'),
(3, 1, 1, 1, 'ORD-68FFE7BE1BC2D', '300.00', '100.00', '200.00', '6.00', 'delivered', '123 TEST STREET', NULL, '2025-10-27 13:44:30', '2025-10-27 16:29:37');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

DROP TABLE IF EXISTS `order_items`;
CREATE TABLE IF NOT EXISTS `order_items` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `order_id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `quantity` int NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `weight_kg` decimal(8,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `order_items_order_id_foreign` (`order_id`),
  KEY `order_items_product_id_foreign` (`product_id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `quantity`, `price`, `weight_kg`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 2, '50.00', '2.00', '2025-10-27 10:16:45', '2025-10-27 10:16:45'),
(2, 1, 2, 2, '250.00', '10.00', '2025-10-27 10:16:45', '2025-10-27 10:16:45'),
(3, 2, 1, 1, '50.00', '1.00', '2025-10-27 10:17:51', '2025-10-27 10:17:51'),
(4, 2, 2, 1, '250.00', '5.00', '2025-10-27 10:17:51', '2025-10-27 10:17:51'),
(5, 3, 1, 1, '50.00', '1.00', '2025-10-27 13:44:30', '2025-10-27 13:44:30'),
(6, 3, 2, 1, '250.00', '5.00', '2025-10-27 13:44:30', '2025-10-27 13:44:30');

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
CREATE TABLE IF NOT EXISTS `password_resets` (
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
CREATE TABLE IF NOT EXISTS `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
CREATE TABLE IF NOT EXISTS `products` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `seller_id` bigint UNSIGNED NOT NULL,
  `category_id` bigint UNSIGNED DEFAULT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `price` decimal(10,2) NOT NULL,
  `stock` int NOT NULL DEFAULT '0',
  `weight_kg` decimal(8,2) NOT NULL DEFAULT '0.50',
  `image_url` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `products_seller_id_foreign` (`seller_id`),
  KEY `products_category_id_foreign` (`category_id`)
) ENGINE=MyISAM AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `seller_id`, `category_id`, `name`, `description`, `price`, `stock`, `weight_kg`, `image_url`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'Fresh Tomatoes', 'High quality fresh tomatoes', '45.00', 50, '1.00', 'products/aEjUZ0AEK3irhUxeejOXQbqOI8tpo6ADJL7IOMx6.jpg', 1, '2025-10-26 17:53:00', '2025-10-28 09:53:17'),
(2, 1, 3, 'Rice (5kg)', 'High quality rice (5kg)', '250.00', 46, '5.00', NULL, 1, '2025-10-26 17:53:00', '2025-10-28 09:30:08'),
(3, 1, 2, 'Chicken Eggs (12pcs)', 'High quality chicken eggs (12pcs)', '120.00', 80, '0.80', NULL, 1, '2025-10-26 17:53:00', '2025-10-28 09:29:51'),
(4, 1, 2, 'Fresh Fish', 'High quality fresh fish', '180.00', 30, '1.50', NULL, 1, '2025-10-26 17:53:00', '2025-10-28 09:29:51'),
(5, 1, 1, 'Mangoes (1kg)', 'High quality mangoes (1kg)', '100.00', 60, '1.00', NULL, 1, '2025-10-26 17:53:00', '2025-10-28 09:45:13'),
(6, 1, 3, 'Cooking Oil (1L)', 'High quality cooking oil (1l)', '150.00', 40, '1.20', NULL, 1, '2025-10-26 17:53:00', '2025-10-28 09:30:08'),
(7, 1, 1, 'Onions (1kg)', 'High quality onions (1kg)', '80.00', 70, '1.00', NULL, 1, '2025-10-26 17:53:00', '2025-10-28 09:28:25'),
(8, 1, 1, 'Garlic (500g)', 'High quality garlic (500g)', '90.00', 50, '0.50', NULL, 1, '2025-10-26 17:53:00', '2025-10-28 09:28:25'),
(9, 1, 1, 'Potatoes (2kg)', 'High quality potatoes (2kg)', '120.00', 45, '2.00', NULL, 1, '2025-10-26 17:53:00', '2025-10-28 09:28:25'),
(10, 1, 2, 'Fresh Milk (1L)', 'High quality fresh milk (1l)', '85.00', 55, '1.10', NULL, 1, '2025-10-26 17:53:00', '2025-10-28 09:29:51'),
(11, 2, 1, 'Fresh Tomatoes', 'High quality fresh tomatoes', '50.00', 100, '1.00', 'products/EB0fHO6bXSZ5x5MLNWbwvLfgOVVQTIXVJzGx37TJ.jpg', 1, '2025-10-26 17:53:00', '2025-10-28 09:54:23'),
(12, 2, 3, 'Rice (5kg)', 'High quality rice (5kg)', '250.00', 50, '5.00', NULL, 1, '2025-10-26 17:53:00', '2025-10-28 09:30:08'),
(13, 2, 2, 'Chicken Eggs (12pcs)', 'High quality chicken eggs (12pcs)', '120.00', 80, '0.80', NULL, 1, '2025-10-26 17:53:00', '2025-10-28 09:29:51'),
(14, 2, 2, 'Fresh Fish', 'High quality fresh fish', '180.00', 30, '1.50', NULL, 1, '2025-10-26 17:53:00', '2025-10-28 09:29:51'),
(15, 2, 1, 'Mangoes (1kg)', 'High quality mangoes (1kg)', '100.00', 60, '1.00', NULL, 1, '2025-10-26 17:53:00', '2025-10-28 09:28:25'),
(16, 2, 3, 'Cooking Oil (1L)', 'High quality cooking oil (1l)', '150.00', 40, '1.20', NULL, 1, '2025-10-26 17:53:00', '2025-10-28 09:30:08'),
(17, 2, 1, 'Onions (1kg)', 'High quality onions (1kg)', '80.00', 70, '1.00', NULL, 1, '2025-10-26 17:53:00', '2025-10-28 09:28:25'),
(18, 2, 1, 'Garlic (500g)', 'High quality garlic (500g)', '90.00', 50, '0.50', NULL, 1, '2025-10-26 17:53:00', '2025-10-28 09:28:25'),
(19, 2, 1, 'Potatoes (2kg)', 'High quality potatoes (2kg)', '120.00', 45, '2.00', NULL, 1, '2025-10-26 17:53:00', '2025-10-28 09:28:25'),
(20, 2, 2, 'Fresh Milk (1L)', 'High quality fresh milk (1l)', '85.00', 55, '1.10', NULL, 1, '2025-10-26 17:53:00', '2025-10-28 09:29:51');

-- --------------------------------------------------------

--
-- Table structure for table `ratings`
--

DROP TABLE IF EXISTS `ratings`;
CREATE TABLE IF NOT EXISTS `ratings` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `order_id` bigint UNSIGNED NOT NULL,
  `buyer_id` bigint UNSIGNED NOT NULL,
  `seller_id` bigint UNSIGNED NOT NULL,
  `rating` int UNSIGNED NOT NULL DEFAULT '5',
  `comment` text COLLATE utf8mb4_unicode_ci,
  `bonus_points` int NOT NULL DEFAULT '10',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ratings_order_id_foreign` (`order_id`),
  KEY `ratings_buyer_id_foreign` (`buyer_id`),
  KEY `ratings_seller_id_foreign` (`seller_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ratings`
--

INSERT INTO `ratings` (`id`, `order_id`, `buyer_id`, `seller_id`, `rating`, `comment`, `bonus_points`, `created_at`, `updated_at`) VALUES
(1, 3, 1, 1, 5, NULL, 10, '2025-10-28 09:56:36', '2025-10-28 09:56:36'),
(2, 2, 1, 1, 5, NULL, 10, '2025-10-28 09:56:40', '2025-10-28 09:56:40');

-- --------------------------------------------------------

--
-- Table structure for table `riders`
--

DROP TABLE IF EXISTS `riders`;
CREATE TABLE IF NOT EXISTS `riders` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED NOT NULL,
  `vehicle_type` enum('bike','motorcycle','car') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'bike',
  `max_capacity_kg` decimal(8,2) NOT NULL DEFAULT '10.00',
  `status` enum('normal','busy','closed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'normal',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `riders_user_id_foreign` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `riders`
--

INSERT INTO `riders` (`id`, `user_id`, `vehicle_type`, `max_capacity_kg`, `status`, `created_at`, `updated_at`) VALUES
(1, 5, 'bike', '10.00', 'normal', '2025-10-26 17:53:00', '2025-10-27 16:29:37'),
(2, 6, 'motorcycle', '25.00', 'normal', '2025-10-26 17:53:00', '2025-10-26 17:53:00'),
(3, 7, 'car', '50.00', 'normal', '2025-10-27 16:43:37', '2025-10-27 16:43:37');

-- --------------------------------------------------------

--
-- Table structure for table `sellers`
--

DROP TABLE IF EXISTS `sellers`;
CREATE TABLE IF NOT EXISTS `sellers` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED NOT NULL,
  `shop_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `shop_description` text COLLATE utf8mb4_unicode_ci,
  `subscription_paid` tinyint(1) NOT NULL DEFAULT '0',
  `rating` decimal(3,2) NOT NULL DEFAULT '0.00',
  `total_ratings` int NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sellers_user_id_foreign` (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sellers`
--

INSERT INTO `sellers` (`id`, `user_id`, `shop_name`, `shop_description`, `subscription_paid`, `rating`, `total_ratings`, `created_at`, `updated_at`) VALUES
(1, 3, 'Shop 1', 'Quality products for everyone', 1, '5.00', 2, '2025-10-26 17:53:00', '2025-10-28 09:56:40'),
(2, 4, 'Shop 2', 'Quality products for everyone', 1, '4.50', 10, '2025-10-26 17:53:00', '2025-10-26 17:53:00');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('buyer','seller','rider') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'buyer',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `role`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'John Buyer', 'buyer@test.com', 'buyer', NULL, '$2y$10$MeFxeaPXIdWVAlu.Qe6lg.TcAOf7o0iAHq/ZY4DP4xavBbIpbNFNu', NULL, '2025-10-26 17:52:59', '2025-10-26 17:52:59'),
(2, 'Jane Buyer', 'buyer2@test.com', 'buyer', NULL, '$2y$10$sgzi2QB3tlRumnZipcV/QePubA1u/7jAJZOnyjdKFwH0zciYBpn8O', NULL, '2025-10-26 17:53:00', '2025-10-26 17:53:00'),
(3, 'Maria Seller', 'seller@test.com', 'seller', NULL, '$2y$10$QAa7DiXC1/wUYQwGkXuibetBGc0GQ6FklEItrzgyCMAtSMQ/CuVUW', 'xCi4Mwp4VGuOjbqWyesoWpziMYdQqu7s6p4Ikpbg3mZAFCKAvmZQSrUJLLMa', '2025-10-26 17:53:00', '2025-10-26 17:53:00'),
(4, 'Carlos Seller', 'seller2@test.com', 'seller', NULL, '$2y$10$UcpPL9aKFWlsFhUo0tx6J.Y1efwwLwMtDEJsKBcuQQLmIoEkWjW7O', NULL, '2025-10-26 17:53:00', '2025-10-26 17:53:00'),
(5, 'Pedro Rider', 'rider@test.com', 'rider', NULL, '$2y$10$HZYj6TPT0h9rAe8q4aEnTOyEnqEdhmcZkgLlOAWhyztYIdxjRpaPe', NULL, '2025-10-26 17:53:00', '2025-10-26 17:53:00'),
(6, 'Miguel Rider', 'rider2@test.com', 'rider', NULL, '$2y$10$tA8XKhrgXJiQvEUcd4e.EOTiEOiUr9yr5Z.wDJi7xTfOw2DDR.kNe', NULL, '2025-10-26 17:53:00', '2025-10-26 17:53:00'),
(7, 'Juan Rider', 'rider3@test.com', 'rider', NULL, '$2y$10$PJUyJ.pf0qhoaorALAYt..9SQF6uzlfvrUQtZNFjOuciHCD.6EDG6', NULL, '2025-10-27 16:43:37', '2025-10-27 16:43:37');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
