<?php
require_once 'Database_connect.php';
session_start(); 

$msg = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_review'])) {
    if (!isset($_SESSION['user_id'])) {
        $msg = "Please login to leave a review.";
    } else {
        $u_id = $_SESSION['user_id'];
        $m_id = isset($_POST['movie_id_form']) ? (int)$_POST['movie_id_form'] : 0;
        $score = isset($_POST['rating']) ? (int)$_POST['rating'] : 0;
        $comment = trim($_POST['comment']);

        if ($m_id > 0 && $score > 0 && !empty($comment)) {
            $check = $conn->query("SELECT rating_id FROM ratings WHERE movie_id=$m_id AND user_id=$u_id");
            if ($check->num_rows > 0) {
                $msg = "You have already rated this movie.";
            } else {
                $stmt_insert = $conn->prepare("INSERT INTO ratings (movie_id, user_id, score, comment) VALUES (?, ?, ?, ?)");
                $stmt_insert->bind_param("iiis", $m_id, $u_id, $score, $comment);
                if ($stmt_insert->execute()) {
                    header("Location: details.php?movie_id=" . $m_id);
                    exit();
                } else {
                    $msg = "Error submitting review.";
                }
                $stmt_insert->close();
            }
        } else {
            $msg = "Please select a star rating and write a comment.";
        }
    }
}


$is_search = false;
$search_param = null;
$param_type = null;
$movie_found = false; 

if (isset($_GET['movie_id']) && is_numeric($_GET['movie_id'])) {
    $search_param = (int)$_GET['movie_id'];
    $param_type = "i"; 
} elseif (isset($_GET['search']) && !empty(trim($_GET['search']))) {
    $search_query = "%" . trim($_GET['search']) . "%"; 
    $search_param = $search_query;
    $param_type = "s"; 
    $is_search = true;
}


$movie_title = "Movie Not Found";
$movie_description = "";
$movie_poster_url = "images/poster_placeholder.jpg";
$movie_duration = "";
$movie_language = "";
$genre_name = "";
$average_rating = "0";
$trailer_embed_url = "";
$reviews_data = [];
$movie_id = 0; 

if ($search_param !== null) {
    $sql_movie = "
        SELECT m.movie_id, m.title, m.description, m.duration, m.language, m.poster, m.trailer_id, g.genreName, AVG(r.score) AS avg_rating
        FROM movies m
        LEFT JOIN Genres g ON m.genre_id = g.genre_id
        LEFT JOIN ratings r ON r.movie_id = m.movie_id
        WHERE " . ($is_search ? "m.title LIKE ?" : "m.movie_id = ?") . " 
        GROUP BY m.movie_id LIMIT 1";

    $stmt = $conn->prepare($sql_movie);
    $stmt->bind_param($param_type, $search_param);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $movie_found = true;
        $movie_id = $row['movie_id']; 
        $movie_title = $row['title'];
        $movie_description = $row['description'];
        if (!empty($row['poster'])) { $movie_poster_url = "images/" . $row['poster']; }
        if (!empty($row['duration'])) { $movie_duration = $row['duration'] . " minutes"; }
        $movie_language = $row['language'];
        $genre_name = $row['genreName'];
        if ($row['avg_rating'] !== null) { $average_rating = number_format($row['avg_rating'], 1); }
        if (!empty($row['trailer_id'])) { $trailer_embed_url = "https://www.youtube.com/embed/" . $row['trailer_id']; }
    }
    $stmt->close();
}

if ($movie_found) {
    $sql_reviews = "SELECT u.name, r.score, r.comment FROM ratings r INNER JOIN users u ON r.user_id = u.user_id WHERE r.movie_id = ? ORDER BY r.rating_id DESC";
    $stmt = $conn->prepare($sql_reviews);
    $stmt->bind_param("i", $movie_id);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) { $reviews_data[] = $row; }
    $stmt->close();
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($movie_title); ?></title>
    <link rel="stylesheet" href="style.css">
    <style>
    
        .star.active { color: #f5a623 !important; }
        .stars .star { cursor: pointer; font-size: 25px; color: #ccc; }
    </style>
</head>
<body>

<header class="top-header">
    <h1 class="page-title"><?php echo strtoupper(htmlspecialchars($movie_title)); ?></h1>
</header>

<main class="movie-details">
    <?php if ($movie_found): ?>
        <section class="movie-summary box">
            <div class="poster">
                <img src="<?php echo htmlspecialchars($movie_poster_url); ?>" alt="Poster">
            </div>
            <div class="summary-info">
                <h1><?php echo htmlspecialchars($movie_title); ?></h1>
                <p class="genre-tag"><?php echo htmlspecialchars($genre_name); ?></p>
                <div class="rating-box">⭐ <span><?php echo htmlspecialchars($average_rating); ?></span></div>
                <div class="quick-details">
                    <p><strong>Duration:</strong> <?php echo htmlspecialchars($movie_duration); ?></p>
                    <p><strong>Language:</strong> <?php echo htmlspecialchars($movie_language); ?></p>
                </div>
                <a href="booking_page.php?movie_id=<?php echo $movie_id; ?>" class="book-now-btn">Book Now</a>
            </div>
        </section>

        <section class="movie-content box">
            <h2>Synopsis</h2>
            <p><?php echo nl2br(htmlspecialchars($movie_description)); ?></p>
            <h2>Trailer</h2>
            <div class="trailer-container">
                <?php if (!empty($trailer_embed_url)): ?>
                    <iframe src="<?php echo htmlspecialchars($trailer_embed_url); ?>" frameborder="0" allowfullscreen></iframe>
                <?php else: ?>
                    <p>No trailer available.</p>
                <?php endif; ?>
            </div>
        </section>

        <section class="reviews-section box">
            <h2>User Reviews</h2>
            <div class="add-review-box">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <h3>Write a Review</h3>
                    <?php if (!empty($msg)): ?><p style="color: red;"><?php echo htmlspecialchars($msg); ?></p><?php endif; ?>
                    <form class="review-form" id="reviewForm" method="POST">
                        <input type="hidden" name="movie_id_form" value="<?php echo $movie_id; ?>">
                        <label>Rating:</label>
                        <div class="stars">
                            <span class="star" data-value="1">★</span>
                            <span class="star" data-value="2">★</span>
                            <span class="star" data-value="3">★</span>
                            <span class="star" data-value="4">★</span>
                            <span class="star" data-value="5">★</span>
                        </div>
                        <input type="hidden" id="ratingValue" name="rating" required>
                        <label>Comment:</label>
                        <textarea name="comment" placeholder="What did you think?" required></textarea>
                        <button type="submit" name="submit_review" class="submit-review-btn">Post Review</button>
                    </form>
                <?php else: ?>
                    <p>Please <a href="login.php" style="color: #d4af37;">Login</a> to leave a review.</p>
                <?php endif; ?>
            </div>

            <div class="user-reviews-list">
                <?php foreach ($reviews_data as $review): ?>
                    <div class="review-item">
                        <p><strong><?php echo htmlspecialchars($review['name']); ?></strong></p>
                        <p><?php for($i=1; $i<=5; $i++) echo ($i <= $review['score']) ? '<span style="color:#f5a623;">★</span>' : '<span style="color:#ccc;">★</span>'; ?></p>
                        <p><?php echo nl2br(htmlspecialchars($review['comment'])); ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>

    <?php else: ?>
        <section class="box" style="text-align: center; padding: 80px 20px;">
            <div style="font-size: 60px; margin-bottom: 20px;">🎬</div>
            <h2 style="color: #d4af37;">Movie Not Found</h2>
            <p style="font-size: 1.1rem; color: #666; margin-bottom: 30px;">Sorry, we couldn't find any movie matching your search criteria.</p>
            <a href="home_page.php" class="book-now-btn" style="text-decoration: none; display: inline-block;">Back to Home</a>
        </section>
    <?php endif; ?>
</main>

<script>
    const stars = document.querySelectorAll(".star");
    const ratingInput = document.getElementById("ratingValue");

    stars.forEach((star) => {
        star.addEventListener("click", () => {
            const value = parseInt(star.getAttribute("data-value"));
            ratingInput.value = value; 
            stars.forEach((s, index) => {
                if (index < value) {
                    s.classList.add("active");
                } else {
                    s.classList.remove("active");
                }
            });
        });
    });

    const reviewForm = document.getElementById("reviewForm");
    if(reviewForm){
        reviewForm.addEventListener("submit", function(e){
            if(!ratingInput.value){
                e.preventDefault();
                alert("Please select a star rating!");
            }
        });
    }
</script>

<footer>
    <p>&copy; 2025 Cinema Booking System</p>
</footer>

</body>
</html>