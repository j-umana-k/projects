<?php 
require_once "db.php";  // Connect to the database

$signup_message = "";  // Keep empty unless there's an error

// Check if the form was submitted using POST
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["name"], $_POST["Email"], $_POST["password"])) { 

    // 1) Get the form values
    $name  = htmlspecialchars($_POST["name"]);
    $email = htmlspecialchars($_POST["Email"]);
    $pass  = htmlspecialchars($_POST["password"]);

    // 2) Simple validation
    if ($name === "" || $email === ""  || $pass === "") { 
        $signup_message = "<p class='error-message'>Please fill in all fields</p>"; 
    } else {

        // 3) SQL query to check if email already exists
        $sql = "SELECT id FROM users WHERE email = '$email'";
        $result = $conn->query($sql);

        // FIX 1: Check if the query succeeded ($result is not false) before accessing num_rows
        if ($result && $result->num_rows > 0) {
            $signup_message = "<p class='error-message'>Email already exists</p>";
        } else {

            // 4) Hash password and insert new user
            $hashed_password = password_hash($pass, PASSWORD_DEFAULT);
            // signup.php, Line 30: CORRECTED
$sql_insert = "INSERT INTO users (username, email, password_hash) VALUES ('$name', '$email', '$hashed_password')";

            if ($conn->query($sql_insert) !== TRUE) {
                $signup_message = "<p class='error-message'>Error saving user: " . $conn->error . "</p>";
            } else {
                // Redirect to index page on successful signup
                header("Location: index.php");
                exit;
            }
        }
    }
}

// FIX 2: Check if $conn is a valid object before attempting to close the connection.
// This prevents the Fatal error if the connection failed to open in db.php or failed critically later.
if (isset($conn) && is_object($conn)) {
    $conn->close(); // Line 53
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign Up</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="signup-page">

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

<h1>Create a new account</h1>

<div class="signup-container">
<main>
    <?php if (!empty($signup_message)) echo $signup_message; ?>

    <form action="" method="POST"> 
        <label for="name">Enter your user name:</label><br>
        <input type="text" id="name" name="name" required placeholder="Enter your user name"
               pattern="^[a-zA-Z0-Z0-9]+$" 
               title="Name can only contain letters and numbers, no spaces or special characters"><br>

        <label for="Email">Enter your Email* :</label><br>
        <input id="Email" name="Email" type="email" required placeholder="name@gmail.com"
               title="Please enter a valid email like name@gmail.com"/><br>

        <label for="password">Enter your Password* :</label><br>
        <input id="password" name="password" type="password" required pattern=".{8,}" 
               title="Password must be at least 8 characters"/><br>

        <button type="submit">Sign up</button>
    </form>
    
    <br>
    <p>Do you already have an account? <a href="login.php">Log in</a></p>
</main>
</div>


<footer class="main-footer"> 
    <p>&copy; 2025 Code & Crumble Project. All rights reserved.</p>
    <div class="footer-links">
        <a href="about.php">Our Mission</a> | 
        <a href="contact.php">Contact</a>
    </div>
</footer>

</body>
</html>