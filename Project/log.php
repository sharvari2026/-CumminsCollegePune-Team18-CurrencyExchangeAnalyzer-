<?php
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "currency";

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get values from form
$name = $_POST["name"];
$pass = $_POST["pass"];

// Prepare SQL statement using prepared statements to prevent SQL injection
$stmt = $conn->prepare("SELECT pass FROM signup WHERE username = ?");
$stmt->bind_param("s", $name); // 's' specifies the variable type => 'string'
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Fetch the hashed password from the database
    $row = $result->fetch_assoc();
    $hashedPass = $row['pass'];

    // Verify the password against the hashed password
    if (password_verify($pass, $hashedPass)) {
        echo "<script>alert('Login successful!'); window.location.href='your_form_file.html';</script>";
    } else {
        echo "<script>alert('Login failed: Incorrect password.'); window.location.href='panel.html';</script>";
    }
} else {
    echo "<script>alert('Login failed: User not found.'); window.location.href='signin.html';</script>";
}

// Close the statement and connection
$stmt->close();
$conn->close();
?>
