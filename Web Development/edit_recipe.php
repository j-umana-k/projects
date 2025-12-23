<?php
require_once "db.php";  // Connect to the database

session_start();
// FIX: Define $user_id globally at the top 
$user_id = 1; 
$message = "";
$recipe = null;

// 1. Get recipe ID from GET and validate
$recipe_id = $_GET['id'] ?? null;
if (!is_numeric($recipe_id) || $recipe_id <= 0) {
    die("Invalid or missing Recipe ID.");
}

// 2. HANDLE FORM SUBMISSION FOR UPDATING RECIPE
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // 2.1 UPDATE RECIPE
    // FIX: Use null coalescing (?? '') for safe access to POST variables
    $dessert_name = htmlspecialchars($_POST['dessert_name'] ?? ''); 
    $description  = htmlspecialchars($_POST['description'] ?? '');  
    $type         = htmlspecialchars($_POST['type'] ?? '');         
    $ingredients  = htmlspecialchars($_POST['ingredients'] ?? '');  
    $instructions = htmlspecialchars($_POST['instructions'] ?? ''); 
    $tags         = isset($_POST['tag']) ? $_POST['tag'] : [];
    $tags_str     = implode(",", $tags);

    // Simple validation
    if ($dessert_name === "" || $description === "" || $type === "" || $instructions === "" || $ingredients === "") {
        $message = "error";
    } else {
        
        // Image handling
        $new_image_url = "";
        if (!empty($_FILES['recipe_file']['name'])) {
            $image_name = time() . "_" . basename($_FILES['recipe_file']['name']);
            move_uploaded_file($_FILES['recipe_file']['tmp_name'], "uploads/" . $image_name);
            $new_image_url = "uploads/" . $image_name;
        }

        // --- Dynamic SQL Update ---
        $sql_update = "UPDATE recipes SET 
                        dessert_name=?, 
                        description=?, 
                        type=?, 
                        tags=?, 
                        ingredients=?, 
                        instructions=?";

        $param_types = "ssssss"; 
        $param_values = [
            $dessert_name, $description, $type, $tags_str, $ingredients, $instructions
        ];

        // Conditionally append image field
        if (!empty($new_image_url)) {
            $sql_update .= ", image_url=?";
            $param_types .= "s";
            $param_values[] = $new_image_url;
        }
        
        // Finalize WHERE clause
        $sql_update .= " WHERE id=? AND user_id=?";
        $param_types .= "ii";
        $param_values[] = $recipe_id; 
        $param_values[] = $user_id;   

        // Execute the Prepared Statement
        $stmt = $conn->prepare($sql_update);
        
        if ($stmt === false) {
             $message = "error"; 
        } else {
            // FIX: Use reference operator for bind_param arguments
            $stmt->bind_param($param_types, ...$param_values); 
            
            if ($stmt->execute()) {
                $message = "success";
            }
            $stmt->close();
        }
    } 
} 

// 3. FETCH EXISTING RECIPE DATA FOR PREFILLING THE FORM

$safe_id = mysqli_real_escape_string($conn, $recipe_id);
$sql_fetch = "SELECT * FROM recipes WHERE id = '$safe_id' AND user_id = '$user_id'";
$result_fetch = mysqli_query($conn, $sql_fetch);

if ($result_fetch && mysqli_num_rows($result_fetch) > 0) {
    $recipe = mysqli_fetch_assoc($result_fetch); 
} else {
    die("Error: Recipe not found or you do not have permission.");
}

// Helper function to safely output text
if (!function_exists('e')) {
    function e($text) {
        return htmlspecialchars($text ?? '');
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Recipe</title>
  <link rel="stylesheet" href="style.css"> 
</head>
<body class="edit-page">

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

<h1>Edit Your Recipe:</h1>

<form action="" method="post" enctype="multipart/form-data" onsubmit="return validateForm()">

  <div class="form-container">

    <div class="column-left">
      <div class="section-details">
        <label for="dessert_name">Dessert Name: *</label>
        <input type="text" id="dessert_name" name="dessert_name" value="<?php echo htmlspecialchars($recipe['dessert_name']); ?>" required>

        <label for="description">Description: *</label>
        <input type="text" id="description" name="description" value="<?php echo htmlspecialchars($recipe['description']); ?>" required>

        <div class="recipe-type">
            <label>Type: *</label>
            <input type="radio" id="type-cake" name="type" value="Cake" <?php if($recipe['type']=="Cake") echo "checked"; ?> required><label for="type-cake">Cake</label>
            <input type="radio" id="type-cookie" name="type" value="Cookie" <?php if($recipe['type']=="Cookie") echo "checked"; ?>><label for="type-cookie">Cookie</label>
            <input type="radio" id="type-pie" name="type" value="Pie" <?php if($recipe['type']=="Pie") echo "checked"; ?>><label for="type-pie">Pie</label><br><br>
        </div>
      </div>

      <div class="section-tags">
        <label>Tags: </label>
        <?php 
        $all_tags = ["Quick","gluten-free","Healthy"];
        foreach($all_tags as $tag):
            $checked = in_array($tag, explode(",",$recipe['tags'])) ? "checked" : "";
        ?>
        <input type="checkbox" name="tag[]" value="<?php echo $tag; ?>" <?php echo $checked; ?>><label><?php echo $tag; ?></label>
        <?php endforeach; ?>
      </div>

      <div class="section-instructions">
        <label for="ingredients">Ingredients: *</label>
        <textarea id="ingredients" name="ingredients" rows="4"><?php echo htmlspecialchars($recipe['ingredients']); ?></textarea>

        <label for="instructions">Instructions: *</label>
        <textarea id="instructions" name="instructions" rows="4" required><?php echo htmlspecialchars($recipe['instructions']); ?></textarea>
      </div>

    </div> 

    <div class="column-right">
      <div class="section-photo">
    <img src="<?= htmlspecialchars($recipe['image_url'] ?? 'images/placeholder.jpg') ?>" 
         alt="Current Recipe Image" 
         style="width: 100%; height: auto; margin-bottom: 10px;"> 
    
    </div>
    </div>

  </div>

  <button type="submit" class="save-changes-button" >Update Recipe</button>
  
  </form>

<footer class="main-footer"> 
    <p>&copy; 2025 Code & Crumble Project. All rights reserved.</p>
    <div class="footer-links">
        <a href="about.php">Our Mission</a> | 
        <a href="contact.php">Contact</a>
    </div>
</footer>

<div id="miniAlert">
  <div id="miniAlertText">
    <h5>Changes Saved successfully!<br> <br>ദ്ദി(˵ •̀ ᴗ - ˵ ) </h5>
    <button id="okAlert">OK</button>
  </div>
</div>

<?php if ($message === "success"): ?>
<script>
   window.onload = () => {
    const box = document.getElementById("miniAlert");
    box.style.display = "flex";

    document.getElementById("okAlert").onclick = () => {
        document.body.style.overflow = "auto";
        window.location.href = "recipes.php"; 
    };
  }; 
</script>

<?php endif; ?>

<script src="scripts.js"></script>
</body>
</html>