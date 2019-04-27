<?php
session_start();
include 'connect.php';
include 'header.php';

if (!$_SESSION['signed_in']) {
    echo 'You must be signed in to follow a user!';
    echo "<script>location.href='".$_SERVER["HTTP_REFERER"]."';</script>";
}
else {
    $sql = "INSERT INTO 
                        follow(follower_id,
                               following_id) 
                            VALUES ('" . $_SESSION['user_id'] . "',
                            " . mysqli_real_escape_string($connect, $_GET['user_id']) . ")";
    $result = mysqli_query($connect, $sql);

    //$sql = "update users set user_follower_number = user_follower_number + 1 where user_id = '" . $_GET['user_id'] . "'";

    echo 'you followed the user successfully!';
    echo "<script>alert('followed successfully!');location.href='".$_SERVER["HTTP_REFERER"]."';</script>";
}

mysqli_close($connect);