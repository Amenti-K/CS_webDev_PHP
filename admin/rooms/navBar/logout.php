<?php
session_start();
session_unset();
session_destroy();
// base URL dynamically
$base_url = "http://" . $_SERVER['HTTP_HOST'] . '/GHM mine/admin';
header("Location: $base_url");
exit();
?>
