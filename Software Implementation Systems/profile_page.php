<?php
session_start();
require_once 'Database_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id']; 
$user_name = "Guest";
$bookings_data = [];

$sql_user = "SELECT name FROM users WHERE user_id = ?";
$stmt = $conn->prepare($sql_user);
if ($stmt) {
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $user_name = $row['name'];
    }
    $stmt->close();
}

$sql_bookings = "
    SELECT 
        b.booking_id,
        m.title AS movie_title,
        h.name AS hall_name, 
        p.status AS payment_status,
        GROUP_CONCAT(s.seat_number SEPARATOR ', ') AS seat_numbers
    FROM bookings b
    JOIN showtimes sh ON b.showtime_id = sh.showtime_id
    JOIN halls h ON sh.hall_id = h.hall_id 
    JOIN movies m ON sh.movie_id = m.movie_id
    LEFT JOIN payments p ON b.booking_id = p.booking_id
    JOIN booking_seats bs ON b.booking_id = bs.booking_id
    JOIN seats s ON bs.seat_id = s.seat_id
    WHERE b.user_id = ?
    GROUP BY b.booking_id
    ORDER BY b.booking_date DESC
";

$stmt = $conn->prepare($sql_bookings);
if ($stmt) {
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $status = !empty($row['payment_status']) ? $row['payment_status'] : 'Pending';
        $bookings_data[] = [
            'booking_id'   => $row['booking_id'],
            'movie_title'  => $row['movie_title'],
            'hall_name'    => $row['hall_name'], 
            'seat_numbers' => $row['seat_numbers'],
            'status'       => $status
        ];
    }
    $stmt->close();
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile</title>
    <link rel="stylesheet" href="profile_page.css"> 
</head>
<body class="p-body">

    <header class="p-header">
        <a href="home_page.php" class="p-home-btn">&larr; Home</a>
        <h1 class="p-title">Welcome, <?php echo htmlspecialchars($user_name); ?></h1>
    </header>

    <main class="p-container">
        <h2 class="p-subtitle">Your Bookings</h2>

        <?php if (empty($bookings_data)): ?>
            <div class="p-empty">
                <p>No bookings yet.</p>
                <a href="home_page.php" style="color:#d4af37;">Book a movie now</a>
            </div>
        <?php else: ?>
            
            <?php foreach ($bookings_data as $ticket): ?>
                
                <div class="p-ticket">
                    <div class="p-ticket-header">
                        <h3 class="p-movie-name"><?php echo htmlspecialchars($ticket['movie_title']); ?></h3>
                        <span class="p-status"><?php echo htmlspecialchars($ticket['status']); ?></span>
                    </div>

                    <div class="p-info-row">
                        <div>
                            <span class="p-label">BOOKING ID</span>
                            <span class="p-value">#<?php echo $ticket['booking_id']; ?></span>
                        </div>

                        <div style="text-align: center;">
                            <span class="p-label">HALL</span>
                            <span class="p-value"><?php echo htmlspecialchars($ticket['hall_name']); ?></span>
                        </div>

                        <div style="text-align: right;">
                            <span class="p-label">SEATS</span>
                            <span class="p-value"><?php echo htmlspecialchars($ticket['seat_numbers']); ?></span>
                        </div>
                    </div>

                    <div class="p-barcode">
                        <img src="images/barcode.jpg" alt="Code">
                        <div class="p-barcode-text">Scan for Entry</div>
                    </div>
                </div>

            <?php endforeach; ?>

        <?php endif; ?>
    </main>

</body>
</html>