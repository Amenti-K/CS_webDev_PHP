<?php
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

// Get search parameters
$location = isset($_GET['location']) ? $_GET['location'] : '';
$check_in_date = isset($_GET['check_in_date']) ? $_GET['check_in_date'] : '';
$check_out_date = isset($_GET['check_out_date']) ? $_GET['check_out_date'] : '';

// Initialize SQL query
$sql = "SELECT r.id, r.name, r.description, r.price, r.image_path1, r.average_rating 
        FROM rooms r
        LEFT JOIN reserved_rooms rr ON r.id = rr.room_id
        WHERE 1=1";

// Add location filter if provided
if (!empty($location)) {
    $sql .= " AND r.location LIKE ?";
}

// Add date filters if provided
if (!empty($check_in_date) && !empty($check_out_date)) {
    $datetime1 = new DateTime($check_in_date);
    $datetime2 = new DateTime($check_out_date);
    $interval = $datetime1->diff($datetime2);
    $number_of_nights = $interval->days;

    $sql .= " AND (rr.check_in_date IS NULL OR (rr.check_in_date NOT BETWEEN ? AND ?) 
            OR DATE_ADD(rr.check_in_date, INTERVAL rr.number_of_nights DAY) NOT BETWEEN ? AND ?)";
}

// Prepare statement
$stmt = $conn->prepare($sql);

// Bind parameters
if (!empty($location)) {
    $location_param = '%' . $location . '%';
    if (!empty($check_in_date) && !empty($check_out_date)) {
        $stmt->bind_param('sssss', $location_param, $check_in_date, $check_out_date, $check_in_date, $check_out_date);
    } else {
        $stmt->bind_param('s', $location_param);
    }
} else {
    if (!empty($check_in_date) && !empty($check_out_date)) {
        $stmt->bind_param('ssss', $check_in_date, $check_out_date, $check_in_date, $check_out_date);
    }
}

$stmt->execute();
$result = $stmt->get_result();

$rooms = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $rooms[] = $row;
    }
}

echo json_encode($rooms);

$stmt->close();
$conn->close();
?>
