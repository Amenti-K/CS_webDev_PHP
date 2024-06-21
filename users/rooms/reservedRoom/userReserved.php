<?php
// Start session and establish database connection
session_start();
$conn = new mysqli("localhost", "root", "", "mineghm");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get logged-in user's email
$userEmail = $_SESSION['emailUser'];

// Fetch reserved rooms for the logged-in user
$sql = "SELECT r.id, r.name, r.description, r.price, rr.check_in_date, rr.number_of_nights 
        FROM reserved_rooms rr
        JOIN rooms r ON rr.room_id = r.id
        WHERE rr.user_email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $userEmail);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reserved Rooms</title>
    <link rel="stylesheet" href="./styleReservedRooms.css">
</head>
<body>
    <div class="nav">
        <?php include '../navBar/navbar.php'; ?>
    </div>
    <main class="table">
        <section class="table_header">
            <h1>My Reserved Rooms</h1>
        </section>
        <section class="table_body">
            <table>
                <thead>
                    <tr>
                        <th>Room Name</th>
                        <th>Description</th>
                        <th>Check in date</th>
                        <th>nights</th>
                        <th>Price</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            $id = (int) $row['id'];
                            echo "<tr>
                                    <td>{$row['name']}</td>
                                    <td>{$row['description']}</td>
                                    <td>{$row['check_in_date']}</td>
                                    <td>{$row['number_of_nights']}</td>
                                    <td>{$row['price']}</td>
                                    <td><a href='editReservation.php?id=$id' class='edit-link'>&#9998; |</a></td>
                                    <td><a href='cancelReservation.php?id=$id' class='cancel-link'>&times;</a></td>
                                  </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='4'>No reserved rooms found</td></tr>";
                    }
                    ?>
                    
                </tbody>
            </table>
        </section>
    </main>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
