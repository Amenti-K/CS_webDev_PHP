<?php
session_start();
if (!isset($_SESSION['emailUser'])) {
    header('Location: login.php');
    exit();
}

$userEmail = $_SESSION['emailUser'];
$room_id = isset($_GET['id']) ? (int)$_GET['id'][0] : 0;
echo $userEmail, $room_id,

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mineghm";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $check_in_date = $_POST['check_in_date'];
    $check_out_date = $_POST['check_out_date'];

    $stmt = $conn->prepare("UPDATE reserved_rooms SET check_in_date = ?, check_out_date = ? WHERE id = ? AND user_email = ?");
    $stmt->bind_param("ssis", $check_in_date, $check_out_date, $room_id, $userEmail);

    if ($stmt->execute()) {
        header('Location: reservedRoom.php');
        exit();
    } else {
        echo "Error updating record: " . $conn->error;
    }

    $stmt->close();
} else {
    $sql = "SELECT check_in_date, number_of_nights FROM reserved_rooms WHERE room_id = ? AND user_email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $room_id, $userEmail);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $reservation = $result->fetch_assoc();
    } else {
        echo "Reservation not found.";
        exit();
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Reservation</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php include 'navbar.php'; ?>

    <h1>Edit Reservation</h1>
    <form action="editReservation.php?id=<?php echo $reservation_id; ?>" method="post">
        <label for="check_in_date">Check-In Date:</label>
        <input type="date" id="check_in_date" name="check_in_date" value="<?php echo htmlspecialchars($reservation['check_in_date']); ?>" required><br><br>

        <label for="check_out_date">Check-Out Date:</label>
        <input type="date" id="check_out_date" name="check_out_date" value="<?php echo htmlspecialchars($reservation['check_out_date']); ?>" required><br><br>

        <input type="submit" value="Update Reservation">
    </form>
</body>
</html>
