<?php
session_start(); //to ensure you are using same session
session_destroy(); //destroy the session
include 'connect.php';
include 'header.php';

echo 'Successfully sign out, you can now return to the <a href="index.php">home page</a>.';
echo "<meta http-equiv='refresh' content='3; url = index.php'>";

mysqli_free_result($result);
mysqli_close($connect);
include 'footer.php';