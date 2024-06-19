<?php
session_start();
$logged = isset($_SESSION['emailUser']);
$userEmail = $logged ? $_SESSION['emailUser'] : null;

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mineghm";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$room_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$sql = "SELECT id, name, description, price, image_path1, image_path2, image_path3, location, num_bedrooms, num_beds, num_bathrooms, kitchen, wifi, ac, average_rating FROM rooms WHERE id = $room_id";
$result = $conn->query($sql);

$room = null;
if ($result->num_rows > 0) {
    $room = $result->fetch_assoc();
}


$stmt = $conn->prepare("SELECT userName, comment FROM comments WHERE room_id = ?");
$stmt->bind_param("i", $room_id);
$stmt->execute();
$stmt->bind_result($userName, $comment);

$comments = [];
while ($stmt->fetch()) {
    $comments[] = ['userName' => $userName, 'comment' => $comment];
}

$stmt->close();

$conn->close();
// this was for rating purpose; we will change it ot favoriting
// Get the current rating of the room by the logged-in user
// $current_user_rating = 0;
// if ($logged) {
//     $sql = "SELECT rating FROM ratings WHERE room_id = $room_id AND user_email = '$userEmail'";
//     $result = $conn->query($sql);
//     if ($result->num_rows > 0) {
//         $row = $result->fetch_assoc();
//         $current_user_rating = $row['rating'];
//     }
// }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Room Details</title>
    <link rel="stylesheet" href="roomDetails.css">
</head>
<body>
    <?php include '../navBar/navbar.php'; ?>
    <?php if ($room): ?>
        <div class="room-details">
            <h1><?php echo htmlspecialchars($room['name']); ?></h1>
            <?php if ($room['image_path1']): ?>
                <img src="<?php echo htmlspecialchars($room['image_path1']); ?>" alt="Image 1 of <?php echo htmlspecialchars($room['name']); ?>">
            <?php endif; ?>
            <?php if ($room['image_path2']): ?>
                <img src="<?php echo htmlspecialchars($room['image_path2']); ?>" alt="Image 2 of <?php echo htmlspecialchars($room['name']); ?>">
            <?php endif; ?>
            <?php if ($room['image_path3']): ?>
                <img src="<?php echo htmlspecialchars($room['image_path3']); ?>" alt="Image 3 of <?php echo htmlspecialchars($room['name']); ?>">
            <?php endif; ?>
            <p><?php echo nl2br(htmlspecialchars($room['description'])); ?></p>
            <p>Price: $<?php echo htmlspecialchars($room['price']); ?> per night</p>
            <p>Location: <?php echo htmlspecialchars($room['location']); ?></p>
            <p>Number of Bedrooms: <?php echo htmlspecialchars($room['num_bedrooms']); ?></p>
            <p>Number of Beds: <?php echo htmlspecialchars($room['num_beds']); ?></p>
            <p>Number of Bathrooms: <?php echo htmlspecialchars($room['num_bathrooms']); ?></p>
            <p>Kitchen: <?php echo $room['kitchen'] ? 'Yes' : 'No'; ?></p>
            <p>WiFi: <?php echo $room['wifi'] ? 'Yes' : 'No'; ?></p>
            <p>AC: <?php echo $room['ac'] ? 'Yes' : 'No'; ?></p>
            <!-- <p>Average Rating: <?php echo htmlspecialchars($room['average_rating']); ?></p> -->
            <div class="stars">
                <?php for ($i = 1; $i <= 5; $i++): ?>
                    <span class="star <?php echo $i <= $room['average_rating'] ? 'selected' : ''; ?>">&#9733;</span>
                <?php endfor; ?>
            </div>
        </div>

        <div class="reservation-form">
            <h2>Reserve this Room</h2>
            <form id="reservationForm" action="../reservation/reserve_room.php" method="post">
                <input type="hidden" name="room_id" value="<?php echo $room_id; ?>">
                
                <label for="check_in_date">Check-In Date:</label>
                <input type="date" name="check_in_date" id="check_in_date" required><br><br>
                
                <label for="check_out_date">Check-Out Date:</label>
                <input type="date" name="check_out_date" id="check_out_date" required><br><br>
                
                <input type="submit" value="Reserve Room">
            </form>
        </div>

        <!-- Modal for reservation confirmation -->
        <div id="confirmationModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <h2>Reservation Confirmation</h2>
                <p id="reservationDetails"></p>
                <button onclick="confirmReservation()">Confirm Reservation</button>
            </div>
        </div>

        <!-- comment section -->
        <div class="comments-section">
            <?php foreach ($comments as $comment) : ?>
                <div class="comment">
                    <strong><?php echo htmlspecialchars($comment['userName']); ?></strong>
                    <p><?php echo htmlspecialchars($comment['comment']); ?></p>
                </div>
            <?php endforeach; ?>
        </div>

    <?php else: ?>
        <p>Room not found.</p>
    <?php endif; ?>

    <script>
        // Get the modal
        var modal = document.getElementById("confirmationModal");

        // Get the <span> element that closes the modal
        var span = document.getElementsByClassName("close")[0];

        // When the user clicks on <span> (x), close the modal
        span.onclick = function() {
            modal.style.display = "none";
        }

        // When the user clicks anywhere outside of the modal, close it
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }

        document.getElementById("reservationForm").onsubmit = function(event) {
            event.preventDefault();

            var check_in_date = document.getElementById("check_in_date").value;
            var check_out_date = document.getElementById("check_out_date").value;
            var price_per_night = <?php echo $room['price']; ?>;
            
            var check_in_date_obj = new Date(check_in_date);
            var check_out_date_obj = new Date(check_out_date);
            var time_difference = check_out_date_obj.getTime() - check_in_date_obj.getTime();
            var number_of_nights = time_difference / (1000 * 3600 * 24);

            if (number_of_nights > 0) {
                var total_price = number_of_nights * price_per_night;
                
                var reservationDetails = `
                    Check-In Date: ${check_in_date}<br>
                    Number of Nights: ${number_of_nights}<br>
                    Total Price: $${total_price.toFixed(2)}
                `;

                document.getElementById("reservationDetails").innerHTML = reservationDetails;
                modal.style.display = "block";
            } else {
                alert("Check-out date must be later than check-in date.");
            }
        };

        function confirmReservation() {
            document.getElementById("reservationForm").submit();
        }

        document.querySelectorAll('.star').forEach(function(star) {
            star.addEventListener('click', function() {
                var rating = this.getAttribute('data-value');
                <?php if ($logged): ?>
                    var room_id = <?php echo $room_id; ?>;
                    var xhr = new XMLHttpRequest();
                    xhr.open("POST", "rate_room.php", true);
                    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                    xhr.onreadystatechange = function() {
                        if (xhr.readyState == 4 && xhr.status == 200) {
                            // Handle response
                            document.querySelectorAll('.star').forEach(function(star) {
                                star.classList.remove('selected');
                            });
                            for (var i = 1; i <= rating; i++) {
                                document.querySelector('.star[data-value="' + i + '"]').classList.add('selected');
                            }
                            alert('Rating submitted successfully');
                        }
                    };
                    xhr.send("room_id=" + room_id + "&rating=" + rating);
                <?php else: ?>
                    alert('You must be logged in to rate a room.');
                    window.location.href = 'signup.php';
                <?php endif; ?>
            });
        });
    </script>
</body>
</html>
