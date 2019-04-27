<?php

session_start();
include 'connect.php';
include 'header.php';

$date = time();

$id = mysqli_real_escape_string($connect, $_GET['user_id']);
$cat = ltrim($_GET['category_id']);

$odate=date("Y-m-d H:i:s");
$stop_date = date('Y-m-d H:i:s', strtotime('+7 day', $stop_date));

$sql = "INSERT INTO
                    ban(ban_user_id, ban_moderator_id, ban_category_id, ban_start_time, ban_end_time)
                VALUES('" . $id . "',
                       '" . $_SESSION['user_id'] . "',
                       '" . $cat . "',
                        NOW(),
                        NOW())";


$result = mysqli_query($connect, $sql);

if (!$result) {
    echo '!';
}

echo "The user has been banned!";


mysqli_close($connect);