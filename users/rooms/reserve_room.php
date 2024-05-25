<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
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

    // Sanitize and retrieve form inputs
    $room_id = (int)$_POST['room_id'];
    $check_in_date = $conn->real_escape_string($_POST['check_in_date']);
    $check_out_date = $conn->real_escape_string($_POST['check_out_date']);

    // Calculate number of nights
    $check_in_date_obj = new DateTime($check_in_date);
    $check_out_date_obj = new DateTime($check_out_date);
    $number_of_nights = $check_in_date_obj->diff($check_out_date_obj)->days;

    // User email to connect room reserved to the gust
    $userEmail = $_SESSION['email'];

    // Check if the room is already reserved for the requested dates
    $sql = "SELECT * FROM reserved_rooms 
            WHERE room_id = '$room_id' 
            AND (
                (check_in_date <= '$check_in_date' AND DATE_ADD(check_in_date, INTERVAL number_of_nights DAY) > '$check_in_date') OR
                (check_in_date < '$check_out_date' AND DATE_ADD(check_in_date, INTERVAL number_of_nights DAY) >= '$check_out_date') OR
                ('$check_in_date' <= check_in_date AND '$check_out_date' > check_in_date)
            )";
    
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        
        echo "<script> alert('Room is already reserved for the selected dates.'); window.history.back(); </script>";
    } else {
        // Insert data into the reserved_rooms table
        $sql = "INSERT INTO reserved_rooms (room_id, check_in_date, number_of_nights, user_email)
                VALUES ('$room_id', '$check_in_date', '$number_of_nights', '$userEmail')";

        if ($conn->query($sql) === TRUE) {
            echo "<script> alert('room reserved successfully!'); window.history.back();</script>";
            // header("location: availableRooms.html?message='1'");
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }

    $conn->close();
}
?>
