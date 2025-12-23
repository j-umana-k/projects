<?php
require_once "db.php";
$message = "";
// 1. Get the Recipe ID from the URL
$recipe_id = $_GET['id'] ?? null;

// Ensure ID is present and is an integer
if (!is_numeric($recipe_id) || $recipe_id <= 0) {

    die("Error: Invalid or missing Recipe ID.");
}

// 2. Prepare and Execute the Database Query
$safe_id = mysqli_real_escape_string($conn, $recipe_id);
$sql = "SELECT * FROM recipes WHERE id = '$safe_id'";

$result = mysqli_query($conn, $sql);

// 3. Check if the recipe was found
if ($result && mysqli_num_rows($result) > 0) {
    $recipe = mysqli_fetch_assoc($result);
} else {

    die("Error: Recipe not found.");
}

mysqli_close($conn);

// Helper function to safely output text
function e($text) {
    return htmlspecialchars($text ?? '[N/A]');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="style.css">
<title><?= e($recipe['dessert_name']) ?> | Recipe View</title>
</head>
<body>
    
 <header class = "main-header">
        <nav class="navBar">
            <a href="index.php" class= "navLogo">
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

<main class="page-content-view-recipe">
    
    <div class="recipe-image-container">
        <img src="<?= e($recipe['image_url']) ?>" alt="Image of <?= e($recipe['dessert_name']) ?>" style="max-width: 100%; height: auto;">
    </div>

    <h2><?= e($recipe['dessert_name']) ?></h2>

    <div class="recipe-meta">
        <p><strong>Type:</strong> <span><?= e($recipe['type']) ?></span></p>
        <p><strong>Tags:</strong> <span><?= e($recipe['tags']) ?></span></p>
    </div>

    <hr>
    
    <h3>Ingredients:</h3>
    <div class="ingredients-list">
        <?= nl2br(e($recipe['ingredients'])) ?>
    </div>

    <h3>Instructions:</h3>
    <div class="instructions-block">
        <?= nl2br(e($recipe['instructions'])) ?>
    </div>

    <hr>

     <a href="edit_recipe.php?id=<?php echo $recipe_id; ?>" class="back-btn">Edit Recipe</a>
    <a href="recipes.php" class="btn-primary">← Back to All Recipes</a>
    
</main>

<footer class="main-footer"> 
    <p>&copy; 2025 Code & Crumble Project. All rights reserved.</p>
    <div class="footer-links">
        <a href="about.php">Our Mission</a> | 
        <a href="contact.php">Contact</a>
    </div>
</footer>

</body>
</html>