<?php 
require_once "db.php";  // Connect to the database 

// Check if the form was submitted using POST 
if ($_SERVER["REQUEST_METHOD"] === "POST") { 

    // 1) Get the form values 
    $name    = htmlspecialchars($_POST["name"]); 
    $email   = htmlspecialchars($_POST["email"]); 
    $subject = htmlspecialchars($_POST["subject"]); 
    $message = htmlspecialchars($_POST["message"]); 

    // 2) Simple validation: check if any field is empty 
    if ($name === "" || $email === "" || $subject === "" || $message === "") { 
        echo "<h2 style='color:red;'> Please fill in all fields.</h2>"; 
        echo "<p><a href='contact.php'>Go back</a></p>";
        exit; 
    } 

    // 3) Check if the email exists in the users table
    $user_id = NULL; // Default NULL for guest
    $sql_user = "SELECT id FROM users WHERE email = '$email'";
    $result_user = $conn->query($sql_user);
    if ($result_user && $result_user->num_rows > 0) {
        $row = $result_user->fetch_assoc();
        $user_id = $row['id']; // Link the message to the user
    }

    // 4) Determine user_id value for insertion without using ternary
    if ($user_id !== NULL) {
        $user_id_value = $user_id;
    } else {
        $user_id_value = "NULL";
    }

    // 5) SQL query to insert the data into the database 
    $sql = "INSERT INTO contact_messages (user_id, name, email, subject, message) 
            VALUES ($user_id_value, '$name', '$email', '$subject', '$message')"; 

    // 6) Run the SQL query 
    if ($conn->query($sql) === TRUE) { 
        // Success: show alert + stay on the page
        echo "<script>
                alert('Your message has been sent successfully! 🍪');
                window.location.href = 'contact.php';
              </script>";
        exit;

    } else { 
        // Error: show message without alert
        echo "<h2 style='color:red;'>Error saving your message. Please try again.</h2>"; 
        echo "<p><a href='contact.php'>Go back</a></p>";
        exit;
    } 

    $conn->close(); 
} 
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Contact Us</title>
<link rel="stylesheet" href="style.css">
</head>
<body class="contact-page">
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


<!-- Contact Form Container -->
<form action="" method="POST" class="contact-container">
    <h1>📩 Contact Us</h1>

    <!-- Intro Text -->
    <div class="contact-text">
        <p>Have a question, feedback, or an idea to sprinkle in (☆ω☆)?</p>
        <p>We’d love to hear from you (o≧▽≦)</p>
        <p>Your feedback helps us keep cooking up something better 🍪</p>
        <p class="p-email"><b>Our Email :</b> code.crumble@example.com</p>
    </div>

    <!-- Form Fields -->
    <label>Your Name</label>
    <input type="text" name="name" placeholder="Enter your full name" required pattern="^[a-zA-Z0-9]+$">

    <label>Your Email</label>
    <input type="email" name="email" placeholder="name@gmail.com" required>

    <label>Subject</label>
    <input type="text" name="subject" placeholder="What is this about?" required>

    <label>Your Message</label>
    <textarea name="message" placeholder="Write your message here." required></textarea>

    <!-- Submit Button -->
    <button type="submit">Send Message</button>
</form>

<footer class="main-footer"> 
    <p>&copy; 2025 Code & Crumble Project. All rights reserved.</p>
    <div class="footer-links">
        <a href="about.php">Our Mission</a> | 
        <a href="contact.php">Contact</a>
    </div>
</footer>    

</body>
</html>

