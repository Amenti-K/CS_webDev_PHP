<?php
session_start();
$logged = isset($_SESSION['emailUser']);
$userEmail = $logged ? $_SESSION['emailUser'] : null;
// base URL dynamically
$base_url = "http://" . $_SERVER['HTTP_HOST'] . '/GHM mine/users';

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

// Check if the room is already a favorite
$isFavorite = false;
if ($logged) {
    $stmt = $conn->prepare("SELECT COUNT(*) FROM favorites WHERE user_email = ? AND room_id = ?");
    $stmt->bind_param("si", $userEmail, $room_id);
    $stmt->execute();
    $stmt->bind_result($favoriteCount);
    $stmt->fetch();
    $isFavorite = $favoriteCount > 0;
    $stmt->close();
}

$stmt = $conn->prepare("SELECT userName, comment, created_at FROM comments WHERE room_id = ?");
$stmt->bind_param("i", $room_id);
$stmt->execute();
$stmt->bind_result($userName, $comment, $created_at);

$comments = [];
while ($stmt->fetch()) {
    $comments[] = ['userName' => $userName, 'comment' => $comment, 'created_at' => $created_at];
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Room Details</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="roomDetails.css">
    <style>
        .favorite-button {
            background: none;
            border: none;
            cursor: pointer;
            font-size: 24px;
            border: 1px solid black;
            color: <?php echo $isFavorite ? 'red' : 'white'; ?>;
        }
    </style>
</head>
<body>
    <div class="nav">
        <?php include '../navBar/navbar.php'; ?>
    </div>
    <?php if ($room): ?>
        <div class="room-details">
            <div class="room-header">
                <h1><?php echo htmlspecialchars($room['name']); ?></h1>
                <!-- Favorite button with heart icon -->
                <button class="favorite-button" id="favoriteButton"><i class="fa fa-bookmark-o" aria-hidden="true"></i></button>
            </div>
            
            <div class="room-images">
                <?php if ($room['image_path1']): ?>
                    <img src="<?php echo htmlspecialchars($room['image_path1']); ?>" alt="Image 1 of <?php echo htmlspecialchars($room['name']); ?>">
                <?php endif; ?>
                <?php if ($room['image_path2'] || $room['image_path3']): ?>
                    <div class="side-by-side-images">
                        <?php if ($room['image_path2']): ?>
                            <img src="<?php echo htmlspecialchars($room['image_path2']); ?>" alt="Image 2 of <?php echo htmlspecialchars($room['name']); ?>">
                        <?php endif; ?>
                        <?php if ($room['image_path3']): ?>
                            <img src="<?php echo htmlspecialchars($room['image_path3']); ?>" alt="Image 3 of <?php echo htmlspecialchars($room['name']); ?>">
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="room-description">
                <div class="name_rating">
                    <p><?php echo nl2br(htmlspecialchars($room['description'])); ?></p>
                    <div class="stars">
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <span class="star <?php echo $i <= $room['average_rating'] ? 'selected' : ''; ?>">&#9733;</span>
                        <?php endfor; ?>
                    </div>
                </div>
            </div>

            <div class="room-amenities">
                <div class="other-amenities">
                    <h2><strong>what this place offers</strong></h2>
                    <ul>
                        <li><?php echo htmlspecialchars($room['num_bedrooms']); ?>   Bedrooms</li>
                        <li><?php echo htmlspecialchars($room['num_beds']); ?>   Beds   </li>
                        <li><?php echo htmlspecialchars($room['num_bathrooms']); ?>   Baths</li>
                        <?php if ($room['kitchen'] > 0 ) { ?>
                            <li><?php echo $room['kitchen'] ? 'Kitchen' : ''; ?></li> 
                        <?php } ?>
                        <?php if ($room['wifi'] > 0 ) { ?>
                            <li><i class="fa fa-wifi" aria-hidden="true"></i><?php echo $room['wifi'] ? 'WiFi' : ''; ?></li>
                        <?php } ?>
                        <?php if ($room['ac'] > 0 ) { ?>
                            <li><?php echo $room['ac'] ? 'Air Conditioning' : ''; ?></li> 
                        <?php } ?>
                    </ul>
                </div>
                <div class="reservation-form">
                    <h2><strong>$ <?php echo htmlspecialchars($room['price']); ?></strong> night </p>
                    <form id="reservationForm" action="../reservation/reserve_room.php" method="post">
                        <input type="hidden" name="room_id" value="<?php echo $room_id; ?>">
                        
                        <label for="check_in_date">Check-In Date:</label>
                        <input type="date" name="check_in_date" id="check_in_date" required><br><br>
                        
                        <label for="check_out_date">Check-Out Date:</label>
                        <input type="date" name="check_out_date" id="check_out_date" required><br><br>
                        
                        <input type="submit" value="Reserve Room">
                    </form>
                </div>
            </div>

            <div class="reviews-section">
                <div class="average-rating">
                    <p><strong>Average Rating </strong></p>
                    <div class="stars">
                        <?php for ($i = 1; $i <= 5; $i++): ?>
                            <span class="star <?php echo $i <= $room['average_rating'] ? 'selected' : ''; ?>">&#9733;</span>
                        <?php endfor; ?>
                    </div>
                </div>
                <div class="comments">
                    <?php foreach ($comments as $comment) : ?>
                        <div class="comment">
                            <div class="profile">
                                <div class="profile-icon"><?php echo strtoupper(substr($comment['userName'], 0, 1)); ?></div>
                                <strong><?php echo htmlspecialchars($comment['userName']); ?></strong>
                            </div>
                            <div class="comment-details">
                                <p class="comment-date">on <?php echo date('F j, Y', strtotime($comment['created_at'])); ?></p>
                                <p class="comment-text"><?php echo htmlspecialchars($comment['comment']); ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
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

        // Favorite button click event
        document.getElementById("favoriteButton").addEventListener("click", function() {
            <?php if ($logged): ?>
                var xhr = new XMLHttpRequest();
                xhr.open("POST", "favorite_room.php", true);
                xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
                xhr.onreadystatechange = function() {
                    if (xhr.readyState == 4 && xhr.status == 200) {
                        var response = JSON.parse(xhr.responseText);
                        if (response.success) {
                            var favoriteButton = document.getElementsByClassName("fa-bookmark-o");
                            favoriteButton.style.color = response.isFavorite ? 'red' : 'white';
                        } else {
                            alert('An error occurred. Please try again.');
                        }
                    }
                };
                xhr.send("room_id=" + <?php echo $room_id; ?>);
            <?php else: ?>
                alert('Join our community.');
                window.location.href = '<?php echo $base_url ?>/user/signup.html';
            <?php endif; ?>
        });
    </script>

    <?php else: ?>
        <p>Room not found.</p>
    <?php endif; ?>
</body>
</html>
