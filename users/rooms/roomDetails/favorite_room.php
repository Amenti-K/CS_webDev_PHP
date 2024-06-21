<?php
session_start();
$logged = isset($_SESSION['emailUser']);

if ($logged) {
    $userEmail = $_SESSION['emailUser'];
    $room_id = isset($_POST['room_id']) ? (int)$_POST['room_id'] : 0;

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "mineghm";

    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("SELECT COUNT(*) FROM favorites WHERE user_email = ? AND room_id = ?");
    $stmt->bind_param("si", $userEmail, $room_id);
    $stmt->execute();
    $stmt->bind_result($favoriteCount);
    $stmt->fetch();
    $stmt->close();

    if ($favoriteCount > 0) {
        $stmt = $conn->prepare("DELETE FROM favorites WHERE user_email = ? AND room_id = ?");
        $stmt->bind_param("si", $userEmail, $room_id);
        $stmt->execute();
        $stmt->close();
        $isFavorite = false;
    } else {
        $stmt = $conn->prepare("INSERT INTO favorites (user_email, room_id) VALUES (?, ?)");
        $stmt->bind_param("si", $userEmail, $room_id);
        $stmt->execute();
        $stmt->close();
        $isFavorite = true;
    }

    $conn->close();

    echo json_encode(['success' => true, 'isFavorite' => $isFavorite]);
} else {
    echo json_encode(['success' => false]);
}
?>
