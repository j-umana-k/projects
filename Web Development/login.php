<?php 
session_start(); // 1. CRITICAL: Start the session to store user data
require_once "db.php";  

$login_message = "";  

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["Email"], $_POST["password"])) { 

    $email = htmlspecialchars($_POST["Email"]);
    $pass  = htmlspecialchars($_POST["password"]);

    if ($email === "" || $pass === "") { 
        $login_message = "<p class='error-message'>Please fill in all fields</p>"; 
    } else {
        // SQL query to get the user
        $sql = "SELECT id, username, password_hash FROM users WHERE email = '$email'";
        $result = $conn->query($sql);

        if ($result && $result->num_rows === 1) { 
            $row = $result->fetch_assoc();

            if (!password_verify($pass, $row["password_hash"])) {
                $login_message = "<p class='error-message'>Wrong password</p>"; 
            } else {
                // 2. SUCCESS: Save user info into the session
                $_SESSION['user_id']  = $row['id'];
                $_SESSION['username'] = $row['username'];

                // Redirect on successful login
                header("Location: index.php");
                exit;
            }
        } else { 
            $login_message = "<p class='error-message'>Email not found</p>"; 
        }
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Log In</title>
    <link rel="stylesheet" href="style.css">
</head>
<body class="login-page">

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


<h1>Log in</h1>
<div class="login-container">
<main>
    <?php if (!empty($login_message)) echo $login_message; ?>

    <form action="" method="POST"> 
        <label for="Email">Enter your Email* :</label><br>
        <input id="Email" name="Email" type="email" required placeholder="name@gmail.com" 
               title="Please enter a valid email like name@gmail.com"/><br>

        <label for="password">Enter your Password* :</label><br>
        <input id="password" name="password" type="password" pattern=".{8,}" required 
               title="Password must be at least 8 characters"/><br>

        <button type="submit">Log in</button>
    </form>
    
    <br>
    <p>Don't have an account? <a href="signup.php">Sign up</a></p>
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