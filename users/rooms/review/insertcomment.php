<?php
session_start();
$logged = isset($_SESSION['emailUser']);
$userEmail = $logged ? $_SESSION['emailUser'] : null;

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mineghm";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $userName = $_POST['userName'];
    $comment = $_POST['comment'];
    $roomId = 7; // Assuming room_id is passed via POST from the form
    $userEmail = $_SESSION['emailUser']; // Assuming the user's email is stored in the session

    // Validate inputs
    if (!empty($userName) && !empty($comment) && !empty($roomId) && !empty($userEmail)) {
        // Prepare and bind SQL statement
        $stmt = $conn->prepare("INSERT INTO comments (userName, comment, room_id, user_email) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssis", $userName, $comment, $roomId, $userEmail);

        // Execute the statement
        if ($stmt->execute()) {
            echo "Comment added successfully!";
        } else {
            echo "Error: " . $stmt->error;
        }

        $stmt->close();
    } else {
        echo "All fields are required.";
    }
}

$conn->close();
?>
