-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 16, 2025 at 06:22 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `code_crumble`
--

-- --------------------------------------------------------

--
-- Table structure for table `contact_messages`
--

CREATE TABLE `contact_messages` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `subject` varchar(150) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contact_messages`
--

INSERT INTO `contact_messages` (`id`, `user_id`, `name`, `email`, `subject`, `message`, `created_at`) VALUES
(1, NULL, 'layan', 'layaniop4@gmail.com', 'course ', 'hello', '2025-12-16 11:50:24'),
(2, NULL, 'han', 'layaniop8@gmail.com', 'sweet', 'i love sweets ', '2025-12-16 15:52:22');

-- --------------------------------------------------------

--
-- Table structure for table `recipes`
--

CREATE TABLE `recipes` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `dessert_name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `ingredients` text NOT NULL,
  `instructions` text NOT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `type` enum('cake','cookie','pie','other') NOT NULL,
  `featured_status` enum('Top Pick','Honorable Mention','None') DEFAULT 'None',
  `tags` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `recipes`
--

INSERT INTO `recipes` (`id`, `user_id`, `dessert_name`, `description`, `ingredients`, `instructions`, `image_url`, `type`, `featured_status`, `tags`, `created_at`, `updated_at`) VALUES
(8, 1, 'Blueberry Cheesecake', 'classic Blueberry Cheesecake', '200g digestive biscuits (crushed)\r\n\r\n100g melted butter and 1 cup blueberries (fresh or frozen), ¼ cup sugar\r\n', 'Preheat oven to 170°C ,Mix biscuits and butter, press into pan.Beat cream cheese and sugar until smooth.\r\n\r\nAdd eggs one at a time.\r\nMix in vanilla, cream, and flour.\r\nPour over crust and bake 50–55 min.\r\nCool completely.', 'uploads/1765902319_cheesecake.jpg', 'cake', 'Honorable Mention', 'Quick', '2025-12-16 16:25:19', '2025-12-16 16:47:55'),
(9, 1, 'Gluten-Free Apple Pie', 'heartwarming apple crumble pie ', '2 cups gluten-free flour blend\r\n½ cup cold butter (cubed)\r\n2 tbsp sugar\r\n¼ cup cold water', 'Preheat oven to 180°C.\r\nMix flour, sugar, and butter until crumbly.\r\nAdd cold water and form dough.\r\nPress dough into pie pan.\r\n\r\nMix apples, sugar, cinnamon, cornstarch, and lemon.\r\nFill crust with apple mixture.\r\nBake 40–45 min until golden.\r\nCool before serving.', 'uploads/1765902729_pie.jpg', 'pie', 'Honorable Mention', 'gluten-free', '2025-12-16 16:32:09', '2025-12-16 16:48:59'),
(10, 1, 'Healthy Brownies', 'simple Healthy Brownies (No refined sugar.)', '2 ripe bananas (mashed) , 2 eggs, ¼ cup cocoa powder\r\n¼ cup oat flour, 2 tbsp honey or maple syrup\r\n2 tbsp coconut oil (melted)\r\n1 tsp vanilla extract, ½ tsp baking powder', 'Preheat oven to 170°C.\r\nMix mashed bananas and eggs.\r\nAdd cocoa, oat flour, sweetener, oil, vanilla, and baking powder.\r\nPour into lined pan.\r\nBake 18–20 min.\r\nLet cool before cutting.', 'uploads/1765902956_brownie.jpg', 'cake', 'Honorable Mention', 'Healthy', '2025-12-16 16:35:56', '2025-12-16 17:17:02'),
(11, 1, 'Vanilla Cupcakes', 'simple Vanilla Cupcakes', '1½ cups flour\r\n1 cup sugar\r\n½ cup butter (soft)\r\n2 eggs\r\n½ cup milk\r\n1½ tsp baking powder\r\n1 tsp vanilla', 'Preheat oven to 180°C.\r\nMix butter and sugar until creamy.\r\nAdd eggs and vanilla.\r\nMix in flour, baking powder, and milk.\r\nPour into cupcake liners.\r\nBake 15–18 min.', 'uploads/1765903203_cupcake.jpg', 'cake', 'None', 'Quick', '2025-12-16 16:40:03', '2025-12-16 16:40:03'),
(12, 1, 'Chocolate Chip Cookies', 'classic Chocolate Chip Cookies', '1 cup flour\r\n½ cup butter\r\n½ cup brown sugar\r\n1 egg\r\n½ tsp baking soda\r\n½ cup chocolate chips', 'Preheat oven to 180°C.\r\nCream butter and sugar. \r\nAdd egg.\r\nMix flour and baking soda.\r\nFold in chocolate chips.\r\nBake 10–12 min.', 'uploads/1765903385_cookie.jpg', 'cookie', 'Top Pick', 'Quick', '2025-12-16 16:43:05', '2025-12-16 16:49:18');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password_hash`) VALUES
(1, 'admin_baker', 'admin@codecrumble.com', '179ad45c6ce2cb97cf1029e212046e81'),
(2, 'lay', 'lay@gmail.com', '$2y$10$M3Rn.q4dzmhZj5ZeN5Oe9eQfokXzBJSAV7W2RDTljY/cMS6f0/fdC'),
(3, 'han', 'han@gmail.com', '$2y$10$F/8jfn8loZISE8D6uCYG7.A3gFg4KU5QKWjIrDwIJb.ou5qYbVw46');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `recipes`
--
ALTER TABLE `recipes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `contact_messages`
--
ALTER TABLE `contact_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `recipes`
--
ALTER TABLE `recipes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD CONSTRAINT `contact_messages_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `recipes`
--
ALTER TABLE `recipes`
  ADD CONSTRAINT `recipes_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
