<?php
session_start();

// Database connection
require_once "Database_connect.php";

// Get booking data from booking page
$selectedSeats = "";
$totalAmount = 0;
$showtime_id = "";

if (isset($_POST['selectedSeats'])) {
    $selectedSeats = $_POST['selectedSeats'];
}

if (isset($_POST['total_amount'])) {
    $totalAmount = $_POST['total_amount'];
}

if (isset($_POST['showtime_id'])) {
    $showtime_id = $_POST['showtime_id'];
}

// Convert seat_id to seat_number for display
$seatLabels = [];
if (!empty($selectedSeats)) {
    $seatIds = array_map('intval', explode(",", $selectedSeats));
    $ids = implode(",", $seatIds);

    $result = $conn->query("
        SELECT seat_number
        FROM seats
        WHERE seat_id IN ($ids)
        ORDER BY seat_number
    ");

    while ($row = $result->fetch_assoc()) {
        $seatLabels[] = $row['seat_number'];
    }
}

// Process payment
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pay_now_submit'])) {

    $user_id = 1;
    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
    }

    $showtime_id = intval($_POST['showtime_id']);
    $selectedSeatsArr = explode(",", $_POST['selectedSeats']);
    $amount = floatval($_POST['total_amount']);
    $method = $_POST['payMethod'];

    // Insert booking
    $stmt = $conn->prepare("INSERT INTO bookings (user_id, showtime_id) VALUES (?, ?)");
    $stmt->bind_param("ii", $user_id, $showtime_id);
    $stmt->execute();
    $booking_id = $stmt->insert_id;
    $stmt->close();

    // Insert seats
    $stmt = $conn->prepare("INSERT INTO booking_seats (booking_id, seat_id) VALUES (?, ?)");
    foreach ($selectedSeatsArr as $seat_id) {
        $seat_id = intval($seat_id);
        $stmt->bind_param("ii", $booking_id, $seat_id);
        $stmt->execute();
    }
    $stmt->close();

    // Insert payment
    $status = "Completed";
    $stmt = $conn->prepare("
        INSERT INTO payments (booking_id, method, amount, status)
        VALUES (?, ?, ?, ?)
    ");
    $stmt->bind_param("isds", $booking_id, $method, $amount, $status);
    $stmt->execute();
    $stmt->close();

    $conn->close();

    // Redirect to confirmation page
    header("Location: booking_confirmation.php?booking_id=" . $booking_id);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

<header class="top-header">
    <h1 class="page-title">Payment</h1>
</header>

<main class="payment-page">

    <div class="box">
        <h3>
            Total Amount:
            <?php echo number_format($totalAmount, 2); ?> SAR
        </h3>

        <p>
            <strong>Selected Seats:</strong>
            <?php echo implode(", ", $seatLabels); ?>
        </p>
    </div>

    <form id="paymentForm" class="box" method="POST">

        <input type="hidden" name="showtime_id"
               value="<?php echo htmlspecialchars($showtime_id); ?>">

        <input type="hidden" name="selectedSeats"
               value="<?php echo htmlspecialchars($selectedSeats); ?>">

        <input type="hidden" name="total_amount"
               value="<?php echo htmlspecialchars($totalAmount); ?>">

        <input type="hidden" name="pay_now_submit" value="1">

        <div class="payment-methods box">
            <h3>Select Payment Method</h3>

            <div class="methods">
                <label>
                    <input type="radio" name="payMethod" value="Credit Card">
                    <img src="images/creditcard.png" alt="Credit Card">
                </label>

                <label>
                    <input type="radio" name="payMethod" value="Mada">
                    <img src="images/mada.png" alt="Mada">
                </label>

                <label>
                    <input type="radio" name="payMethod" value="Apple Pay">
                    <img src="images/applepay.png" alt="Apple Pay">
                </label>
            </div>
        </div>

        <label>
            Card Holder Name:
            <input type="text" id="cardName" name="cardName"
placeholder="Name on Card"
                   pattern="[A-Za-z ]+" required disabled>
        </label>

        <label>
            Card Number:
            <input type="text" id="cardNumber" name="cardNumber"
                   placeholder="0000 0000 0000 0000"
                   pattern="[0-9]{16}" maxlength="16"
                   required disabled>
        </label>

        <label>
            Expiry Date:
            <input type="month" id="expiry" name="expiry"
                   required disabled>
        </label>

        <label>
            CVV:
            <input type="password" id="cvv" name="cvv"
                   placeholder="123"
                   pattern="[0-9]{3}" maxlength="3"
                   required disabled>
        </label>

        <button type="submit" class="pay-btn" disabled>
            Pay Now
        </button>
    </form>
     <button type="button" class="cancel-btn"
            onclick="window.location.href='home_page.php'">
        Cancel
    </button>

</main>

<script src="script.js"></script>

<footer>
    &copy; 2025 Cinema Booking System
</footer>

</body>
</html>
