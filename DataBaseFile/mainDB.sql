-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 01, 2021 at 09:56 PM
-- Server version: 10.4.17-MariaDB
-- PHP Version: 8.0.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
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
  `ClientPhone2` varchar(100) NOT NULL DEFAULT '0',
  `OrderDate` date NOT NULL,
  `NumOfPieces` int(11) NOT NULL DEFAULT 1,
  `UserID` int(11) NOT NULL,
  `MandopID` int(5) NOT NULL DEFAULT 0,
  `MandopStatus` int(11) NOT NULL DEFAULT 0,
  `Approve` tinyint(4) NOT NULL DEFAULT 0,
  `Status` int(11) NOT NULL DEFAULT 0,
  `ChargePrint` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`OrderID`, `ClientName`, `City`, `ClientAddress`, `ClientPhone`, `ClientPhone2`, `OrderDate`, `NumOfPieces`, `UserID`, `MandopID`, `MandopStatus`, `Approve`, `Status`, `ChargePrint`) VALUES
(47, 'بدون أسم', 'القاهره', 'مدينه نصر اول عباس شارع ابو العز السوري كافيه قهوه الكوخ', '01158060179', '', '2020-12-18', 1, 1, 39, 0, 1, 5, 1),
(48, 'بدون أسم', 'القاهره', 'محطة مترو حلوان', '01112063393', '01012294445', '2020-12-18', 1, 1, 0, 0, 1, 1, 1),
(49, 'بدون أسم', 'القاهره', 'محطة المرور امام سعيد علي شبرا الخيمه', '01154182949', '', '2020-12-18', 1, 1, 0, 0, 1, 1, 1),
(50, 'محمـد صـــلاح ذيدان', 'القاهره', 'مركز الصف عرب الحصار', '01154305060', '', '2020-12-18', 1, 1, 0, 0, 1, 1, 1),
(51, 'بدون أسم', 'القاهره', 'المطريه شارع الاربعين', '01150453591', '', '2020-12-18', 1, 1, 39, 0, 1, 5, 1),
(52, 'بدون أسم', 'القاهره', 'شبر الخيمة', '01157036995', '', '2020-12-18', 1, 1, 0, 0, 1, 1, 1),
(53, 'بدون أسم', 'القاهره', 'الشيخ زايد الحي ال ١٣', '98930784861', '', '2020-12-18', 1, 1, 0, 0, 1, 9, 0),
(54, 'بدون أسم', 'القاهره', 'البساتين امام قسم البساتين بجوار مصنع لبوار', '01226329857', '', '2020-12-18', 1, 1, 0, 0, 1, 1, 1),
(55, 'بدون أسم', 'القاهره', 'عين شمس الشرقية شارع منشية التحرير عند مدرسة التجارة', '01122860752', '', '2020-12-18', 1, 1, 0, 0, 1, 9, 0),
(56, 'محمد ابراهيم محمد حنفي', 'القاهره', 'المقطم مساكن الزلزال عند النادي الجديد', '98883438298', '', '2020-12-18', 1, 1, 0, 0, 1, 1, 1),
(57, 'محمد خالد عبدالرحيم', 'القاهره', '٥ ش سيد مرسي / الشرقاويه القديمه / شبرا الخيمه', '98973604260', '', '2020-12-18', 1, 1, 0, 0, 1, 1, 1),
(58, 'بدون أسم', 'القاهره', 'منشيه ناصر شارع بست عبد النور', '01221709533', '01224344274', '2020-12-18', 1, 1, 40, 0, 1, 5, 0),
(59, 'عبدالله محمد', 'القاهره', 'المقاولين العرب', '01115200217', '', '2020-12-18', 1, 1, 0, 0, 1, 1, 1),
(60, 'مصطفى محمد', 'القاهره', 'عادل ابو على متفرع من الشيخ منصور أما محطة مترو المرج القديمة', '01272009853', '', '2020-12-18', 1, 1, 0, 0, 1, 1, 1),
(61, 'شريف احمد سيد', 'القاهره', 'برج الجوهرة الدور الخامس شقه ١٧ شارع دكتور لاشين فيصل مريوطيه امام اسواق كارمن', '98934118270', '0237534457', '2020-12-18', 1, 1, 41, 0, 1, 8, 0),
(62, 'محمد ثروت', 'القاهره', 'العباسيه', '0110041839', '', '2020-12-18', 1, 1, 0, 0, 1, 1, 1),
(63, 'بدون أسم', 'القاهره', 'شبرا الخيمه', '01226070116', '', '2020-12-18', 1, 1, 0, 0, 1, 1, 1),
(64, 'بدون أسم', 'القاهره', 'الشيخ زايد الحي ال ١٣ امام محل العيسوي', '01069215138', '', '2020-12-18', 1, 1, 0, 0, 1, 1, 1),
(65, 'وليد محمد ', 'القاهره', 'مدينة النهضة امام استاد الانتاج الحربي ', '01123984209', '', '2020-12-19', 1, 1, 0, 0, 1, 1, 1),
(66, 'محمد ', 'القاهره', 'الزاوية الحمراء', '01111409732', '', '2020-12-19', 1, 1, 41, 0, 1, 5, 0),
(67, 'بدون اسم ', 'القاهره', 'المقطم ش المدينة المنورة خلف بنزينة توتال عمارة 7737', '01142713867', '', '2020-12-19', 1, 1, 0, 0, 1, 1, 1),
(68, 'احمد ابراهيم ', 'القاهره', 'البردعة مركز القناطر الخيرية القليوبية ', '01146288651', '', '2020-12-19', 1, 1, 0, 0, 1, 1, 1),
(69, 'يوسف ابراهيم ', 'القاهره', 'المطرية عرب الحصن بجوار كوبري عرب الحصن', '01156646612', '', '2020-12-19', 1, 1, 0, 0, 1, 1, 1),
(70, 'يحيي سليمان ', 'القاهره', 'رمسيس باب البحر', '01019094667', '', '2020-12-19', 1, 1, 41, 0, 1, 5, 0),
(71, 'حسام صلاح ', 'القاهره', 'ش الورشة بجوار الثلاجة خلف اولاد رجب  الحي العاشر مدينة نصر', '01128941284', '', '2020-12-19', 1, 1, 39, 0, 1, 5, 0),
(72, 'بدون اسم ', 'القاهره', '21 بيومي حجازي متفرع من ش الشركات امام مخزن الانابيب الزاوية الحمراء', '01271477227', '', '2020-12-19', 1, 1, 41, 0, 1, 5, 0),
(73, 'عبدالرحمن ', 'القاهره', 'غمرة سوق العبايات', '01147337069', '', '2020-12-19', 1, 1, 41, 0, 1, 5, 0),
(74, 'احمد حسام ', 'القاهره', 'ش مستشفي ناصر العام بجوار معرض كريستال عصفور ', '01129305192', '', '2020-12-19', 1, 1, 0, 0, 1, 1, 1),
(75, 'حسام حسن ', 'القاهره', 'ش الجسر البراني امام اكادمية طيبة مكتبة الفرسان دار السلام ', '01121477888', '', '2020-12-19', 1, 1, 0, 0, 1, 5, 0),
(76, 'ابانوب ممدوح ', 'القاهره', 'محطة مترو عزبة النخل ', '01140656845', '', '2020-12-19', 1, 1, 0, 0, 1, 1, 1),
(77, 'بدون اسم ', 'القاهره', 'المعادي', '01143355363', '', '2020-12-19', 1, 1, 0, 0, 1, 8, 0),
(78, 'بدون اسم ', 'القاهره', '63ش الحايس من جسر البحر الساحل بجانب مترو الخلفاوي', '01007157744', '01091899928', '2020-12-19', 1, 1, 0, 0, 1, 1, 1),
(79, 'محمد سمير عبد الرحيم', 'القاهره', 'السلام اسبيكو العبد ٧٠٠  خلف المحلات بلوك ٢٢١', '98976180173', '', '2020-12-19', 1, 1, 0, 0, 1, 1, 1),
(80, 'حسام عماد محمد', 'القاهره', '83 ش خليل إبراهيم متفرع من شارع جسر السويس', '01115386537', '', '2020-12-19', 1, 1, 39, 0, 1, 5, 0),
(81, 'بدون اسم ', 'القاهره', 'مدينه نصر أول عباس العقاد', '01025468901', '', '2020-12-19', 1, 1, 39, 0, 1, 5, 0);

-- --------------------------------------------------------

--
-- Table structure for table `pieces`
--

CREATE TABLE `pieces` (
  `PieceID` int(11) NOT NULL,
  `OrderNumber` int(11) NOT NULL,
  `PieceModel` int(11) NOT NULL,
  `NumberOfPieces` int(100) NOT NULL DEFAULT 1,
  `PiecePrice` varchar(100) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `pieces`
--

INSERT INTO `pieces` (`PieceID`, `OrderNumber`, `PieceModel`, `NumberOfPieces`, `PiecePrice`) VALUES
(1, 47, 19, 1, '220'),
(2, 48, 23, 1, '220'),
(3, 49, 25, 1, '220'),
(4, 50, 24, 1, '220'),
(5, 51, 24, 1, '220'),
(6, 52, 23, 1, '220'),
(7, 53, 18, 1, '220'),
(8, 54, 19, 1, '220'),
(9, 55, 18, 1, '220'),
(10, 56, 23, 1, '220'),
(11, 57, 17, 1, '220'),
(12, 58, 17, 1, '220'),
(13, 59, 23, 1, '220'),
(14, 60, 15, 1, '250'),
(15, 61, 18, 1, '250'),
(16, 62, 23, 1, '250'),
(17, 63, 23, 1, '250'),
(18, 64, 18, 1, '250'),
(19, 65, 20, 1, '220'),
(20, 66, 23, 1, '220'),
(21, 67, 24, 1, '220'),
(22, 68, 23, 1, '220'),
(23, 69, 15, 1, '220'),
(24, 70, 18, 1, '220'),
(25, 71, 24, 1, '220'),
(26, 72, 17, 1, '220'),
(27, 73, 20, 1, '220'),
(28, 74, 16, 1, '220'),
(29, 75, 14, 1, '220'),
(30, 76, 20, 1, '220'),
(31, 77, 21, 1, '220'),
(32, 78, 25, 1, '220'),
(33, 79, 25, 1, '220'),
(34, 80, 24, 1, '220'),
(35, 81, 25, 1, '220');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `ProductID` int(11) NOT NULL,
  `ProductName` varchar(255) NOT NULL,
  `ProductColor` varchar(255) NOT NULL,
  `ProductSize` int(11) NOT NULL DEFAULT 0,
  `NumOfPieces` int(100) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`ProductID`, `ProductName`, `ProductColor`, `ProductSize`, `NumOfPieces`) VALUES
(14, 'ترنج سوسته', 'كحلي', 3, 0),
(15, 'ترنج سوسته', 'كحلي', 4, 0),
(16, 'ترنج سوسته', 'كحلي', 5, 0),
(17, 'ترنج سوسته', 'نبيتي', 3, 0),
(18, 'ترنج سوسته', 'نبيتي', 4, 0),
(19, 'ترنج سوسته', 'نبيتي', 5, 0),
(20, 'ترنج سوسته', 'رصاصي', 3, 0),
(21, 'ترنج سوسته', 'رصاصي', 4, 0),
(22, 'ترنج سوسته', 'رصاصي', 5, 0),
(23, 'ترنج سوسته', 'أسود', 3, 0),
(24, 'ترنج سوسته', 'أسود', 4, 0),
(25, 'ترنج سوسته', 'أسود', 5, 0),
(26, 'ترنج سوسته', 'زيتي', 3, 0),
(27, 'ترنج سوسته', 'زيتي', 4, 0),
(28, 'ترنج سوسته', 'زيتي', 5, 0);

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
  `Phone2` varchar(100) NOT NULL DEFAULT '0',
  `FullName` varchar(255) CHARACTER SET utf8 NOT NULL,
  `RegDate` date NOT NULL,
  `Status` int(11) NOT NULL DEFAULT 0,
  `Approve` tinyint(4) NOT NULL DEFAULT 0,
  `ManagerID` int(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`ID`, `UserName`, `Email`, `Password`, `City`, `Address`, `Phone`, `Phone2`, `FullName`, `RegDate`, `Status`, `Approve`, `ManagerID`) VALUES
(1, 'wiko', 'wiko@gmail.com', 'f7c3bc1d808e04732adf679965ccc34ca7ae3441', 'القاهره', 'المعادى', '01069167342', '0', 'محمد حسام الوكيل', '2020-12-03', 1, 1, 1),
(29, 'samy', 'samy@gmail.com', 'f7c3bc1d808e04732adf679965ccc34ca7ae3441', 'الاسكندرية', 'العجمى', '01144171568', '0', 'سامى سعيد العدلى', '2020-12-03', 2, 1, 1),
(30, 'ramy', 'ramy@yahoo.com', 'f7c3bc1d808e04732adf679965ccc34ca7ae3441', 'البحيرة', 'الرحمانية', '01112453697', '0', 'رامى احمد بدر', '2020-12-03', 4, 1, 1),
(31, 'saied', 'sa34@gamil.com', 'f7c3bc1d808e04732adf679965ccc34ca7ae3441', 'القاهره', 'المعادى', '01144171685', '0', 'سعيد عبد الغنى', '2020-12-03', 4, 1, 1),
(32, 'salah', 'salah@gamil.com', 'f7c3bc1d808e04732adf679965ccc34ca7ae3441', 'القاهره', 'المعادى', '01069167325', '01569167342', 'صلاح احمد عاشور', '2020-12-03', 2, 1, 1),
(35, 'alii', 'ali@gmail.com', 'f7c3bc1d808e04732adf679965ccc34ca7ae3441', 'الاسكندرية', 'البطاش', '01069188342', '0', 'على على معلول', '2020-12-03', 3, 1, 29),
(36, 'karem', 'kar@gamil.com', 'f7c3bc1d808e04732adf679965ccc34ca7ae3441', 'الاسكندرية', 'الهانوفيل', '01069137245', '0', 'كريم عبدالفتاح', '2020-12-03', 3, 1, 29),
(38, 'osama', 'sfsfs@gmail.com', '0438287abdc7edc5b9f31a5e10a637b044f39d56', 'القاهره', 'sfsafs sf asfasf', '01001455454', '', 'اسامه جابر', '2020-12-18', 2, 1, 1),
(39, 'محمد سليمان', 'shlkjlkj@gmail.com', '601f1889667efaebb33b8c12572835da3f027f78', 'القاهره', 'محمد سليمان ', '205450050', '', 'محمد سليمان', '2020-12-19', 3, 1, 38),
(40, 'محمد سيد', 'mthe622@gmail.com', '601f1889667efaebb33b8c12572835da3f027f78', 'القاهره', 'محمد سيد', '464496616416', '', 'محمد سيد ', '2020-12-20', 3, 1, 38),
(41, 'محمد اسماعيل ', 'mthe622@gmail.com', '601f1889667efaebb33b8c12572835da3f027f78', 'القاهره', 'محمد اسماعيل ', '46465456536', '', 'محمد اسماعيل ', '2020-12-20', 3, 1, 38);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`OrderID`),
  ADD KEY `FK` (`UserID`);

--
-- Indexes for table `pieces`
--
ALTER TABLE `pieces`
  ADD PRIMARY KEY (`PieceID`),
  ADD KEY `productnumberFK` (`PieceModel`),
  ADD KEY `OrderNumberFK` (`OrderNumber`);

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
  MODIFY `OrderID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=82;

--
-- AUTO_INCREMENT for table `pieces`
--
ALTER TABLE `pieces`
  MODIFY `PieceID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `ProductID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `ID` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `FK` FOREIGN KEY (`UserID`) REFERENCES `users` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `pieces`
--
ALTER TABLE `pieces`
  ADD CONSTRAINT `OrderNumberFK` FOREIGN KEY (`OrderNumber`) REFERENCES `orders` (`OrderID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `productnumberFK` FOREIGN KEY (`PieceModel`) REFERENCES `products` (`ProductID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `MFK` FOREIGN KEY (`ManagerID`) REFERENCES `users` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
