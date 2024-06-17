<?php
session_start();
$user = $_SESSION['email'];
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mineghm";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT id, name, description, price, image_path1 FROM rooms";
$result = $conn->query($sql);

$rooms = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $rooms[] = $row;
    }
}

$conn->close();

header('Content-Type: application/json');
echo json_encode($rooms);
?>
