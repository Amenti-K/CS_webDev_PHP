<?php
session_start();
$conn = new mysqli("localhost", "root", "", "mineghm");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_GET['id'])) {
    $room_id = (int)$_GET['id'];

    // Delete room from reserved_rooms first
    $sql = "DELETE FROM reserved_rooms WHERE room_id = $room_id";
    if ($conn->query($sql) === TRUE) {
        // Delete room from rooms table
        $sql = "DELETE FROM rooms WHERE id = $room_id";
        if ($conn->query($sql) === TRUE) {
            header("Location: ../availableRooms.php");
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
} else {
    die("Invalid room ID.");
}
?>
