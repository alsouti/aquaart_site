<?php
// Start the session
session_start();

// Database credentials
$servername = "localhost";
$username = "root"; // change if needed
$password = "";     // change if you set a password
$dbname = "aquaart_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $uname = $_POST['uname'];
    $psw = $_POST['psw'];

    // Validate password strength
    $hasSpecialChar = preg_match('/[^a-zA-Z0-9]/', $psw);
    if (strlen($psw) >= 8 && $hasSpecialChar) {
        // Hash the password for security
        $hashedPassword = password_hash($psw, PASSWORD_DEFAULT);

        // Insert into database
        $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
        $stmt->bind_param("ss", $uname, $hashedPassword);

        if ($stmt->execute()) {
            echo "<script>alert('Successful log-in'); window.location.href='welcome.html';</script>";
        } else {
            echo "<script>alert('Failed to log-in'); window.history.back();</script>";
        }
        $stmt->close();
    } else {
        echo "<script>alert('Apply secure password'); window.history.back();</script>";
    }
}

$conn->close();
?>
