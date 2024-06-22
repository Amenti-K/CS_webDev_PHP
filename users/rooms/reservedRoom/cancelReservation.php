<?php
session_start();
if (!isset($_SESSION['emailUser'])) {
    header("Location: $base_url/login/user/index.html");
    exit();
}

// base URL dynamically
$base_url = "http://" . $_SERVER['HTTP_HOST'] . '/GHM mine/users';

$userEmail = $_SESSION['emailUser'];
$GETcheck_in_date = isset($_GET['checkInDate']) ? $_GET['checkInDate'] : 0;
// $reservation_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mineghm";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$stmt = $conn->prepare("DELETE FROM reserved_rooms WHERE check_in_date = ? AND user_email = ?");
$stmt->bind_param("ss", $GETcheck_in_date, $userEmail);

if ($stmt->execute()) {
    echo "reservation is canceled";
    header("Location: $base_url/rooms/reservedRoom/userReserved.php");
    exit();
} else {
    echo "Error deleting record: " . $conn->error;
}

$stmt->close();
$conn->close();
?>
