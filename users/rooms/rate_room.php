<?php
session_start();
if (!isset($_SESSION['emailUser'])) {
    echo "You must be logged in to rate a room.";
    exit;
}

$userEmail = $_SESSION['emailUser'];
$room_id = isset($_POST['room_id']) ? (int)$_POST['room_id'] : 0;
$rating = isset($_POST['rating']) ? (int)$_POST['rating'] : 0;

if ($room_id == 0 || $rating == 0) {
    echo "Invalid room or rating.";
    exit;
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mineghm";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the user has already rated this room
$sql = "SELECT id, rating FROM ratings WHERE room_id = $room_id AND user_email = '$userEmail'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Update the existing rating
    $row = $result->fetch_assoc();
    $existing_rating_id = $row['id'];
    $sql = "UPDATE ratings SET rating = $rating WHERE id = $existing_rating_id";
} else {
    // Insert a new rating
    $sql = "INSERT INTO ratings (room_id, user_email, rating) VALUES ($room_id, '$userEmail', $rating)";
}

if ($conn->query($sql) === TRUE) {
    // Recalculate the average rating
    $sql = "SELECT AVG(rating) AS average_rating FROM ratings WHERE room_id = $room_id";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $average_rating = round($row['average_rating'], 2);

        // Update the average rating in the rooms table
        $sql = "UPDATE rooms SET average_rating = $average_rating WHERE id = $room_id";
        if ($conn->query($sql) === TRUE) {
            echo "Rating updated successfully";
        } else {
            echo "Error updating average rating: " . $conn->error;
        }
    }
} else {
    echo "Error updating rating: " . $conn->error;
}

$conn->close();
?>
