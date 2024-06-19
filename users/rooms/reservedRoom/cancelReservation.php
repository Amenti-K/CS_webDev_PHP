<?php
session_start();
if (!isset($_SESSION['emailUser'])) {
    header('Location: login.php');
    exit();
}

$userEmail = $_SESSION['emailUser'];
$reservation_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mineghm";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$stmt = $conn->prepare("DELETE FROM reservations WHERE id = ? AND user_email = ?");
$stmt->bind_param("is", $reservation_id, $userEmail);

if ($stmt->execute()) {
    header('Location: reservedRoom.php');
    exit();
} else {
    echo "Error deleting record: " . $conn->error;
}

$stmt->close();
$conn->close();
?>
