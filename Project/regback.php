<?php
// Database connection
$servername = "localhost";
$username = "root"; // Your MySQL username
$password = "root"; // Your MySQL password
$dbname = "currency"; 

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get data from form
$fullname = $_POST['fullname'];
$gender = $_POST['gender'];
$dob = $_POST['date'];
$phone = $_POST['PhoneNo'];
$username = $_POST['Uname'];
$password = password_hash($_POST['Pass'], PASSWORD_BCRYPT); // Securely hash the password

// Insert data into database
$sql = "INSERT INTO signup (fullname, gender, dob, phone, username, password) 
        VALUES ('$fullname', '$gender', '$dob', '$phone', '$username', '$password')";

if ($conn->query($sql) === TRUE) {
    echo "<script>alert('Registration successful!'); window.location.href='signin.html';</script>";
} else {
    echo "<script>alert('Error: " . $conn->error . "'); window.location.href='signup.html';</script>";
}

// Close connection
$conn->close();
?>
