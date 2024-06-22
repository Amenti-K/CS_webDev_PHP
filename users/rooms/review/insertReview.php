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

$status = false;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $userName = $_POST['userName'];
    $comment = $_POST['comment'];
    $roomId = $_POST['room_id']; // Assuming room_id is passed via POST from the form
    $rating = $_POST['rating']; // Get the rating value from the form
    $userEmail = $_SESSION['emailUser']; // Assuming the user's email is stored in the session

    // Validate inputs
    if (!empty($userName) && !empty($comment) && !empty($roomId) && !empty($userEmail) && !empty($rating)) {
        // Prepare and bind SQL statement for comments
        $stmt = $conn->prepare("INSERT INTO comments (userName, comment, room_id, user_email) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssis", $userName, $comment, $roomId, $userEmail);

        // Execute the statement
        if ($stmt->execute()) {
            $status = true;
        } else {
            $status = false;
            echo "Error: " . $stmt->error;
        }

        $stmt->close();

        // Check if the user has already rated the room
        $stmt = $conn->prepare("SELECT COUNT(*) FROM ratings WHERE room_id = ? AND user_email = ?");
        $stmt->bind_param("is", $roomId, $userEmail);
        $stmt->execute();
        $stmt->bind_result($ratingCount);
        $stmt->fetch();
        $stmt->close();

        if ($ratingCount > 0) {
            // User has already rated this room, update the existing rating
            $stmt = $conn->prepare("UPDATE ratings SET rating = ?, created_at = NOW() WHERE room_id = ? AND user_email = ?");
            $stmt->bind_param("iis", $rating, $roomId, $userEmail);
        } else {
            // User has not rated this room, insert a new rating
            $stmt = $conn->prepare("INSERT INTO ratings (room_id, user_email, rating, created_at) VALUES (?, ?, ?, NOW())");
            $stmt->bind_param("isi", $roomId, $userEmail, $rating);
        }

        // Execute the rating statement
        if ($stmt->execute()) {
            $status = true;
        } else {
            $status = false;
            echo "Error: " . $stmt->error;
        }

        $stmt->close();

        // Calculate the new average rating
        $stmt = $conn->prepare("SELECT AVG(rating) AS average_rating FROM ratings WHERE room_id = ?");
        $stmt->bind_param("i", $roomId);
        $stmt->execute();
        $stmt->bind_result($averageRating);
        $stmt->fetch();
        $stmt->close();

        // Update the room's average rating
        $stmt = $conn->prepare("UPDATE rooms SET average_rating = ? WHERE id = ?");
        $stmt->bind_param("di", $averageRating, $roomId);
        if ($stmt->execute()) {
            $status = true;
        } else {
            $status = false;
            echo "Error updating average rating: " . $stmt->error;
        }

        $stmt->close();
    } else {
        $status = false;
        echo "All fields are required.";
    }
}

if ($status) {
    $pre_page = $_SERVER['HTTP_REFERER'];
    header("Location: $pre_page");
}

$conn->close();
?>
