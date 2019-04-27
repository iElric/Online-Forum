<?php
session_start();
include 'connect.php';
include 'header.php';

if ($_SESSION['user_id'] == 1) {
    $sql = "DELETE FROM ban WHERE ban_user_id = '" . $_GET['user_id'] . "'";
}
else {
    $sql = "DELETE FROM ban WHERE ban_user_id = '" . $_GET['user_id'] . "' AND ban_category_id = '" . $_GET['category_id'] . "'";
}

$result = mysqli_query($connect, $sql);

echo 'the user has been released';