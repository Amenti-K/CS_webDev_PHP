<?php
session_start();
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
$userEmail = $_SESSION['email'];

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
        <div class="editing-form">
            <form id="editForm" action="./edit_rooms/edit_room.php" method="post">
                <!-- <a href="edit_rooms/edit_room.php"><button type="submit">edit room</button></a> -->
                <input type="submit" value="edit">
            </form>
            <form id="removeForm" action="./remove_rooms/remove_room.php" method="post">
                <!-- <a href="remove_rooms/remove_room.php"><button type="submit">remove room</button></a> -->
                <input type="submit" value="remove">
            </form>
        </div>
        <div class="room-details">
            <h1><?php echo htmlspecialchars($room['name']) , ",  " , htmlspecialchars($room['location']); ?></h1>
            <p><?php echo nl2br(htmlspecialchars($room['description'])); ?></p>
            <p>Price: $<?php echo htmlspecialchars($room['price']); ?> / night</p>
            <!-- <p>Location: <?php echo htmlspecialchars($room['location']); ?></p> -->
            <p>Number of Bedrooms: <?php echo htmlspecialchars($room['num_bedrooms']); ?></p>
            <p>Number of Beds: <?php echo htmlspecialchars($room['num_beds']); ?></p>
            <p>Number of Bathrooms: <?php echo htmlspecialchars($room['num_bathrooms']); ?></p>
            <p>Kitchen: <?php echo $room['kitchen'] ? 'Yes' : 'No'; ?></p>
            <p>WiFi: <?php echo $room['wifi'] ? 'Yes' : 'No'; ?></p>
            <p>AC: <?php echo $room['ac'] ? 'Yes' : 'No'; ?></p>
            <?php if ($room['image_path1']): ?>
                <img src="../../<?php echo htmlspecialchars($room['image_path1']); ?>" alt="Image 1 of <?php echo htmlspecialchars($room['name']); ?>">
            <?php endif; ?>
            <?php if ($room['image_path2']): ?>
                <img src="../../<?php echo htmlspecialchars($room['image_path2']); ?>" alt="Image 2 of <?php echo htmlspecialchars($room['name']); ?>">
            <?php endif; ?>
            <?php if ($room['image_path3']): ?>
                <img src="../../<?php echo htmlspecialchars($room['image_path3']); ?>" alt="Image 3 of <?php echo htmlspecialchars($room['name']); ?>">
            <?php endif; ?>
        </div>
       

        <!-- Modal for reservation confirmation -->
        <div id="confirmationModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <h2 id="target"></h2>
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

        document.getElementById("editForm").onsubmit = function(event) {
            event.preventDefault();
            var editing_form = document.getElementById("conformationModal");
            editing_form.style.display = "block";
            
        }
        document.getElementById("removeForm").onsubmit = function(event) {
            event.preventDefault();
            console.log(event);
        }
    </script>

</body>
</html>

<!-- 
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


    "<form action='upload.php' method='post' enctype='multipart/form-data'>
      <label for='name'>Room Name:</label>
      <input type='text' name='name' id='name' required /><br /><br />
      <label for='description'>Description:</label>
      <textarea name='description' id='description' required></textarea
      ><br /><br />

      <label for='price'>Price:</label>
      <input
        type='number'
        name='price'
        id='price'
        step='0.01'
        required
      /><br /><br />

      <label for='image1'>Image 1:</label>
      <input
        type='file'
        name='image1'
        id='image1'
        accept='image/*'
        required
      /><br /><br />

      <label for='image2'>Image 2:</label>
      <input
        type='file'
        name='image2'
        id='image2'
        accept='image/*'
        required
      /><br /><br />

      <label for='image3'>Image 3:</label>
      <input
        type='file'
        name='image3'
        id='image3'
        accept='image/*'
        required
      /><br /><br />

      <label for='location'>Location:</label>
      <input type='text' name='location' id='location' required /><br /><br />

      <label for='num_bedrooms'>Number of Bedrooms:</label>
      <input
        type='number'
        name='num_bedrooms'
        id='num_bedrooms'
        required
      /><br /><br />

      <label for='num_beds'>Number of Beds:</label>
      <input type='number' name='num_beds' id='num_beds' required /><br /><br />

      <label for='num_bathrooms'>Number of Bathrooms:</label>
      <input
        type='number'
        name='num_bathrooms'
        id='num_bathrooms'
        required
      /><br /><br />

      <label for='kitchen'>Kitchen:</label>
      <input type='checkbox' name='kitchen' id='kitchen' /><br /><br />

      <label for='wifi'>WiFi:</label>
      <input type="checkbox" name="wifi" id="wifi" /><br /><br />

      <label for='ac'>AC:</label>
      <input type='checkbox' name='ac' id='ac' /><br /><br />

      <input type='submit' value='Add Room' />
    </form>"

-->

