<?php
// Start session and establish database connection
session_start();
$conn = new mysqli("localhost", "root", "", "mineghm");

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch users from the database
$sql = "SELECT username, first_name, last_name, email, phonenumber, govID FROM users";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Users</title>
    <link rel="stylesheet" href="styleUser.css">
</head>
<body>
    <div class="nav">
        <?php include '../rooms/navBar/navbar.php' ?>
    </div>
    <main class="table">
        <section class="table_header">
            <h1>Registered Users</h1>
        </section>
        <section class="table_body">
            <table>
                <thead>
                    <tr>
                        <th>Username</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Email</th>
                        <th>Phone Number</th>
                        <th>Government ID</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            echo "<tr>
                                    <td>{$row['username']}</td>
                                    <td>{$row['first_name']}</td>
                                    <td>{$row['last_name']}</td>
                                    <td>{$row['email']}</td>
                                    <td>{$row['phonenumber']}</td>
                                    <td>{$row['govID']}</td>
                                  </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='6'>No users found</td></tr>";
                    }
                    ?>
                    <tr>
                        <td>bb</td>
                        <td>biftu</td>
                        <td>kebede</td>
                        <td>biftu@email</td>
                        <td>091111111</td>
                        <td>ugr/547/15</td>
                    </tr>
                    <tr>
                        <td>bb</td>
                        <td>biftu</td>
                        <td>kebede</td>
                        <td>biftu@email</td>
                        <td>091111111</td>
                        <td>ugr/547/15</td>
                    </tr>
                    <tr>
                        <td>bb</td>
                        <td>biftu</td>
                        <td>kebede</td>
                        <td>biftu@email</td>
                        <td>091111111</td>
                        <td>ugr/547/15</td>
                    </tr>
                    <tr>
                        <td>bb</td>
                        <td>biftu</td>
                        <td>kebede</td>
                        <td>biftu@email</td>
                        <td>091111111</td>
                        <td>ugr/547/15</td>
                    </tr>
                    <tr>
                        <td>bb</td>
                        <td>biftu</td>
                        <td>kebede</td>
                        <td>biftu@email</td>
                        <td>091111111</td>
                        <td>ugr/547/15</td>
                    </tr>
                    <tr>
                        <td>bb</td>
                        <td>biftu</td>
                        <td>kebede</td>
                        <td>biftu@email</td>
                        <td>091111111</td>
                        <td>ugr/547/15</td>
                    </tr>
                    <tr>
                        <td>bb</td>
                        <td>biftu</td>
                        <td>kebede</td>
                        <td>biftu@email</td>
                        <td>091111111</td>
                        <td>ugr/547/15</td>
                    </tr>
                    <tr>
                        <td>bb</td>
                        <td>biftu</td>
                        <td>kebede</td>
                        <td>biftu@email</td>
                        <td>091111111</td>
                        <td>ugr/547/15</td>
                    </tr>
                    <tr>
                        <td>bb</td>
                        <td>biftu</td>
                        <td>kebede</td>
                        <td>biftu@email</td>
                        <td>091111111</td>
                        <td>ugr/547/15</td>
                    </tr>
                    <tr>
                        <td>bb</td>
                        <td>biftu</td>
                        <td>kebede</td>
                        <td>biftu@email</td>
                        <td>091111111</td>
                        <td>ugr/547/15</td>
                    </tr>
                    <tr>
                        <td>bb</td>
                        <td>biftu</td>
                        <td>kebede</td>
                        <td>biftu@email</td>
                        <td>091111111</td>
                        <td>ugr/547/15</td>
                    </tr>
                    <tr>
                        <td>bb</td>
                        <td>biftu</td>
                        <td>kebede</td>
                        <td>biftu@email</td>
                        <td>091111111</td>
                        <td>ugr/547/15</td>
                    </tr>
                    <tr>
                        <td>bb</td>
                        <td>biftu</td>
                        <td>kebede</td>
                        <td>biftu@email</td>
                        <td>091111111</td>
                        <td>ugr/547/15</td>
                    </tr>
                    <tr>
                        <td>bb</td>
                        <td>biftu</td>
                        <td>kebede</td>
                        <td>biftu@email</td>
                        <td>091111111</td>
                        <td>ugr/547/15</td>
                    </tr>
                    

                </tbody>
            </table>
        </section>
    </main>
    
</body>
</html>

<?php
$conn->close();
?>
