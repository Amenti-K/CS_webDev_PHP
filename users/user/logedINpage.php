<?php 
session_start();
$user = $_SESSION['emailUser'];
// $email = isset($_GET['email']) ? $_GET['email'] : 0;
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>LOged IN</title>
  </head>
  <body>
    <h1>loged in</h1>
    <?php echo "<h2>";
    echo $user;
    echo "</h2>";
    ?>
  </body>
</html>
