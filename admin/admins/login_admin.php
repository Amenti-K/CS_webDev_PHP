<?php
session_start();

// Connect to the database
$conn = new mysqli("localhost", "root", "", "mineghm");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Fetch user details from the database
    $sql = "SELECT * FROM admins WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        // User found
        $user = $result->fetch_assoc();

        // Verify password
        if (password_verify($password, $user['password'])) {
            // Password is correct, start a session
            $_SESSION['email'] = $user['email'];

            // Redirect to a protected page
            // header("Location: logedINpage.php?email=${email}");
            header("Location: logedINpage.php");
            exit;
        } else {
            // Invalid password
            echo "<script>alert('Invalid email or password.'); window.history.back();</script>";
        }
    } else {
        // User not found
        echo "<script>alert('Invalid email or password.'); window.history.back();</script>";
    }

    $conn->close();
}
?>
