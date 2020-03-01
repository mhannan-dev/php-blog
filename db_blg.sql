-- phpMyAdmin SQL Dump
-- version 4.9.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 01, 2020 at 04:33 AM
-- Server version: 10.4.8-MariaDB
-- PHP Version: 7.2.23

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `blg`
--

-- --------------------------------------------------------

--
-- Table structure for table `contact`
--

CREATE TABLE `contact` (
  `id` int(11) NOT NULL,
  `fname` varchar(100) NOT NULL,
  `lname` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `msg` text NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0,
  `created` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `contact`
--

INSERT INTO `contact` (`id`, `fname`, `lname`, `email`, `msg`, `status`, `created`) VALUES
(3, 'sdfdsf', 'asdfdf', 'author@gmail.com', 'adfdfd', 0, '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `footer`
--

CREATE TABLE `footer` (
  `id` int(11) NOT NULL,
  `note` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `footer`
--

INSERT INTO `footer` (`id`, `note`) VALUES
(1, 'Muhammad Hannan Ali');

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

CREATE TABLE `pages` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `body` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `pages`
--

INSERT INTO `pages` (`id`, `name`, `body`) VALUES
(6, 'Blog', '<p>About us..Some text will be go here. Some text will be go here.Some text will be go here.Some text will be go here.Some text will be go here.Some text will be go here.Some text will be go here.Some text will be go here.Some text will be go here.Some text will be go here. Some text will be go here.Some text will be go here.Some text will be go here.Some text will be go here.Some text will be go here.Some text will be go here.Some text will be go here.Some text will be go here.</p>'),
(9, 'DMCA', '<p>About us..Some text will be go here. Some text will be go here.Some text will be go here.Some text will be go here.Some text will be go here.Some text will be go here.Some text will be go here.Some text will be go here.Some text will be go here.Some text will be go here. Some text will be go here.Some text will be go here.Some text will be go here.Some text will be go here.Some text will be go here.Some text will be go here.Some text will be go here.Some text will be go here.</p>'),
(11, 'Tutorial', '<p>tutorial</p>'),
(12, 'Sports', '<p>Sports</p>');

-- --------------------------------------------------------

--
-- Table structure for table `site_info`
--

CREATE TABLE `site_info` (
  `logo` varchar(255) NOT NULL,
  `title` varchar(100) NOT NULL,
  `slogan` varchar(100) NOT NULL,
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `site_info`
--

INSERT INTO `site_info` (`logo`, `title`, `slogan`, `id`) VALUES
('upload/02a874388f.png', 'MH Blog', 'Write for knowledge', 3);

-- --------------------------------------------------------

--
-- Table structure for table `sliders`
--

CREATE TABLE `sliders` (
  `id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `image` varchar(100) NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `sliders`
--

INSERT INTO `sliders` (`id`, `title`, `image`, `timestamp`) VALUES
(34, 'One', 'upload/3642b7239f.jpg', '2019-09-05 09:35:31'),
(35, 'Two', 'upload/e819a86130.jpg', '2019-09-05 09:35:50'),
(36, 'Three', 'upload/a49c387bd8.jpg', '2019-09-05 09:36:05'),
(37, 'Four', 'upload/7891bbc826.jpg', '2019-09-05 09:36:19');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_category`
--

CREATE TABLE `tbl_category` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_category`
--

INSERT INTO `tbl_category` (`id`, `name`) VALUES
(1, 'Java'),
(5, 'SQL'),
(6, 'Javascript'),
(7, 'Oracle'),
(8, 'Mongo DB'),
(10, 'Django');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_post`
--

CREATE TABLE `tbl_post` (
  `id` int(11) NOT NULL,
  `cat` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `body` text NOT NULL,
  `image` varchar(255) NOT NULL,
  `author` varchar(50) NOT NULL,
  `tags` varchar(255) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `userid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_post`
--

INSERT INTO `tbl_post` (`id`, `cat`, `title`, `body`, `image`, `author`, `tags`, `date`, `userid`) VALUES
(10, 7, 'Lorem Ippsumma', '<p>One of the most common causes of this problem is adding an extra line after the closing PHP tag in an include file, such as the one Dreamweaver creates in the <kbd class=\"userinput\">Connections</kbd> folder with details of your MySQL ...</p>', 'upload/5d44b0c2ed.jpg', 'admin', 'common ', '2019-09-05 09:50:41', 1),
(14, 5, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.', '<p>\"Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.\"</p>', 'upload/8dd63fbc58.png', 'admin', 'Lorem ', '2019-09-05 09:51:16', 1),
(22, 8, 'Female job seeker gang-raped in Shyamoli', '<p>During the interview, two staff of the office feed the girl soft drinks mixed with sedative pills and raped her, the OC told The Daily Star quoting the victim.</p>\r\n<p>The law enforcers arrested Fahim Ahmed Foyez, 31, one of the accused from the office last night after the victim filed a case against the two with the police station yesterday, he said.</p>\r\n<p>The police official said they were trying to arrest another accused.</p>', 'upload/064e4cfa5c.png', 'admin', 'gang-raped ', '2019-09-05 09:51:34', 1),
(23, 6, 'Cop among 3 killed in Cumilla road crash ', '<p>The deceased were identified as Assistant Sub-inspector of Miabazar highway police outpost Akter Hossain, 33, covered van driver Fahad, 25, and its helper Sumon, 24.</p>\r\n<p>Abul Kalam Azad, in-charge of the police outpost, said a team of highway police along with a wrecker reached Sayedpur area in Chauddagram upazila to rescue a covered van which was hit by another vehicle.</p>\r\n<p>But all of a sudden, another covered van appeared the scene and hit the stationed covered van, which broke down after the accident, when the police team with the help of its driver, helper was conducting the rescue operation, the police official said.</p>', 'upload/f3bc0cf633.jpg', 'admin', 'road crash', '2019-09-05 09:50:30', 1),
(25, 6, 'The school headmaster, Abdul Karim', '<p>The school headmaster, Abdul Karim, said, &ldquo;The river was about 300 feet away from the school but the intensity of erosion increased suddenly, devouring the entire school.</p>\r\n<p>&ldquo;We could save some important official papers and a few chairs, tables, and benches with the help of locals and the students when the school was slowly disappearing into the river. We are worried about the children&rsquo;s education.&rdquo;</p>\r\n<p>As the erosion continued to claim more land, more than a hundred villagers migrated from the river side and took shelter in open spaces around the char.</p>', 'upload/69d6e1cf22.jpg', 'admin', 'headmaster', '2019-09-05 09:49:50', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_social`
--

CREATE TABLE `tbl_social` (
  `id` int(11) NOT NULL,
  `fb` varchar(100) NOT NULL,
  `tw` varchar(100) NOT NULL,
  `ln` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_social`
--

INSERT INTO `tbl_social` (`id`, `fb`, `tw`, `ln`) VALUES
(3, 'https://www.facebook.com/muhammadhannanali', 'https://github.com/MyCodeBin', 'https://www.linkedin.com/in/muhammad-hannan-87abb948/');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_user`
--

CREATE TABLE `tbl_user` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(32) NOT NULL,
  `details` text NOT NULL,
  `role` int(11) NOT NULL,
  `userid` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tbl_user`
--

INSERT INTO `tbl_user` (`id`, `name`, `username`, `email`, `password`, `details`, `role`, `userid`) VALUES
(1, 'Muhammad Hannan Ali', 'admin', 'mdhannan.info@gmail.com', '81dc9bdb52d04dc20036dbd8313ed055', '<p>MD. HANNAN ALI</p>', 0, 0),
(8, 'Mr. Author', 'author', 'author@gmail.com', 'd5b7fb5a8edfeadce678a98282fbca57', '<p>Author bio</p>', 1, 0),
(15, '', 'tuhin', 'tuhin@yahoo.com', '81dc9bdb52d04dc20036dbd8313ed055', '', 2, 0);

-- --------------------------------------------------------

--
-- Table structure for table `themes`
--

CREATE TABLE `themes` (
  `id` int(11) NOT NULL,
  `theme` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `themes`
--

INSERT INTO `themes` (`id`, `theme`) VALUES
(1, 'green');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `username`, `password`) VALUES
(1, 'MD. HANNAN ALI', 'hannan@arobil.com', 'admin', 'e10adc3949ba59abbe56e057f20f883e'),
(2, 'Mahmudul Hasan', 'hasan@gmail.com', 'hasan', 'e10adc3949ba59abbe56e057f20f883e'),
(3, 'Shahjalal', 'jalal@gmail.com', 'jalal', 'e10adc3949ba59abbe56e057f20f883e'),
(4, 'Abdul Manna', 'amannan@gmail.com', 'amannan', 'e10adc3949ba59abbe56e057f20f883e'),
(5, 'Abdus Salam', 'asalam@gmail.com', 'asalam', 'e10adc3949ba59abbe56e057f20f883e'),
(6, 'Saddam Hossain Arif', 'saddam@gmail.com', 'saddam', 'e10adc3949ba59abbe56e057f20f883e'),
(7, 'Abdul Bari', 'bari@gmail.com', 'abari', 'e10adc3949ba59abbe56e057f20f883e');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `contact`
--
ALTER TABLE `contact`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `footer`
--
ALTER TABLE `footer`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pages`
--
ALTER TABLE `pages`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `site_info`
--
ALTER TABLE `site_info`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sliders`
--
ALTER TABLE `sliders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_category`
--
ALTER TABLE `tbl_category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_post`
--
ALTER TABLE `tbl_post`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `title` (`title`);

--
-- Indexes for table `tbl_social`
--
ALTER TABLE `tbl_social`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_user`
--
ALTER TABLE `tbl_user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `themes`
--
ALTER TABLE `themes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `contact`
--
ALTER TABLE `contact`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `footer`
--
ALTER TABLE `footer`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `pages`
--
ALTER TABLE `pages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `site_info`
--
ALTER TABLE `site_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `sliders`
--
ALTER TABLE `sliders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `tbl_category`
--
ALTER TABLE `tbl_category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `tbl_post`
--
ALTER TABLE `tbl_post`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `tbl_social`
--
ALTER TABLE `tbl_social`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tbl_user`
--
ALTER TABLE `tbl_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `themes`
--
ALTER TABLE `themes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
