<?php
session_start();

// Database connection using new file
require_once "Database_connect.php";

// Validate movie ID
if (!isset($_GET['movie_id'])) die("Movie ID missing.");

$movie_id = intval($_GET['movie_id']);

// Get movie info
$movie = $conn->query("SELECT * FROM movies WHERE movie_id = $movie_id")->fetch_assoc();
if (!$movie) die("Movie not found");

// Get halls for the movie
$halls = $conn->query("
    SELECT DISTINCT h.hall_id, h.name AS hall_name
    FROM halls h
    JOIN showtimes st ON st.hall_id = h.hall_id
    WHERE st.movie_id = $movie_id
    ORDER BY h.hall_id
");

// Get showtimes for the movie
$showtimes = $conn->query("
    SELECT st.showtime_id, st.show_time, st.hall_id
    FROM showtimes st
    WHERE st.movie_id = $movie_id
    ORDER BY st.hall_id, st.show_time
");

// Get reserved seats
$reservedSeats = [];
if (isset($_GET['showtime_id'])) {
    $showtime_id = intval($_GET['showtime_id']);
    $reservedQuery = $conn->query("
        SELECT seat_id
        FROM booking_seats bs
        JOIN bookings b ON bs.booking_id = b.booking_id
        WHERE b.showtime_id = $showtime_id
    ");
    while ($row = $reservedQuery->fetch_assoc()) {
        $reservedSeats[] = $row['seat_id'];
    }
}

// Get all seats
$seats = $conn->query("
    SELECT *, LEFT(seat_number,1) AS row_label
    FROM seats
    ORDER BY hall_id, row_label, seat_number
");

// Preset showtime id (optional)
$presetShowtimeId = $_GET['showtime_id'] ?? '';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Booking - <?= htmlspecialchars($movie['title']) ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="booking_page">

<header class="top-header">
    <h1 class="page-title"><?= htmlspecialchars($movie['title']) ?></h1>
</header>

<div class="main-container">
    <div class="sidebar">
        <a href="home_page.php" class="back-home-btn"> Back To Home</a>

        <h4>Select Hall</h4>
        <div class="hall-container">
            <?php while($h = $halls->fetch_assoc()): ?>
                <div class="hall-option" data-hall-id="<?= $h['hall_id'] ?>">
                    <?= htmlspecialchars($h['hall_name']) ?>
                </div>
            <?php endwhile; ?>
        </div>

        <h4>Select Showtime</h4>
        <div class="showtime-container">
            <?php while($s = $showtimes->fetch_assoc()): ?>
                <?php
                $isSelected = (isset($_GET['showtime_id']) && $_GET['showtime_id'] == $s['showtime_id']) ? 'selected' : '';
                ?>
                <div class="showtime-option <?= $isSelected ?>"
                     data-id="<?= $s['showtime_id'] ?>"
                     data-hall-id="<?= $s['hall_id'] ?>">
                    <?= date("g:i A", strtotime($s['show_time'])) ?>
                </div>
            <?php endwhile; ?>
        </div>
    </div>

    <div class="seats-column">
        <div class="screen">SCREEN</div>
        <div class="seating-area">
            <?php
            $rows = [];
            while($seat = $seats->fetch_assoc()) {
                $rows[$seat['row_label']][] = $seat;
            }

            foreach ($rows as $row_label => $seats_in_row) {
                echo "<div class='row-wrapper'>";
                echo "<div class='row-label'>$row_label</div>";
                echo "<div class='row'>";
                foreach ($seats_in_row as $seat) {
                    $reservedClass = in_array($seat['seat_id'], $reservedSeats) ? 'reserved' : 'available';
                    echo "<div class='seat $reservedClass'
                         data-seat-id='{$seat['seat_id']}'
                         data-price='50'
                         data-hall-id='{$seat['hall_id']}'>
                         </div>";
                }
                echo "</div></div>";
            }
            ?>
        </div>

        <!-- LEGEND -->
        <div class="legend">
            <div class="legend-item"><div class="legend-seat available"></div> Available</div>
            <div class="legend-item"><div class="legend-seat selected"></div> Selected</div>
<div class="legend-item"><div class="legend-seat reserved"></div> Reserved</div>
        </div>

        <!-- PAYMENT FORM -->
        <form id="paymentForm" action="payment_page.php" method="POST">
            <input type="hidden" name="movie_id" value="<?= $movie_id ?>">
            <input type="hidden" name="selectedSeats" id="selectedSeats">
            <input type="hidden" name="total_amount" id="total_amount_hidden">
            <input type="hidden" name="showtime_id" id="form_showtime_id">

            <div class="totalPriceDisplay">
                <h4>Total: <span id="totalPriceDisplay">0.00 SAR</span></h4>
            </div>

            <button id="proceedBtn" class="next-btn" disabled>Proceed to Payment</button>
        </form>
    </div>
</div>

<script>
    // Preset showtime id for JS use
    const presetShowtimeId = "<?= $presetShowtimeId ?>";
</script>

<script src="script.js"></script>

<footer>
    &copy; 2025 Cinema Booking System
</footer>
</body>
</html>
