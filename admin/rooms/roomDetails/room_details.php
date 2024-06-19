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

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Room Details</title>
    <link rel="stylesheet" href="roomDetails.css">
    <script>
        function showEditPopup() {
            document.getElementById("edit-popup").style.display = "block";
        }

        function hideEditPopup() {
            document.getElementById("edit-popup").style.display = "none";
        }

        function showRemovePopup() {
            document.getElementById("remove-popup").style.display = "block";
        }

        function hideRemovePopup() {
            document.getElementById("remove-popup").style.display = "none";
        }

        function confirmRemove() {
            window.location.href = './remove_rooms/remove_room.php?id=<?php echo $room_id; ?>';
        }
    </script>
</head>
<body>
    <?php include '../navBar/navbar.php'; ?>
    <?php if ($room): ?>
        <div class="room-details">
            <div class="room-header">
                <h1><?php echo htmlspecialchars($room['name']); ?></h1>
                            <!-- Edit and Remove Buttons -->
                <button onclick="showEditPopup()">Edit Room</button>
            <button onclick="showRemovePopup()">Remove Room</button>

            <!-- Edit Room Popup -->
            <div id="edit-popup" class="popup">
                <div class="popup-header">
                    <h2>Edit Room</h2>
                    <span class="close" onclick="hideEditPopup()">&times;</span>
                </div>
                <form action="./edit_rooms/edit_room.php" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="id" value="<?php echo $room_id; ?>">
                    <div class="user-details">
                        <div class="input-box">
                            <label for="name">Name:</label>
                            <input type="text" id="name" name="name" value="<?php echo $room['name']; ?>" required>
                        </div>
                        <div class="beds input-box">
                            <label for="beds">Beds:</label>
                            <input type="number" id="beds" name="beds" value="<?php echo $room['num_beds']; ?>" required>
                        </div>
                        <div class="description input-box">
                            <label for="description">Description:</label>
                            <textarea id="description" name="description" required><?php echo $room['description']; ?></textarea>
                        </div>
                        <div class="bathrooms input-box">
                            <label for="bathrooms">Bathrooms:</label>
                            <input type="number" id="bathrooms" name="bathrooms" value="<?php echo $room['num_bathrooms']; ?>" required>
                        </div>
                        <div class="price input-box">
                            <label for="price">Price:</label>
                            <input type="number" id="price" name="price" value="<?php echo $room['price']; ?>" required>
                        </div>
                        <div class="image_path1 input-box">
                            <label for="image_path1">Image 1:</label>
                            <input type="file" id="image_path1" name="image_path1">
                        </div>
                        <div class="location input-box">
                            <label for="location">Location:</label>
                            <input type="text" id="location" name="location" value="<?php echo $room['location']; ?>" required>
                        </div>
                        <div class="image_path2 input-box">
                            <label for="image_path2">Image 2:</label>
                            <input type="file" id="image_path2" name="image_path2">
                        </div>
                        <div class="bedrooms input-box">
                            <label for="bedrooms">Bedrooms:</label>
                            <input type="number" id="bedrooms" name="bedrooms" value="<?php echo $room['num_bedrooms']; ?>" required>
                        </div>
                        <div class="image_path3 input-box">
                            <label for="image_path3">Image 3:</label>
                            <input type="file" id="image_path3" name="image_path3">
                        </div>
                    </div>  
                    <div class="amenities">
                        <div class="kitchen input-box">
                            <label for="kitchen">Kitchen:</label>
                            <input type="checkbox" id="kitchen" name="kitchen" <?php echo $room['kitchen'] ? 'checked' : ''; ?>>
                        </div>
                        
                        <div class="wifi input-box">
                            <label for="wifi">WiFi:</label>
                            <input type="checkbox" id="wifi" name="wifi" <?php echo $room['wifi'] ? 'checked' : ''; ?>>
                        </div>
                        
                        <div class="ac input-box">
                            <label for="ac">AC:</label>
                            <input type="checkbox" id="ac" name="ac" <?php echo $room['ac'] ? 'checked' : ''; ?>>
                        </div>
                    </div>
                    <div class="button">
                        <button type="submit">Save Changes</button>
                    </div>
                </form>
            </div>

            <!-- Remove Room Popup -->
            <div id="remove-popup" class="popup">
                <h2>Confirm Remove Room</h2>
                <p>Are you sure you want to remove this room?</p>
                <div class="remove-buttons">
                    <button onclick="confirmRemove()">Yes</button>
                    <button onclick="hideRemovePopup()">No</button>
                </div>
            </div>
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
                <p><?php echo nl2br(htmlspecialchars($room['description'])); ?></p>
                <p><strong>Basic Amenities:</strong></p>
                <ul>
                    <li>Number of Bathrooms: <?php echo htmlspecialchars($room['num_bathrooms']); ?></li>
                    <li>Number of Beds: <?php echo htmlspecialchars($room['num_beds']); ?></li>
                    <li>Number of Bedrooms: <?php echo htmlspecialchars($room['num_bedrooms']); ?></li>
                </ul>
                <div class="stars">
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                        <span class="star <?php echo $i <= $room['average_rating'] ? 'selected' : ''; ?>">&#9733;</span>
                    <?php endfor; ?>
                </div>
            </div>

            <div class="room-amenities">
                <div class="other-amenities">
                    <p><strong>Other Amenities:</strong></p>
                    <ul>
                        <li><?php echo $room['kitchen'] ? 'Kitchen' : ''; ?></li>
                        <li><?php echo $room['wifi'] ? 'WiFi' : ''; ?></li>
                        <li><?php echo $room['ac'] ? 'Air Conditioning' : ''; ?></li>
                    </ul>
                </div>
            </div>
        </div>

    <?php else: ?>
        <p>Room not found.</p>
    <?php endif; ?>
</body>
</html>

