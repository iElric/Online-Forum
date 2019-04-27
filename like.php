<?php

session_start();
include 'connect.php';
include 'header.php';

if($_SESSION['signed_in'] == false)
{
    //the user is not signed in
    echo 'Sorry, you have to be <a href="signin.php">signed in</a> to like a post.';
    echo "<script>location.href='".$_SERVER["HTTP_REFERER"]."';</script>";
}
else {
    $sql = "select count(*) from like_post where like_post_post_id = '" . $_GET['post_id'] . "' and like_post_user_id = '" . $_SESSION['user_id'] . "'";
    $result = mysqli_query($connect, $sql);


    if (mysqli_fetch_row($result)[0] == 0) {
        $sql = "update posts set post_liked_number = post_liked_number + 1 where post_id = '" . mysqli_real_escape_string($connect, $_GET['post_id']) . "'";
        $result = mysqli_query($connect, $sql);


        $sql = "INSERT INTO 
                        like_post(like_post_user_id,
                            like_post_post_id,
                            like_post_status) 
                            VALUES ('" . $_SESSION['user_id'] . "',
                            " . $_GET['post_id'] . ",
                            1)";


        $result = mysqli_query($connect, $sql);

        echo "<script>location.href='".$_SERVER["HTTP_REFERER"]."';</script>";
    }
    else {
        echo "you have already voted!";
        echo "<script>alert('you have already voted!');location.href='".$_SERVER["HTTP_REFERER"]."';</script>";
    }
}