<?php
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
    $name = $conn->real_escape_string($_POST['name']);
    $description = $conn->real_escape_string($_POST['description']);
    $price = $conn->real_escape_string($_POST['price']);
    $location = $conn->real_escape_string($_POST['location']);
    $num_bedrooms = (int)$_POST['num_bedrooms'];
    $num_beds = (int)$_POST['num_beds'];
    $num_bathrooms = (int)$_POST['num_bathrooms'];
    $kitchen = isset($_POST['kitchen']) ? 1 : 0;
    $wifi = isset($_POST['wifi']) ? 1 : 0;
    $ac = isset($_POST['ac']) ? 1 : 0;

    // Handle file uploads
    $target_dir = "../../../uploads/";
    $image_paths = [];

    for ($i = 1; $i <= 3; $i++) {
        $image_key = "image$i";
        if (isset($_FILES[$image_key]) && $_FILES[$image_key]['error'] == 0) {
            $target_file = $target_dir . basename($_FILES[$image_key]["name"]);
            $uploadOk = 1;
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

            // Check if image file is a actual image or fake image
            $check = getimagesize($_FILES[$image_key]["tmp_name"]);
            if ($check !== false) {
                $uploadOk = 1;
            } else {
                echo "File $i is not an image.<br>";
                $uploadOk = 0;
            }

            // Check file size
            if ($_FILES[$image_key]["size"] > 5000000) {
                echo "Sorry, your file $i is too large.<br>";
                $uploadOk = 0;
            }

            // Allow certain file formats
            if (!in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif'])) {
                echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed for file $i.<br>";
                $uploadOk = 0;
            }

            // Check if $uploadOk is set to 0 by an error
            if ($uploadOk == 0) {
                echo "Sorry, your file $i was not uploaded.<br>";
            // if everything is ok, try to upload file
            } else {
                if (move_uploaded_file($_FILES[$image_key]["tmp_name"], $target_file)) {
                    $image_paths[] = $target_file;
                } else {
                    echo "Sorry, there was an error uploading your file $i.<br>";
                    // Check specific error
                    echo "Debugging info: " . $_FILES[$image_key]["error"] . "<br>";
                }
            }
        } else {
            echo "No file $i uploaded or error in uploading.<br>";
        }
    }

    $sqlIR = "INSERT INTO rooms (name, description, price, image_path1, image_path2, image_path3, location, num_bedrooms, num_beds, num_bathrooms, kitchen, wifi, ac) 
                VALUES ('$name', '$description', $price, '$image_paths[0]', '$image_paths[1]', '$image_paths[2]', '$location', $num_bedrooms, $num_beds, $num_bathrooms, $kitchen, $wifi, $ac);";
    if (count($image_paths) == 3) {
        if (mysqli_query($conn, $sqlIR)){
            echo "New room record created successfully";
            header("location: ../availableRooms.php");
        } else {
            echo "Error: " . $stmt->error;
        }
    } else {
        echo "All three images must be uploaded.";
    }

$conn->close();
}
?>

<!-- // if (count($image_paths) == 3) {
//     // Use prepared statement to prevent SQL injection
//     $stmt = $conn->prepare("INSERT INTO rooms (name, description, price, image_path1, image_path2, image_path3, location, num_bedrooms, num_beds, num_bathrooms, kitchen, wifi, ac) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
//     $stmt->bind_param($name, $description, $price, $image_paths[0], $image_paths[1], $image_paths[2], $location, $num_bedrooms, $num_beds, $num_bathrooms, $kitchen, $wifi, $ac);

//     if ($stmt->execute()) {
//         echo "New room record created successfully";
//     } else {
//         echo "Error: " . $stmt->error;
//     }

//     $stmt->close();
// } else {
//     echo "All three images must be uploaded.";
// } -->