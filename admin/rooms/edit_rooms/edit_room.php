<?php
session_start();
$conn = new mysqli("localhost", "root", "", "mineghm");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $room_id = $_POST['id'];
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $location = $_POST['location'];
    $bedrooms = $_POST['bedrooms'];
    $beds = $_POST['beds'];
    $bathrooms = $_POST['bathrooms'];
    $kitchen = isset($_POST['kitchen']) ? 1 : 0;
    $wifi = isset($_POST['wifi']) ? 1 : 0;
    $ac = isset($_POST['ac']) ? 1 : 0;

    $image_path1 = $image_path2 = $image_path3 = null;
    if (!empty($_FILES['image_path1']['name'])) {
        $image_path1 = 'uploads/' . basename($_FILES['image_path1']['name']);
        move_uploaded_file($_FILES['image_path1']['tmp_name'], $image_path1);
    }
    if (!empty($_FILES['image_path2']['name'])) {
        $image_path2 = 'uploads/' . basename($_FILES['image_path2']['name']);
        move_uploaded_file($_FILES['image_path2']['tmp_name'], $image_path2);
    }
    if (!empty($_FILES['image_path3']['name'])) {
        $image_path3 = 'uploads/' . basename($_FILES['image_path3']['name']);
        move_uploaded_file($_FILES['image_path3']['tmp_name'], $image_path3);
    }

    $sql = "UPDATE rooms SET 
            name = '$name', 
            description = '$description', 
            price = $price, 
            location = '$location', 
            num_bedrooms = $bedrooms, 
            num_beds = $beds, 
            num_bathrooms = $bathrooms, 
            kitchen = $kitchen, 
            wifi = $wifi, 
            ac = $ac" .
            ($image_path1 ? ", image_path1 = '$image_path1'" : "") .
            ($image_path2 ? ", image_path2 = '$image_path2'" : "") .
            ($image_path3 ? ", image_path3 = '$image_path3'" : "") . 
            " WHERE id = $room_id";

    if ($conn->query($sql) === TRUE) {
        // echo "<script> alert('room edited successfully!'); window.history.back();</script>";
        header("Location: ../room_details.php?id=$room_id");
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>
