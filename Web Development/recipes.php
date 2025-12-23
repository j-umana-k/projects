<?php
// CRITICAL: Use require_once for essential files like the database connection
require_once "db.php"; 
$message = "";

// 1. Get user input from the GET request and initialize variables safely
// The '??' operator ensures the variable is set to '' if nothing is in the URL.
$search_query = $_GET['query'] ?? '';
$category_filter = $_GET['cat'] ?? '';
$where_clauses = [];
$sql = "SELECT * FROM recipes"; // Base query

// 2. Handle Search Query
if (!empty($search_query)) {
    // SECURITY: Sanitize the search term to prevent SQL injection
    $safe_query = mysqli_real_escape_string($conn, $search_query);
    
    // Build the search condition: search across name, ingredients, and instructions
    $where_clauses[] = "
        (dessert_name LIKE '%$safe_query%' 
        OR ingredients LIKE '%$safe_query%' 
        OR instructions LIKE '%$safe_query%')
    ";
}

// 3. Handle Category Filter
if (!empty($category_filter)) {
    // SECURITY: Sanitize the category term
    $safe_category = mysqli_real_escape_string($conn, $category_filter);
    
    // Build the category condition: look for an exact match
    $where_clauses[] = "type = '$safe_category'";
}

// 4. Construct the Final SQL Query
if (!empty($where_clauses)) {
    // If we have conditions, add WHERE and join all conditions with ' AND '
    $sql .= " WHERE " . implode(' AND ', $where_clauses);
}

// 5. Add Ordering
$sql .= " ORDER BY id DESC";

// 6. Execute the query
$result = mysqli_query($conn, $sql);

// Handle query error (good practice)
if (!$result) {
    // This is useful for debugging database connection/query issues
    die("Database query failed: " . mysqli_error($conn));
}
?>


<!DOCTYPE html>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name ="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">

    <title>All Recipes | Code & Crumble</title>

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

<main class="page-content-recipes">
    
    <h1>All Code & Crumble Recipes</h1>

    <a href="add_recipe.php" class="btn-primary" style="margin-bottom: 20px;">
        + Submit Your Own Recipe
    </a>


    <section class="filter-controls">
    
    <form action="recipes.php" method="GET" class="search-form">
    <label for="search">Search Recipes:</label>
    <input type="text" id="search" name="query" placeholder="e.g., Cake or vegan FOR EXAMPLE "
           value="<?= htmlspecialchars($search_query) ?>"> 
    <button type="submit" class="btn-search">Search</button>
</form>

    <form action="recipes.php" method="GET" class="category-filter">
    <label for="category">Filter by Category:</label>
    <select id="category" name="cat">
        <option value="">All Categories</option> 
        <option value="cake"    <?= ($category_filter === 'cake' ? 'selected' : '') ?>>Cake</option>
        <option value="cookie"  <?= ($category_filter === 'cookie' ? 'selected' : '') ?>>Cookie</option>
        <option value="pie"     <?= ($category_filter === 'pie' ? 'selected' : '') ?>>Pie</option>
        <option value="other"   <?= ($category_filter === 'other' ? 'selected' : '') ?>>Other</option>
    </select>
    <button type="submit" class="btn-filter">Filter</button>
</form>

</section>

<div class="recipe-grid">
<?php 
// CRITICAL: Start the loop here. It checks if there are any results and assigns the data to $row for each cycle.
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
?>

    <div class="recipe-card">
        
        <img src="<?= htmlspecialchars($row['image_url'] ?? 'images/placeholder.jpg') ?>" alt="<?= htmlspecialchars($row['dessert_name']) ?>">

        <div class="card-content">
            
            <h4><?= htmlspecialchars($row['dessert_name']) ?></h4> 
            
            <p>
            <?php 
                // Display the description, or a snippet of instructions if description is empty
                if (!empty($row['description'])) {
                    echo htmlspecialchars($row['description']);
                } else {
                    echo htmlspecialchars(substr($row['instructions'], 0, 100)) . '...';
                }
            ?>
            </p>

            <a href="view_recipe.php?id=<?= $row['id'] ?>">View Recipe</a>
            
        </div>
    </div>

<?php 
    } // End of the while loop
} else {
    // Message if no recipes are found
    echo "<p style='text-align:center;'>No recipes found! Start by submitting one.</p>";
}
?>
</div>
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