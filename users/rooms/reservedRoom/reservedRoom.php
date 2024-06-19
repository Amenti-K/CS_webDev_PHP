<?php
session_start();
if (!isset($_SESSION['emailUser'])) {
    header('Location: login.php');
    exit();
}

$userEmail = $_SESSION['emailUser'];

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mineghm";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT reserved_rooms.id, rooms.name, rooms.location, reserved_rooms.check_in_date, reserved_rooms.number_of_nights, rooms.price
        FROM reserved_rooms
        JOIN rooms ON reserved_rooms.room_id = rooms.id
        WHERE reserved_rooms.user_email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $userEmail);
$stmt->execute();
$result = $stmt->get_result();

$reservations = [];
while ($row = $result->fetch_assoc()) {
    $checkInDate = new DateTime($row['check_in_date']);
    // $checkOutDate = new DateTime($row['check_out_date']);
    $numberOfNights = $row['number_of_nights'];
    $totalPrice = $numberOfNights * $row['price'];

    $reservations[] = [
        'id' => $row['id'],
        'name' => $row['name'],
        'location' => $row['location'],
        'checkInDate' => $checkInDate->format('Y-m-d'),
        'numberOfNights' => $numberOfNights,
        'totalPrice' => $totalPrice
    ];
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reserved Rooms</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <?php include '../navBar/navbar.php'; ?>

    <h1>Your Reserved Rooms</h1>

    <?php if (count($reservations) > 0): ?>
        <ul>
            <?php foreach ($reservations as $reservation): ?>
                <li>
                    <p><strong>Room Name:</strong> <?php echo htmlspecialchars($reservation['name']); ?></p>
                    <p><strong>Location:</strong> <?php echo htmlspecialchars($reservation['location']); ?></p>
                    <p><strong>Check-In Date:</strong> <?php echo htmlspecialchars($reservation['checkInDate']); ?></p>
                    <p><strong>Number of Nights:</strong> <?php echo htmlspecialchars($reservation['numberOfNights']); ?></p>
                    <p><strong>Total Price:</strong> $<?php echo htmlspecialchars($reservation['totalPrice']); ?></p>
                    <a href="editReservation.php?id=<?php echo $reservation['id']; ?>">Edit</a>
                    <a href="cancelReservation.php?id=<?php echo $reservation['id']; ?>">Cancel</a>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>No reservations found.</p>
    <?php endif; ?>
</body>
</html>
