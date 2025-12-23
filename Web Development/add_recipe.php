<?php
session_start();
require_once "db.php";
$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    //  Get the form values
    // If the session has a user_id, use it. Otherwise, default to 1.
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 1;
    $dessert_name = htmlspecialchars($_POST["dessert_name"]);
    $description  = htmlspecialchars($_POST["description"]);
    $type         = htmlspecialchars($_POST["type"]);
    $ingredients  = htmlspecialchars($_POST["ingredients"]);
    $instructions = htmlspecialchars($_POST["instructions"]);

    // Tags (array to string)
    $tags = isset($_POST['tag']) ? $_POST['tag'] : [];
    $tags_str = implode(",", $tags);

    // Simple validation
    if ($dessert_name === "" || $description === "" || $type === "" || $instructions === "" || $ingredients === "") {

        $message = "error";

    } else {

        // Image upload
        $image_name = "";

        if (!empty($_FILES['recipe_file']['name'])) {
            $image_name = time() . "_" . basename($_FILES['recipe_file']['name']);
            move_uploaded_file($_FILES['recipe_file']['tmp_name'], "uploads/" . $image_name);
        }

        // SQL Insert
        $sql = "INSERT INTO recipes (user_id, dessert_name, description, type, tags, ingredients, instructions, image_url) 
                VALUES ('$user_id', '$dessert_name', '$description', '$type', '$tags_str', '$ingredients', '$instructions', 'uploads/$image_name')";
        
        // CRITICAL FIX: Execute the SQL and set the success flag
        if (mysqli_query($conn, $sql)) {
            
            // Get the ID of the newly created recipe
            $new_recipe_id = mysqli_insert_id($conn);
            
            // Set the message to success so the JavaScript runs
            $message = "success";
            
        } else {
            // Handle database insert failure
            $message = "error"; 
            // If the notification still doesn't appear, you can temporarily 
            // uncomment the line below to see the exact MySQL error:
            // die("Database error: " . mysqli_error($conn));
        }

    } // end of the main 'else' block
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add Recipe</title>

  <link rel="stylesheet" href="style.css"> 
</head>
<body class="add-page">

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

  <h1>Share Your Recipe:</h1>

<form action="" method="post" enctype="multipart/form-data"onsubmit="return validateForm()">

  <div class="form-container">

    <div class="column-left">
      <div class="section-details">
        <label for="dessert_name">Dessert Name: *</label>
        <input type="text" id="dessert_name" name="dessert_name" placeholder="Chocolate Cake" required>
        
        <label for="description">Description: *</label>
    <input type="text" id="description" name="description" placeholder="A short description of your dessert..." required>

        <div class="recipe-type">
            <label>Type: *</label>
            <input type="radio" id="type-cake" name="type" value="Cake" required><label for="type-cake">Cake</label>
            <input type="radio" id="type-cookie" name="type" value="Cookie"><label for="type-cookie">Cookie</label>
            <input type="radio" id="type-pie" name="type" value="Pie"><label for="type-pie">Pie</label><br><br>
        </div>
      </div>

      <div class="section-tags">
        <label>Tags: </label>
        <input type="checkbox" id="tag-quick" name="tag[]" value="Quick"><label for="tag-quick">Quick</label>
        <input type="checkbox" id="tag-gluten-free" name="tag[]" value="gluten-free"><label for="tag-gluten-free">Gluten-free</label>
        <input type="checkbox" id="tag-healthy" name="tag[]" value="Healthy"><label for="tag-healthy">Healthy</label><br><br>
      </div>

      <div class="section-instructions">
        <label for="ingredients">Ingredients: *</label>
        <textarea id="ingredients" name="ingredients" rows="4" maxlength="200" placeholder="Flour, sugar, chocolate..." ></textarea>

        <label for="instructions">Instructions: *</label>
        <textarea id="instructions" name="instructions" rows="4" placeholder="How to bake...." required></textarea>
      </div>

    </div> 

    <div class="column-right">
      <div class="section-photo">
        <label for="recipe_file" class="custom-file-upload"><img src="images/camera-icon.png" alt="Upload Dessert Image"></label>
        <input type="file" id="recipe_file" name="recipe_file" accept=".png,.jpg,.jpeg" onchange="updateStatus()">
        <label for="status">Image Status</label>
        <input type="text" id="status" value="No image uploaded" disabled>
      </div>
    </div>

  </div>

  <button type="submit" >Add Dessert</button>

</form>



<div id="miniAlert">
  <div id="miniAlertText"><h5>Thank you For Sharing ପ(๑•ᴗ•๑)ଓ ♡<h5> 
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
        window.location.href = "view_recipe.php?id=<?= $new_recipe_id ?>"; 
    };
  };
</script>
<?php endif; ?>


<script src="scripts.js"></script>
<footer class="main-footer"> 
    <p>&copy; 2025 Code & Crumble Project. All rights reserved.</p>
    <div class="footer-links">
        <a href="about.php">Our Mission</a> | 
        <a href="contact.php">Contact</a>
    </div>
</footer>
</body>
</html>

