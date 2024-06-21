<?php
session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mineghm";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// base URL dynamically
$base_url = "http://" . $_SERVER['HTTP_HOST'] . '/GHM mine/users';

// Check if user is logged in
$logged = isset($_SESSION['emailUser']);
$userEmail = $logged ? $_SESSION['emailUser'] : null;

// Fetch favorite rooms for the logged-in user
$query = "
    SELECT rooms.id, rooms.name, rooms.description, rooms.location, rooms.average_rating
    FROM favorites
    JOIN rooms ON favorites.room_id = rooms.id
    WHERE favorites.user_email = ?
";

$stmt = $conn->prepare($query);
$stmt->bind_param('s', $userEmail);
$stmt->execute();
$result = $stmt->get_result();
$favorite_rooms = $result->fetch_all(MYSQLI_ASSOC);

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Favorite Rooms</title>
    <link rel="stylesheet" href="styleFavorites.css">
</head>
<body>
    <div class="nav">
        <?php include "../../navBar/navbar.php"; ?>
    </div>
    <main class="table">
        <section class="table_header">
            <h1>My Favorite Rooms</h1>
        </section>
        <section class="table_body">
            <table>
                <thead>
                    <tr>
                        <th>Room Name</th>
                        <th>Description</th>
                        <th>Location</th>
                        <th>Rating</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (count($favorite_rooms) > 0) {
                        foreach ($favorite_rooms as $room) {
                            $room_id = (int) $room['id'];
                            echo "<tr>
                                    <td><a href='../../roomDetails/room_details.php?id=$room_id'>{$room['name']}</a></td>
                                    <td>{$room['description']}</td>
                                    <td>{$room['location']}</td>
                                    <td>
                                        <div class='stars'>";
                                        for ($i = 1; $i <= 5; $i++) {
                                            $selected = $i <= $room['average_rating'] ? 'selected' : '';
                                            echo "<span class='star $selected'>&#9733;</span>";
                                        }
                            echo        "</div>
                                    </td>
                                  </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='4'>No favorite rooms found</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </section>
    </main>
</body>
</html>
