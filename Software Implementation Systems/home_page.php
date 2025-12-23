<?php
require_once 'Database_connect.php';


$sqlTopRated = "SELECT m.*, AVG(r.score) as avg_rating 
                FROM movies m 
                LEFT JOIN ratings r ON m.movie_id = r.movie_id 
                GROUP BY m.movie_id 
                ORDER BY avg_rating DESC 
                LIMIT 4";
$resultTop = $conn->query($sqlTopRated);
$soonMovies = ($resultTop) ? $resultTop->fetch_all(MYSQLI_ASSOC) : [];


$topRatedIds = array_column($soonMovies, 'movie_id');
$excludeIds = !empty($topRatedIds) ? implode(',', $topRatedIds) : '0';


$sqlNow = "SELECT * FROM movies 
           WHERE movie_id NOT IN ($excludeIds) 
           ORDER BY movie_id ASC 
           LIMIT 4";

$resultNow = $conn->query($sqlNow);
$nowMovies = ($resultNow) ? $resultNow->fetch_all(MYSQLI_ASSOC) : [];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style_home.css">
    <title>Home Page</title>
</head>
<body>

<header>
    <div class="header-content">
        <h1>BOOK NOW!</h1>
    </div>
    <div class="right-icons">
        <a href="signup.php" class="signup-link">Signup</a> 
        <a href="profile_page.php" class="profile-link">My Profile</a>
    </div>
</header>

<div class="search-container">
    <form action="details.php" method="get">
    <input type="text" name="search" id="search-bar" placeholder="Search for a movie..." required>
    <button type="submit">Search</button>
</form>
</div>

<main>
    <section id="now-showing">
        <h2>Now Showing!</h2>
        <div class="movie-list">
            <?php foreach ($nowMovies as $movie): ?>
                <div class="movie-item">
                    <a href="details.php?movie_id=<?= $movie['movie_id'] ?>">
                        <div class="poster-wrapper"></div>
                        <img src="images/<?= htmlspecialchars($movie['poster']) ?>" alt="<?= htmlspecialchars($movie['title']) ?>">
                        <h3><?= htmlspecialchars($movie['title']) ?></h3>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <section id="comming-soon">
        <h2>Top Rated Movies ⭐</h2>
        <div class="movie-list">
            <?php foreach ($soonMovies as $movie): ?>
                <div class="movie-item">
                    <a href="details.php?movie_id=<?= $movie['movie_id'] ?>">
                        <div class="poster-wrapper"></div>
                        <img src="images/<?= htmlspecialchars($movie['poster']) ?>" alt="<?= htmlspecialchars($movie['title']) ?>">
                        <h3><?= htmlspecialchars($movie['title']) ?></h3>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
</main>

<footer>
    <div id="contact-us">
        <h2>Contact Us</h2>
        <p><strong>Email:</strong> cinema.booking@gmail.com</p>
        <p><strong>Phone:</strong> +966 50 988 7375</p>
    </div>
    <p>&copy; 2025 All rights reserved.</p>
</footer>

</body>
</html>