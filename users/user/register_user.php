<?php

    session_start();
    // Connect to the database
    // $servername = "localhost";
    // $username = "root";
    // $password = "";
    // $dbname = "mineghm";
    // $conn = new mysqli($servername, $username, $password, $dbname);
    try {
        $conn = new mysqli("localhost", "root", "", "mineghm");
    } catch (\Throwable $th) {
        echo $th;
    }


    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

if ($_SERVER["REQUEST_METHOD"] == "POST" ) {
        $username = $_POST['username'];
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $email = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password
        $govID = $_POST['govID'];
        $phonenumber = $_POST['phonenumber'];

        $sql = "INSERT INTO users (username, first_name, last_name, email, password, govID, phonenumber)
                VALUES ('$username', '$first_name', '$last_name', '$email', '$password', '$govID', '$phonenumber')";
        try {
            if ($conn->query($sql) === TRUE) {
                // echo "User registered successfully.";
                $_SESSION['emailUser'] = $email;
                // echo "session created using the email";
    
                // Redirect to a protected page0
                // header("Location: logedINpage.php?email=${email}");
                header("Location: ../rooms/availableRooms.php");
                exit;
            } else {
                echo "in else";
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        } catch (\Throwable $th) {
            echo $th;
        }

        $conn->close();
}
?>


<!-- try and catch controling Handle username check request
    if (isset($_POST['username_check'])) {
        $username = $_POST['username'];
        $sql = "SELECT id FROM users WHERE username = '$username'";
        $result = $conn->query($sql); 
        if ($result->num_rows > 0) {
            echo "taken";
        } else {
            echo "available";
        }
        exit;
    } -->