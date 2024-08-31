-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 20, 2024 at 04:13 PM
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
-- Database: `rp`
--

-- --------------------------------------------------------

--
-- Table structure for table `addproduct`
--

CREATE TABLE `addproduct` (
  `id` int(11) NOT NULL,
  `productname` varchar(255) NOT NULL,
  `product_sku` int(11) NOT NULL,
  `product_price` int(11) NOT NULL,
  `product_sale_price` int(11) NOT NULL,
  `category` int(11) NOT NULL,
  `image` blob NOT NULL,
  `status` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `addproduct`
--

INSERT INTO `addproduct` (`id`, `productname`, `product_sku`, `product_price`, `product_sale_price`, `category`, `image`, `status`) VALUES
(33, 'cup', 122, 110, 100, 14, 0x2e2e2f696d616765732f53637265656e73686f7420323032342d30372d3039203133333234322e706e67, 'active'),
(34, 'cup', 122, 100, 100, 14, 0x2e2e2f696d616765732f53637265656e73686f7420323032342d30372d3132203132313634392e706e67, 'inactive'),
(35, 'pencil', 1, 90, 100, 14, 0x2e2e2f696d616765732f53637265656e73686f7420323032342d30372d3132203132313633332e706e67, 'active'),
(36, 'cup', 122, 100, 110, 14, 0x2e2e2f696d616765732f53637265656e73686f7420323032342d30372d3132203132313633332e706e67, 'active');

-- --------------------------------------------------------

--
-- Table structure for table `add_sale`
--

CREATE TABLE `add_sale` (
  `id` int(11) NOT NULL,
  `storename` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `phone_no` int(11) NOT NULL,
  `city` text NOT NULL,
  `order_type` varchar(255) NOT NULL,
  `order_no` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `price` int(11) NOT NULL,
  `tracking_id` int(11) NOT NULL,
  `order_date` date NOT NULL,
  `discount` float NOT NULL,
  `shipping` int(11) NOT NULL,
  `free_shipping` varchar(255) NOT NULL,
  `cate_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `add_sale`
--

INSERT INTO `add_sale` (`id`, `storename`, `name`, `phone_no`, `city`, `order_type`, `order_no`, `item_id`, `price`, `tracking_id`, `order_date`, `discount`, `shipping`, `free_shipping`, `cate_id`) VALUES
(35, 1, 'Muhammad', 476888, 'Ahmed Nager', 'Single Item', 15, 33, 199, 1, '2024-07-14', 12, 100, '', 12),
(36, 1, 'musa', 2345, 'Darya Khan', 'Single Item', 1, 35, 13333, 12, '2024-07-14', 1234, 12, 'Free Shipping', 14),
(37, 1, 'Muhammad', 36375, 'Alipur', 'Single Item', 12, 35, 100, 1, '2024-07-15', 10, 119, '', 14),
(38, 1, 'Muhammad', 456, 'Arifwala', 'Multiple Item', 12, 33, 300, 1, '2024-07-17', 10, 119, 'Free Shipping', 12),
(39, 1, 'musa', 63737, 'Attock', 'Single Item', 654, 36, 120, 12, '2024-07-17', 234, 119, 'Free Shipping', 14),
(40, 1, 'musa', 63737, 'Attock', 'Single Item', 654, 36, 120, 12, '2024-07-17', 234, 119, 'Free Shipping', 14);

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `a_email` varchar(255) NOT NULL,
  `a_password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `a_email`, `a_password`) VALUES
(1, 'saher111@gmail.com', '1111');

-- --------------------------------------------------------

--
-- Table structure for table `calculator`
--

CREATE TABLE `calculator` (
  `id` int(11) NOT NULL,
  `sell` int(11) NOT NULL,
  `disc` int(11) NOT NULL,
  `category` int(11) NOT NULL,
  `shipping` int(11) NOT NULL,
  `free_shipping` text NOT NULL,
  `vat` float NOT NULL,
  `cost` int(11) NOT NULL,
  `expenses` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `calculator`
--

INSERT INTO `calculator` (`id`, `sell`, `disc`, `category`, `shipping`, `free_shipping`, `vat`, `cost`, `expenses`) VALUES
(162, 1479, 0, 14, 135, '', 16, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `id` int(11) NOT NULL,
  `categoryname` varchar(255) NOT NULL,
  `level_1` varchar(255) NOT NULL,
  `level_2` varchar(255) NOT NULL,
  `level_3` varchar(255) NOT NULL,
  `level_4` varchar(255) NOT NULL,
  `level_5` varchar(255) NOT NULL,
  `commission_per` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`id`, `categoryname`, `level_1`, `level_2`, `level_3`, `level_4`, `level_5`, `commission_per`) VALUES
(12, 'kitchen', 'kitchen1', 'kitchen2', 'kitchen3', 'kitchen4', 'kitchen5', 14);

-- --------------------------------------------------------

--
-- Table structure for table `item`
--

CREATE TABLE `item` (
  `id` int(11) NOT NULL,
  `item_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `item`
--

INSERT INTO `item` (`id`, `item_name`) VALUES
(1, 'cup');

-- --------------------------------------------------------

--
-- Table structure for table `store`
--

CREATE TABLE `store` (
  `id` int(11) NOT NULL,
  `storename` text NOT NULL,
  `description` text NOT NULL,
  `category` varchar(255) NOT NULL,
  `email` text NOT NULL,
  `password` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `store`
--

INSERT INTO `store` (`id`, `storename`, `description`, `category`, `email`, `password`) VALUES
(1, 'reys', '1323', '353', 'musa.azan786@gmail.com', '132'),
(2, 'reys', '1323', '353', 'musa.azan786@gmail.com', '132'),
(3, 'reys', '1323', '353', 'musa.azan786@gmail.com', '1111'),
(4, 'reys', '1323', '353', 'musa.azan786@gmail.com', '111'),
(5, 'reys', '1323', '353', 'musa.azan786@gmail.com', '111');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `addproduct`
--
ALTER TABLE `addproduct`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `add_sale`
--
ALTER TABLE `add_sale`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cate_id` (`cate_id`),
  ADD KEY `storename` (`storename`),
  ADD KEY `item_id` (`item_id`);

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `calculator`
--
ALTER TABLE `calculator`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `item`
--
ALTER TABLE `item`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `store`
--
ALTER TABLE `store`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `addproduct`
--
ALTER TABLE `addproduct`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `add_sale`
--
ALTER TABLE `add_sale`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `calculator`
--
ALTER TABLE `calculator`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=163;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `item`
--
ALTER TABLE `item`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `store`
--
ALTER TABLE `store`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `add_sale`
--
ALTER TABLE `add_sale`
  ADD CONSTRAINT `add_sale_ibfk_3` FOREIGN KEY (`storename`) REFERENCES `store` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `add_sale_ibfk_4` FOREIGN KEY (`item_id`) REFERENCES `addproduct` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
