-- phpMyAdmin SQL Dump
-- version 4.9.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 03, 2020 at 02:35 PM
-- Server version: 10.4.8-MariaDB
-- PHP Version: 7.1.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sherif`
--

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `OrderID` int(10) NOT NULL,
  `ClientName` varchar(255) CHARACTER SET utf8 NOT NULL,
  `City` varchar(100) CHARACTER SET utf8 NOT NULL DEFAULT '0',
  `ClientAddress` varchar(255) CHARACTER SET utf8 NOT NULL,
  `ClientPhone` varchar(100) CHARACTER SET utf8 NOT NULL,
  `OrderPrice` varchar(100) CHARACTER SET utf8 NOT NULL,
  `OrderModel` int(11) NOT NULL,
  `OrderDate` date NOT NULL,
  `UserID` int(11) NOT NULL,
  `MandopID` int(5) NOT NULL DEFAULT 0,
  `MandopStatus` int(11) NOT NULL DEFAULT 0,
  `Approve` tinyint(4) NOT NULL DEFAULT 0,
  `Status` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`OrderID`, `ClientName`, `City`, `ClientAddress`, `ClientPhone`, `OrderPrice`, `OrderModel`, `OrderDate`, `UserID`, `MandopID`, `MandopStatus`, `Approve`, `Status`) VALUES
(34, 'محمد محمود الونش', 'الاسكندرية', 'العجمى', '01069167777', '300', 5, '2020-12-03', 1, 0, 0, 1, 4),
(35, 'مروان صلاح محسن', 'القاهره', 'المعادى', '01144171635', '250', 13, '2020-12-03', 30, 35, 1, 1, 5),
(36, 'محمود ياسين', 'القاهره', 'العاشر', '01516171819', '500', 9, '2020-12-03', 31, 36, 3, 1, 8);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `ProductID` int(11) NOT NULL,
  `ProductName` varchar(255) NOT NULL,
  `ProductColor` varchar(255) NOT NULL,
  `ProductSize` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`ProductID`, `ProductName`, `ProductColor`, `ProductSize`) VALUES
(4, 'شورت', 'ازرق', 4),
(5, 'بنطلون', 'اسود', 2),
(7, 'شورت', 'اسود', 2),
(8, 'قميص', 'اخضر', 1),
(9, 'جاكيت', 'ازرق', 3),
(10, 'جاكيت', 'كحلى', 2),
(12, 'بنطلون', 'رصاصى', 3),
(13, 'قميص', 'اسود', 5);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `ID` int(5) NOT NULL,
  `UserName` varchar(255) CHARACTER SET utf8 NOT NULL,
  `Email` varchar(255) CHARACTER SET utf8 NOT NULL,
  `Password` varchar(255) CHARACTER SET utf8 NOT NULL,
  `City` varchar(100) NOT NULL,
  `Address` varchar(255) CHARACTER SET utf8 NOT NULL,
  `Phone` varchar(100) CHARACTER SET utf8 NOT NULL,
  `FullName` varchar(255) CHARACTER SET utf8 NOT NULL,
  `RegDate` date NOT NULL,
  `Status` int(11) NOT NULL DEFAULT 0,
  `Approve` tinyint(4) NOT NULL DEFAULT 0,
  `ManagerID` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`ID`, `UserName`, `Email`, `Password`, `City`, `Address`, `Phone`, `FullName`, `RegDate`, `Status`, `Approve`, `ManagerID`) VALUES
(1, 'wiko', 'wiko@gmail.com', 'f7c3bc1d808e04732adf679965ccc34ca7ae3441', 'القاهره', 'المعادى', '01069167342', 'محمد حسام الوكيل', '2020-12-03', 1, 1, 1),
(29, 'samy', 'samy@gmail.com', 'f7c3bc1d808e04732adf679965ccc34ca7ae3441', 'الاسكندرية', 'العجمى', '01144171568', 'سامى سعيد العدلى', '2020-12-03', 2, 1, 1),
(30, 'ramy', 'ramy@yahoo.com', 'f7c3bc1d808e04732adf679965ccc34ca7ae3441', 'البحيرة', 'الرحمانية', '01112453697', 'رامى احمد بدر', '2020-12-03', 4, 1, 1),
(31, 'saied', 'sa34@gamil.com', 'f7c3bc1d808e04732adf679965ccc34ca7ae3441', 'القاهره', 'المعادى', '01144171685', 'سعيد عبد الغنى', '2020-12-03', 4, 1, 1),
(32, 'salah', 'salah@gamil.com', 'f7c3bc1d808e04732adf679965ccc34ca7ae3441', 'القاهره', 'المعادى', '01069167325', 'صلاح احمد عاشور', '2020-12-03', 2, 1, 1),
(35, 'alii', 'ali@gmail.com', 'f7c3bc1d808e04732adf679965ccc34ca7ae3441', 'القاهره', 'المعادى', '01069188342', 'على على معلول', '2020-12-03', 3, 1, 32),
(36, 'karem', 'kar@gamil.com', 'f7c3bc1d808e04732adf679965ccc34ca7ae3441', 'القاهره', 'العاشر', '01069137245', 'كريم عبدالفتاح', '2020-12-03', 3, 1, 32);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`OrderID`),
  ADD KEY `FK` (`UserID`),
  ADD KEY `OFK` (`OrderModel`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`ProductID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `MFK` (`ManagerID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `OrderID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `ProductID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `ID` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `FK` FOREIGN KEY (`UserID`) REFERENCES `users` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `OFK` FOREIGN KEY (`OrderModel`) REFERENCES `products` (`ProductID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `MFK` FOREIGN KEY (`ManagerID`) REFERENCES `users` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
