<?php
session_start();
if (!isset($_SESSION['emailUser'])) {
    header('Location: login.php');
    exit();
}

// base URL dynamically
$base_url = "http://" . $_SERVER['HTTP_HOST'] . '/GHM mine/users';

$userEmail = $_SESSION['emailUser'];
$GETcheck_in_date = isset($_GET['checkInDate']) ? $_GET['checkInDate'] : 0;

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

    $stmt = $conn->prepare("UPDATE reserved_rooms SET check_in_date = ?, check_out_date = ? WHERE check_in_date = ? AND user_email = ?");
    $stmt->bind_param("ssss", $check_in_date, $check_out_date, $GETcheck_in_date, $userEmail);

    if ($stmt->execute()) {
        header("Location: $base_url/rooms/reservedRoom/userReserved.php");
        exit();
    } else {
        echo "Error updating record: " . $conn->error;
    }

    $stmt->close();
} else {
    $sql = "SELECT check_in_date, number_of_nights FROM reserved_rooms WHERE check_in_date = ? AND user_email = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $GETcheck_in_date, $userEmail);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $reservation = $result->fetch_assoc();
    
        // Convert the check-in date string to a DateTime object
        $check_in_date_obj = new DateTime($reservation['check_in_date']);
    
        // Clone the DateTime object
        $check_out_date_obj = clone $check_in_date_obj;
    
        // Add the number of nights to the check-in date
        $number_of_nights = $reservation['number_of_nights'];
        $check_out_date_obj->modify("+$number_of_nights days");
    
        // Format the new date to a string (optional: specify your desired date format)
        $check_out_date = $check_out_date_obj->format('Y-m-d');
    
        // Now you have both the check-in and check-out dates
        $check_in_date = $check_in_date_obj->format('Y-m-d');
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
    <?php include '../navBar/navbar.php'; ?>

    <h1>Edit Reservation</h1>
    <form action="editReservation.php?id=<?php echo $reservation_id; ?>" method="post">
        <label for="check_in_date">Check-In Date</label>
        <input type="date" id="check_in_date" name="check_in_date" value="<?php echo $check_in_date ?>" required><br><br>

        <label for="check_out_date">Check-Out Date</label>
        <input type="date" id="check_out_date" name="check_out_date" value="<?php echo $check_out_date ?>" required><br><br>

        <input type="submit" value="Update Reservation">
    </form>
</body>
</html>
