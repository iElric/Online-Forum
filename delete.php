<?php

session_start();
include 'connect.php';
include 'header.php';

$sql = "DELETE FROM posts WHERE post_id = '" . mysqli_real_escape_string($connect, $_GET['post_id']) . "'";

$result = mysqli_query($connect, $sql);

if (!$result) {
    echo 'sorry,failed';
}
else {
    echo 'you successfully delete the post!';
    echo "<script>location.href='".$_SERVER["HTTP_REFERER"]."';</script>";
}

mysqli_close($connect);