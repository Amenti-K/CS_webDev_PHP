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

$room_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$sql = "SELECT id, name, description, price, image_path1, image_path2, image_path3, location, num_bedrooms, num_beds, num_bathrooms, kitchen, wifi, ac FROM rooms WHERE id = $room_id";
$result = $conn->query($sql);

$room = null;

if ($result->num_rows > 0) {
    $room = $result->fetch_assoc();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Room Details</title>
    <style>
        .room-details, .reservation-form {
            border: 1px solid #ccc;
            padding: 16px;
            margin: 16px 0;
        }
        .room-details img {
            max-width: 100%;
            height: auto;
        }
        .room-details h2, .room-details p {
            margin: 8px 0;
        }
        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgb(0,0,0);
            background-color: rgba(0,0,0,0.4);
            padding-top: 60px;
        }
        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
        }
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }
        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <?php if ($room): ?>
        <div class="room-details">
            <h1><?php echo htmlspecialchars($room['name']); ?></h1>
            <p><?php echo nl2br(htmlspecialchars($room['description'])); ?></p>
            <p>Price: $<?php echo htmlspecialchars($room['price']); ?> per night</p>
            <p>Location: <?php echo htmlspecialchars($room['location']); ?></p>
            <p>Number of Bedrooms: <?php echo htmlspecialchars($room['num_bedrooms']); ?></p>
            <p>Number of Beds: <?php echo htmlspecialchars($room['num_beds']); ?></p>
            <p>Number of Bathrooms: <?php echo htmlspecialchars($room['num_bathrooms']); ?></p>
            <p>Kitchen: <?php echo $room['kitchen'] ? 'Yes' : 'No'; ?></p>
            <p>WiFi: <?php echo $room['wifi'] ? 'Yes' : 'No'; ?></p>
            <p>AC: <?php echo $room['ac'] ? 'Yes' : 'No'; ?></p>
            <?php if ($room['image_path1']): ?>
                <img src="../<?php echo htmlspecialchars($room['image_path1']); ?>" alt="Image 1 of <?php echo htmlspecialchars($room['name']); ?>">
            <?php endif; ?>
            <?php if ($room['image_path2']): ?>
                <img src="../<?php echo htmlspecialchars($room['image_path2']); ?>" alt="Image 2 of <?php echo htmlspecialchars($room['name']); ?>">
            <?php endif; ?>
            <?php if ($room['image_path3']): ?>
                <img src="../<?php echo htmlspecialchars($room['image_path3']); ?>" alt="Image 3 of <?php echo htmlspecialchars($room['name']); ?>">
            <?php endif; ?>
        </div>

        <div class="reservation-form">
            <h2>Reserve this Room</h2>
            <form id="reservationForm" action="reserve_room.php" method="post">
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
    </script>
</body>
</html>
