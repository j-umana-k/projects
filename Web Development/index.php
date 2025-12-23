<?php
require_once "db.php";
$message = "";
// 1. qurey for "Today's Top Pick"
// Fetches the single recipe marked as 'Top Pick'
$top_pick_sql = "SELECT id, dessert_name, description FROM recipes WHERE featured_status = 'Top Pick' LIMIT 1";
$top_pick_result = mysqli_query($conn, $top_pick_sql);
$top_pick = mysqli_fetch_assoc($top_pick_result);

// 2. query for "Honorable Mentions"
// Fetches up to 3 recipes marked as 'Honorable Mention'
$honors_sql = "SELECT id, dessert_name, description, image_url, instructions FROM recipes WHERE featured_status = 'Honorable Mention' ORDER BY created_at DESC LIMIT 3";
$honors_result = mysqli_query($conn, $honors_sql);

// Function to safely escape output
function e($text) {
    return htmlspecialchars($text ?? '');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name ="viewport" content="width=device-width, initial-scale=1.0">
    <title>Code & Crumble | Sweet Recipes </title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <!-- (Cross-Origin Resource Sharing) domain for the font -->
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&family=Playfair+Display:wght@700;900&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
</head>
<body>
    <header  class = "main-header">
        <nav class="navBar">
            <a href="#" class= "navLogo">
                <h2 class = "logoText">Code & Crumble</h2>
            </a>
               <ul class ="nav-menu">
                <li class="navItem">
                    <a href="index.php" class ="navLink">Home</a>
                </li> 
                <li class="navItem">
                    <a href="recipes.php" class ="navLink">Recipes</a>
                </li>    
                    
                <li class="navItem">
                    <a href="login.php" class ="navLink">Login</a>
                </li> 

                <li class="navItem">
                    <a href="about.php" class ="navLink">About</a>
                </li>  
                
                 <li class="navItem">
                    <a href="contact.php" class ="navLink">Contact</a>
                </li>  
                
                
                
            </ul>
        </nav>
    </header>

<main class = "page-content">

   <section id = "hero" >
    <div class="hero-content">
      <h2>Welcome to Code & Crumble</h2>
      <p>Where coding meets baking — discover, bake, and share the sweetest recipes from around the world.</p>  
      <a href="recipes.php" class="btn-primary">Explore All Recipes</a>
    </div>
</section>

<section id="mission" class="site-summary">
    <h2>Our Philosophy: Code Meets Crumble</h2>
    <p class="mission-text">
       <i>We believe that the best creations come from a blend of precision and passion. Just as a perfectly executed line of code builds a functional application, precise measurements and technique yield a flawless dessert. Code & Crumble is the bridge between these worlds, offering meticulously crafted recipes and tools for the modern baker.</i> 
    </p>
</section>


    <section id="featured" class="recipe-of-the-day">

        <div class="cookie-graphic-backdrop"></div>
        <div class="cake-graphic-backdrop-left"></div>
        
        <div class="daily-recipe-placeholder">

            <?php if ($top_pick): ?>
                <h3>Today's Top Pick: <?= e($top_pick['dessert_name']) ?></h3>
                <p><?= e($top_pick['description']) ?></p>
                <a href="recipe_of_day.php?id=<?= e($top_pick['id']) ?>" class="btn-primary">Pick a random recipe!!</a>
            <?php else: ?>
                <h3>Today's Top Pick!</h3>
                <p>Check back soon for our featured recipe of the day! We need to set a 'Top Pick' in the database.</p>
                <a href="recipes.php" class="btn-primary">Explore All Recipes</a>
            <?php endif; ?>
        </div>


        <h3>Honorable Mentions</h3>
        
        <div class="mentions-intro">
            <p>Our top trending desserts, hand-picked by the Code & Crumble community for their exceptional flavor and visual appeal. Give them a try!</p>
        </div>

        <div class="honorable-mentions recipe-grid">
            
            <?php 
            if (mysqli_num_rows($honors_result) > 0) {
                while ($row = mysqli_fetch_assoc($honors_result)) {
            ?>
                <div class="recipe-card">
                    <img src="<?= e($row['image_url'] ?? 'images/placeholder.jpg') ?>" alt="<?= e($row['dessert_name']) ?>">
                    
                    <div class="card-content">
                        <h4><a href="view_recipe.php?id=<?= e($row['id']) ?>"><?= e($row['dessert_name']) ?></a></h4>
                        
                        <p>
                        <?php 
                            if (!empty($row['description'])) {
                                echo e($row['description']);
                            } else {
                                echo e(substr($row['instructions'], 0, 100)) . '...';
                            }
                        ?>
                        </p>
                        
                        <a href="view_recipe.php?id=<?= e($row['id']) ?>">View Recipe</a>
                    </div>
                </div>
            <?php 
                } // end while
            } else {
                echo "<p style='grid-column: 1 / -1; text-align: center;'>No Honorable Mentions selected yet. Please set some in phpMyAdmin.</p>";
            }
            ?>

        </div>

        <p class="view-all-link">
            <a href="recipes.php">See All Recipes →</a>
        </p>

    </section>

</main>


<div class="site-graphic graphic-right"></div>

<footer class="main-footer"> 
    <p>&copy; 2025 Code & Crumble Project. All rights reserved.</p>
    <div class="footer-links">
        <a href="about.php">Our Mission</a> | 
        <a href="contact.php">Contact</a>
    </div>
</footer>

</body>
</html>