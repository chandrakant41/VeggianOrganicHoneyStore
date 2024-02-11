-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3307:tcp
-- Generation Time: Nov 10, 2023 at 04:40 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `shop_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(255) NOT NULL,
  `user_id` int(255) NOT NULL,
  `pid` int(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` varchar(255) NOT NULL,
  `quantity` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`id`, `user_id`, `pid`, `name`, `price`, `quantity`, `image`) VALUES
(41, 6, 6, 'lavender honey', '500', '1', 'img5.png');

-- --------------------------------------------------------

--
-- Table structure for table `message`
--

CREATE TABLE `message` (
  `id` int(255) NOT NULL,
  `user_id` int(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `number` varchar(255) NOT NULL,
  `message` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `message`
--

INSERT INTO `message` (`id`, `user_id`, `name`, `email`, `number`, `message`) VALUES
(13, 5, 'pratik', 'pratik@gmail.com', '2233445566', 'it\'s an incredible experience to test delicious honey '),
(14, 3, 'Akash', 'Akash07@gmail.com', '1122334455', 'the product was good and also the service was top notch.\r\ni like it.'),
(15, 6, 'Abhishekh', 'Abhishekh@gmail.com', '6677889955', 'the product was good and also the service was top notch. i like it.\r\npurity of your honey is awesome!');

-- --------------------------------------------------------

--
-- Table structure for table `order`
--

CREATE TABLE `order` (
  `id` int(255) NOT NULL,
  `user_id` int(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `number` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `method` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `total_products` varchar(255) NOT NULL,
  `total_price` varchar(255) NOT NULL,
  `placed_on` varchar(255) NOT NULL,
  `payment_status` varchar(255) NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order`
--

INSERT INTO `order` (`id`, `user_id`, `name`, `number`, `email`, `method`, `address`, `total_products`, `total_price`, `placed_on`, `payment_status`) VALUES
(5, 3, 'Akash', '1122334455', 'Akash07@gmail.com', 'cash on delivery', 'flat no.,,Karad,Maharashtra,India,', 'buckwheat honey(1)', '1200', '05-Nov-2023', 'completes'),
(6, 3, 'Akash', '1122334455', 'Akash07@gmail.com', 'cash on delivery', 'flat no.,,Karad,Maharashtra,India,', 'lavender honey(3)', '1500', '06-Nov-2023', 'completes'),
(10, 5, 'pratik', '2233445566', 'pratik@gmail.com', 'cash on delivery', 'flat no.,At/p Kusur ,Karad,Maharashtra,India,', 'lavender honey(1),buckwheat honey(1)', '1700', '07-Nov-2023', 'completes'),
(11, 5, 'pratik', '2233445566', 'pratik@gmail.com', 'cash on delivery', 'flat no.,At/p Kusur,Karad,Maharashtra,India,', 'manuka honey(1),Honey Mangrove(1)', '2300', '07-Nov-2023', 'pending'),
(12, 4, 'karan', '6677889955', 'karan@gmail.com', 'cash on delivery', 'flat no.,At/p pusesavali,Karad,Maharashtra,India,', 'Acacia Honey(1),lavender honey(1)', '1900', '07-Nov-2023', 'pending'),
(13, 6, 'Abhishekh', '3344556677', 'Abhishekh@gmail.com', 'cash on delivery', 'flat no.,At/p Rajmachi,Karad,Maharashtra,India,', 'Acacia Honey(1)', '1400', '10-Nov-2023', 'completes'),
(14, 6, 'Abhishekh', '3344556677', 'Abhishekh@gmail.com', 'cash on delivery', 'At/p kolewadi tal-karad dist-satara,Kolewadi,Maharashtra,India,415103', 'wildflower honey(1)', '411', '10-Nov-2023', 'pending'),
(15, 3, 'Akash', '2233445566', 'Akash07@gmail.com', 'cash on delivery', 'At/p shamgav,Karad,Maharashtra,India,415103', 'wildflower honey(1)', '411', '10-Nov-2023', 'pending');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` varchar(255) NOT NULL,
  `product_details` varchar(255) NOT NULL,
  `image` varchar(355) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `price`, `product_details`, `image`) VALUES
(5, 'wildflower honey', '900', 'Wildflower honey is a type of honey that is produced by bees from the nectar of various wildflowers. Wildflower honey has a distinct flavor that can very depending on the types of wildflowers the bees have forged. it often has a rich , floral taste with a', 'img8.png'),
(6, 'lavender honey', '500', 'lavender honey that is made from pollinating lavender blossoms is different from standard clover honey infused with lavender buds.', 'img5.png'),
(11, 'buckwheat honey', '1200', 'Buckwheat Honey is dark in color, often appearing as deep brown or purple. it has a strong, bold flavor with molasses and malt undertones.\r\nIt is produced by honeybees that primarily feed on the nectar of buckwheat flowers ,making it a monofloral honey.\r\n', 'img6.png'),
(14, 'manuka honey', '1000', 'Manuka honey is renowned for its non peroxide activity, which sets it apart from regular honey. this activity is often measured using the MGO rating which indicates its antibacterial strength.', 'img9.png'),
(15, 'Honey Mangrove', '1300', 'Harvested from the mangroves of the Sunderbans, this rare and exotic honey provides livelihood to the indigenous people of the Sunderbans. Made by Apis Mellifera species of bees by collecting the nectar from thousands of mangrove flowers, this honey will ', 'img17.png'),
(16, 'Acacia Honey', '1400', 'Acacia honey, also known as locust honey, is derived from the nectar of the Robinia pseudoacacia flower. It has a light, almost transparent color and stays liquid for longer, prolonging its shelf life. Acacia honey may aid wound healing, improve acne, and', 'img21.png');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `user_type` varchar(255) NOT NULL DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `user_type`) VALUES
(2, 'Chandrakant ', 'chandrakant05@gmail.com', 'chandrakant', 'admin'),
(3, 'Akash', 'Akash07@gmail.com', 'akash', 'user'),
(4, 'karan', 'karan@gmail.com', 'karan', 'user'),
(5, 'pratik', 'pratik@gmail.com', 'pratik', 'user'),
(6, 'Abhishekh', 'Abhishekh@gmail.com', 'abhishekh', 'user');

-- --------------------------------------------------------

--
-- Table structure for table `wishlist`
--

CREATE TABLE `wishlist` (
  `id` int(255) NOT NULL,
  `user_id` int(255) NOT NULL,
  `pid` int(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` varchar(255) NOT NULL,
  `image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `wishlist`
--

INSERT INTO `wishlist` (`id`, `user_id`, `pid`, `name`, `price`, `image`) VALUES
(25, 6, 5, 'wildflower honey', '411', 'img8.png');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `message`
--
ALTER TABLE `message`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order`
--
ALTER TABLE `order`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wishlist`
--
ALTER TABLE `wishlist`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `message`
--
ALTER TABLE `message`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `order`
--
ALTER TABLE `order`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `wishlist`
--
ALTER TABLE `wishlist`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
