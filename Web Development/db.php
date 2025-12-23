<?php
$host = "localhost";
$user = "root";       // default XAMPP username
$pass = "";           // default password = empty
$dbname = "code_crumble";

$conn = mysqli_connect($host, $user, $pass, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
