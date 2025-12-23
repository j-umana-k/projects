<?php
// Database connection
require_once "Database_connect.php";

if (!isset($_GET['booking_id'])) {
    die("Booking ID missing");
}

$booking_id = intval($_GET['booking_id']);

// Fetch booking info
$booking = $conn->query("
    SELECT b.booking_id, b.booking_date, u.name AS user_name, 
           m.title AS movie_title, m.poster AS movie_poster,
           s.show_date, s.show_time
    FROM bookings b
    JOIN users u ON b.user_id = u.user_id
    JOIN showtimes s ON b.showtime_id = s.showtime_id
    JOIN movies m ON s.movie_id = m.movie_id
    WHERE b.booking_id = $booking_id
")->fetch_assoc();

// Fetch seats
$seats_result = $conn->query("
    SELECT se.seat_number, h.name AS hall_name
    FROM booking_seats bs
    JOIN seats se ON bs.seat_id = se.seat_id
    JOIN halls h ON se.hall_id = h.hall_id
    WHERE bs.booking_id = $booking_id
");

$seats = [];
while ($row = $seats_result->fetch_assoc()) {
    $seats[] = $row;
}
?>

<!DOCTYPE html>
<html class="confirmation-page">
<head>
    <meta charset="UTF-8">
    <title>Booking Confirmation</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<main class="box">

    <h1>Booking Confirmed!</h1>

    <!-- Movie poster -->
    <div class="poster-container">
        <img src="images/<?php echo htmlspecialchars($booking['movie_poster']); ?>" 
             alt="<?php echo htmlspecialchars($booking['movie_title']); ?>" 
             style="width:250px; border-radius:10px;">
    </div>

    <p><strong>Movie:</strong> <?php echo htmlspecialchars($booking['movie_title']); ?></p>
    <p>
        <strong>Showtime:</strong> 
        <?php echo date("d M Y", strtotime($booking['show_date'])) . " " . date("g:i A", strtotime($booking['show_time'])); ?>
    </p>
    <p><strong>Hall:</strong> <?php echo isset($seats[0]['hall_name']) ? htmlspecialchars($seats[0]['hall_name']) : ''; ?></p>
    <p><strong>Seats:</strong> <?php echo implode(", ", array_column($seats, 'seat_number')); ?></p>
    <p><strong>Booking Date:</strong> <?php echo date("d M Y, g:i A", strtotime($booking['booking_date'])); ?></p>
    <button type="button" class="back-home-btn"
            onclick="window.location.href='profile_page.php'">
         My Profile
    </button>
</main>

<footer>
    &copy; 2025 Cinema Booking System
</footer>
</body>
</html>
