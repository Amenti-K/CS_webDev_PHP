<?php
session_start();
$conn = new mysqli("localhost", "root", "", "mineghm");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$room_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($room_id === 0) {
    die("Invalid room ID.");
}

$sql = "SELECT * FROM rooms WHERE id = $room_id";
$result = $conn->query($sql);

if ($result->num_rows === 1) {
    $room = $result->fetch_assoc();
} else {
    die("Room not found.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Room Details</title>
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
    <style>
        .popup {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: white;
            padding: 20px;
            border: 1px solid black;
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
    <h1>Room Details</h1>
    <button onclick="showEditPopup()">Edit Room</button>
    <button onclick="showRemovePopup()">Remove Room</button>
    
    <h2><?php echo $room['name']; ?></h2>
    <p><?php echo $room['description']; ?></p>
    <p>Price: $<?php echo $room['price']; ?></p>
    <p>Location: <?php echo $room['location']; ?></p>
    <p>Bedrooms: <?php echo $room['num_bedrooms']; ?></p>
    <p>Beds: <?php echo $room['num_beds']; ?></p>
    <p>Bathrooms: <?php echo $room['num_bathrooms']; ?></p>
    <p>Kitchen: <?php echo $room['kitchen'] ? 'Yes' : 'No'; ?></p>
    <p>WiFi: <?php echo $room['wifi'] ? 'Yes' : 'No'; ?></p>
    <p>AC: <?php echo $room['ac'] ? 'Yes' : 'No'; ?></p>
    <img src="<?php echo $room['image_path1']; ?>" alt="Room Image 1">
    <img src="<?php echo $room['image_path2']; ?>" alt="Room Image 2">
    <img src="<?php echo $room['image_path3']; ?>" alt="Room Image 3">

    <!-- Edit Room Popup -->
    <div id="edit-popup" class="popup">
    <span class="close" onclick="hideEditPopup()">&times;</span>
        <h2>Edit Room</h2>
        <form action="./edit_rooms/edit_room.php" method="post" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo $room_id; ?>">
            <label for="name">Name:</label>
            <input type="text" id="name" name="name" value="<?php echo $room['name']; ?>" required><br>
            <label for="description">Description:</label>
            <textarea id="description" name="description" required><?php echo $room['description']; ?></textarea><br>
            <label for="price">Price:</label>
            <input type="number" id="price" name="price" value="<?php echo $room['price']; ?>" required><br>
            <label for="location">Location:</label>
            <input type="text" id="location" name="location" value="<?php echo $room['location']; ?>" required><br>
            <label for="bedrooms">Bedrooms:</label>
            <input type="number" id="bedrooms" name="bedrooms" value="<?php echo $room['num_bedrooms']; ?>" required><br>
            <label for="beds">Beds:</label>
            <input type="number" id="beds" name="beds" value="<?php echo $room['num_beds']; ?>" required><br>
            <label for="bathrooms">Bathrooms:</label>
            <input type="number" id="bathrooms" name="bathrooms" value="<?php echo $room['num_bathrooms']; ?>" required><br>
            <label for="kitchen">Kitchen:</label>
            <input type="checkbox" id="kitchen" name="kitchen" <?php echo $room['kitchen'] ? 'checked' : ''; ?>><br>
            <label for="wifi">WiFi:</label>
            <input type="checkbox" id="wifi" name="wifi" <?php echo $room['wifi'] ? 'checked' : ''; ?>><br>
            <label for="ac">AC:</label>
            <input type="checkbox" id="ac" name="ac" <?php echo $room['ac'] ? 'checked' : ''; ?>><br>
            <label for="image_path1">Image 1:</label>
            <input type="file" id="image_path1" name="image_path1"><br>
            <label for="image_path2">Image 2:</label>
            <input type="file" id="image_path2" name="image_path2"><br>
            <label for="image_path3">Image 3:</label>
            <input type="file" id="image_path3" name="image_path3"><br>
            <button type="submit">Save Changes</button>
            <!-- <button type="button" onclick="hideEditPopup()">Cancel</button> -->
        </form>
    </div>

    <!-- Remove Room Popup -->
    <div id="remove-popup" class="popup">
        <h2>Confirm Remove Room</h2>
        <p>Are you sure you want to remove this room?</p>
        <button onclick="confirmRemove()">Yes</button>
        <button onclick="hideRemovePopup()">No</button>
    </div>
</body>
</html>
