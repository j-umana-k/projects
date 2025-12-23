-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 18, 2025 at 05:58 AM
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
-- Database: `cinemabooking`
--

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `booking_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `showtime_id` int(11) DEFAULT NULL,
  `booking_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`booking_id`, `user_id`, `showtime_id`, `booking_date`) VALUES
(1, 6, 9, '2025-12-18 07:57:27');

-- --------------------------------------------------------

--
-- Table structure for table `booking_seats`
--

CREATE TABLE `booking_seats` (
  `id` int(11) NOT NULL,
  `booking_id` int(11) DEFAULT NULL,
  `seat_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `booking_seats`
--

INSERT INTO `booking_seats` (`id`, `booking_id`, `seat_id`) VALUES
(1, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `genres`
--

CREATE TABLE `genres` (
  `genre_id` int(11) NOT NULL,
  `genreName` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `genres`
--

INSERT INTO `genres` (`genre_id`, `genreName`) VALUES
(1, 'Science Fiction'),
(2, 'Drama'),
(3, 'Thriller'),
(4, 'Comedy'),
(5, 'Mystery'),
(6, 'War');

-- --------------------------------------------------------

--
-- Table structure for table `halls`
--

CREATE TABLE `halls` (
  `hall_id` int(11) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `total_seats` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `halls`
--

INSERT INTO `halls` (`hall_id`, `name`, `total_seats`) VALUES
(1, 'Hall 1', 10),
(2, 'Hall 2', 8);

-- --------------------------------------------------------

--
-- Table structure for table `movies`
--

CREATE TABLE `movies` (
  `movie_id` int(11) NOT NULL,
  `title` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `duration` int(11) DEFAULT NULL,
  `language` varchar(50) DEFAULT NULL,
  `genre_id` int(11) DEFAULT NULL,
  `poster` varchar(200) DEFAULT NULL,
  `trailer_id` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `movies`
--

INSERT INTO `movies` (`movie_id`, `title`, `description`, `duration`, `language`, `genre_id`, `poster`, `trailer_id`) VALUES
(1, 'Interstellar', 'Space sci-fi film.', 169, 'English', 1, 'interstellar.jpg', 'nyc6RJEEe0U'),
(2, 'The Holdovers', 'Holiday school drama.', 133, 'English', 2, 'the_holdovers.jpg', 'AhKLpJmHhIg'),
(3, 'Home Alone', 'Kid vs burglars comedy.', 103, 'English', 4, 'home_alone.jpg', 'dzdpqRGA1qc'),
(4, 'Donnie Darko', 'Time travel mystery.', 113, 'English', 5, 'donnie_darko.jpg', 'rPeGaos7DB4'),
(5, 'Hacksaw Ridge', 'True war story.', 139, 'English', 6, 'hacksaw_ridge.jpg', 's2-1hz1juBI'),
(6, 'Dune', 'Epic desert sci-fi.', 155, 'English', 1, 'dune.jpg', 'n9xhJrPXop4'),
(7, 'Forgotten', 'Korean memory thriller.', 109, 'Korean', 3, 'forgotten.jpg', 'b4p92xc9r6g'),
(8, 'Sherlock Holmes', 'Detective mystery.', 128, 'English', 5, 'sherlock_holmes.jpg', 'J7nJksXDBWc'),
(9, 'Black Hawk Down', 'Somalia war film.', 144, 'English', 6, 'black_hawk_down.jpg', '2GfBkC3qs78');

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `payment_id` int(11) NOT NULL,
  `booking_id` int(11) DEFAULT NULL,
  `method` varchar(50) DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`payment_id`, `booking_id`, `method`, `amount`, `status`) VALUES
(1, 1, 'Apple Pay', 50.00, 'Completed');

-- --------------------------------------------------------

--
-- Table structure for table `ratings`
--

CREATE TABLE `ratings` (
  `rating_id` int(11) NOT NULL,
  `movie_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `score` int(11) DEFAULT NULL CHECK (`score` between 1 and 5),
  `comment` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ratings`
--

INSERT INTO `ratings` (`rating_id`, `movie_id`, `user_id`, `score`, `comment`) VALUES
(1, 1, 1, 5, 'A timeless masterpiece in science fiction.'),
(2, 1, 3, 5, 'The best movie I have ever seen. The story and production are stunning.'),
(3, 2, 2, 4, 'A very warm and touching drama. Strong acting performances.'),
(4, 2, 5, 3, 'A decent film, but the pacing felt a bit too slow.'),
(5, 3, 3, 5, 'My favorite holiday classic! Never gets old.'),
(6, 4, 5, 4, 'A strange and complex film that provokes excellent thought.'),
(7, 4, 2, 5, 'A personal favorite, unique atmosphere and great mystery.'),
(8, 5, 1, 5, 'A powerful and moving war film about a truly inspiring true story.'),
(9, 5, 4, 4, 'The combat was intense, but the movie\'s message is valuable.'),
(10, 6, 3, 5, 'Visually stunning and the soundtrack is incredible! They captured the novel well.'),
(11, 6, 2, 4, 'Enjoyable film, but you need to see the second part for completion.'),
(12, 7, 3, 4, 'A good Korean thriller, full of unexpected twists.'),
(13, 8, 4, 3, 'A nice action movie, but it lacks the complexity of the original Sherlock stories.'),
(14, 8, 5, 4, 'A good mix of comedy and mystery.'),
(15, 9, 2, 3, 'A realistic and heartbreaking war film, but highly tense.'),
(16, 9, 1, 3, 'Long and action-focused, but it showed an important side of war.');

-- --------------------------------------------------------

--
-- Table structure for table `seats`
--

CREATE TABLE `seats` (
  `seat_id` int(11) NOT NULL,
  `seat_number` varchar(10) DEFAULT NULL,
  `hall_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `seats`
--

INSERT INTO `seats` (`seat_id`, `seat_number`, `hall_id`) VALUES
(1, 'A1', 1),
(2, 'A2', 1),
(3, 'A3', 1),
(4, 'A4', 1),
(5, 'A5', 1),
(6, 'B1', 1),
(7, 'B2', 1),
(8, 'B3', 1),
(9, 'B4', 1),
(10, 'B5', 1),
(11, 'A1', 2),
(12, 'A2', 2),
(13, 'A3', 2),
(14, 'A4', 2),
(15, 'B1', 2),
(16, 'B2', 2),
(17, 'B3', 2),
(18, 'B4', 2);

-- --------------------------------------------------------

--
-- Table structure for table `showtimes`
--

CREATE TABLE `showtimes` (
  `showtime_id` int(11) NOT NULL,
  `movie_id` int(11) DEFAULT NULL,
  `hall_id` int(11) DEFAULT NULL,
  `show_date` date DEFAULT NULL,
  `show_time` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `showtimes`
--

INSERT INTO `showtimes` (`showtime_id`, `movie_id`, `hall_id`, `show_date`, `show_time`) VALUES
(1, 1, 1, '2025-12-03', '16:00:00'),
(2, 1, 1, '2025-12-03', '19:00:00'),
(3, 1, 2, '2025-12-03', '17:00:00'),
(4, 1, 2, '2025-12-03', '20:00:00'),
(5, 2, 1, '2025-12-03', '16:30:00'),
(6, 2, 1, '2025-12-03', '19:30:00'),
(7, 2, 2, '2025-12-03', '17:30:00'),
(8, 2, 2, '2025-12-03', '20:30:00'),
(9, 3, 1, '2025-12-04', '16:00:00'),
(10, 3, 1, '2025-12-04', '19:00:00'),
(11, 3, 2, '2025-12-04', '17:00:00'),
(12, 3, 2, '2025-12-04', '20:00:00'),
(13, 4, 1, '2025-12-04', '16:30:00'),
(14, 4, 1, '2025-12-04', '19:30:00'),
(15, 4, 2, '2025-12-04', '17:30:00'),
(16, 4, 2, '2025-12-04', '20:30:00'),
(17, 5, 1, '2025-12-05', '16:00:00'),
(18, 5, 1, '2025-12-05', '19:00:00'),
(19, 5, 2, '2025-12-05', '17:00:00'),
(20, 5, 2, '2025-12-05', '20:00:00'),
(21, 6, 1, '2025-12-05', '16:30:00'),
(22, 6, 1, '2025-12-05', '19:30:00'),
(23, 6, 2, '2025-12-05', '17:30:00'),
(24, 6, 2, '2025-12-05', '20:30:00'),
(25, 7, 1, '2025-12-06', '16:00:00'),
(26, 7, 1, '2025-12-06', '19:00:00'),
(27, 7, 2, '2025-12-06', '17:00:00'),
(28, 7, 2, '2025-12-06', '20:00:00'),
(29, 8, 1, '2025-12-06', '16:30:00'),
(30, 8, 1, '2025-12-06', '19:30:00'),
(31, 8, 2, '2025-12-06', '17:30:00'),
(32, 8, 2, '2025-12-06', '20:30:00'),
(33, 9, 1, '2025-12-07', '16:00:00'),
(34, 9, 1, '2025-12-07', '19:00:00'),
(35, 9, 2, '2025-12-07', '17:00:00'),
(36, 9, 2, '2025-12-07', '20:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `name`, `email`, `password`, `created_at`) VALUES
(1, 'John Doe', 'johndoe@example.com', 'password123', '2025-12-18 07:56:01'),
(2, 'Jane Smith', 'janesmith@example.com', 'password456', '2025-12-18 07:56:01'),
(3, 'Alice Brown', 'alicebrown@example.com', 'password789', '2025-12-18 07:56:01'),
(4, 'Bob White', 'bobwhite@example.com', 'password321', '2025-12-18 07:56:01'),
(5, 'Charlie Black', 'charlieblack@example.com', 'password654', '2025-12-18 07:56:01'),
(6, 'jumana', 'jmana.alsaedi@gmail.com', '12345678', '2025-12-18 07:56:44');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`booking_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `showtime_id` (`showtime_id`);

--
-- Indexes for table `booking_seats`
--
ALTER TABLE `booking_seats`
  ADD PRIMARY KEY (`id`),
  ADD KEY `booking_id` (`booking_id`),
  ADD KEY `seat_id` (`seat_id`);

--
-- Indexes for table `genres`
--
ALTER TABLE `genres`
  ADD PRIMARY KEY (`genre_id`);

--
-- Indexes for table `halls`
--
ALTER TABLE `halls`
  ADD PRIMARY KEY (`hall_id`);

--
-- Indexes for table `movies`
--
ALTER TABLE `movies`
  ADD PRIMARY KEY (`movie_id`),
  ADD KEY `genre_id` (`genre_id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`payment_id`),
  ADD UNIQUE KEY `booking_id` (`booking_id`);

--
-- Indexes for table `ratings`
--
ALTER TABLE `ratings`
  ADD PRIMARY KEY (`rating_id`),
  ADD UNIQUE KEY `UQ_Movie_User` (`movie_id`,`user_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `seats`
--
ALTER TABLE `seats`
  ADD PRIMARY KEY (`seat_id`),
  ADD KEY `hall_id` (`hall_id`);

--
-- Indexes for table `showtimes`
--
ALTER TABLE `showtimes`
  ADD PRIMARY KEY (`showtime_id`),
  ADD UNIQUE KEY `UQ_Hall_DateTime` (`hall_id`,`show_date`,`show_time`),
  ADD KEY `movie_id` (`movie_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `booking_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `booking_seats`
--
ALTER TABLE `booking_seats`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `halls`
--
ALTER TABLE `halls`
  MODIFY `hall_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `movies`
--
ALTER TABLE `movies`
  MODIFY `movie_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `ratings`
--
ALTER TABLE `ratings`
  MODIFY `rating_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `seats`
--
ALTER TABLE `seats`
  MODIFY `seat_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `showtimes`
--
ALTER TABLE `showtimes`
  MODIFY `showtime_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `bookings_ibfk_2` FOREIGN KEY (`showtime_id`) REFERENCES `showtimes` (`showtime_id`);

--
-- Constraints for table `booking_seats`
--
ALTER TABLE `booking_seats`
  ADD CONSTRAINT `booking_seats_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`booking_id`),
  ADD CONSTRAINT `booking_seats_ibfk_2` FOREIGN KEY (`seat_id`) REFERENCES `seats` (`seat_id`);

--
-- Constraints for table `movies`
--
ALTER TABLE `movies`
  ADD CONSTRAINT `movies_ibfk_1` FOREIGN KEY (`genre_id`) REFERENCES `genres` (`genre_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`booking_id`);

--
-- Constraints for table `ratings`
--
ALTER TABLE `ratings`
  ADD CONSTRAINT `ratings_ibfk_1` FOREIGN KEY (`movie_id`) REFERENCES `movies` (`movie_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `ratings_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `seats`
--
ALTER TABLE `seats`
  ADD CONSTRAINT `seats_ibfk_1` FOREIGN KEY (`hall_id`) REFERENCES `halls` (`hall_id`);

--
-- Constraints for table `showtimes`
--
ALTER TABLE `showtimes`
  ADD CONSTRAINT `showtimes_ibfk_1` FOREIGN KEY (`movie_id`) REFERENCES `movies` (`movie_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `showtimes_ibfk_2` FOREIGN KEY (`hall_id`) REFERENCES `halls` (`hall_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
