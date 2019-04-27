<?php
session_start();
include 'connect.php';
include 'header.php';


if (!$_SESSION['signed_in']) {
    echo 'You must be signed in to post a reply.';
    echo "<script>location.href='".$_SERVER["HTTP_REFERER"]."';</script>";
}
else {
    $sql = "delete from follow where follower_id = '" . $_SESSION['user_id'] . "' 
    and following_id = " . mysqli_real_escape_string($connect, $_GET['user_id']) . " ";

    $result = mysqli_query($connect, $sql);

    echo 'you unfollowed the user successfully!';
    echo "<script>location.href='".$_SERVER["HTTP_REFERER"]."';</script>";
}

mysqli_close($connect);