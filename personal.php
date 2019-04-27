<?php
session_start(); //to ensure you are using same session
include 'connect.php';
include 'header.php';

echo '<div class="order">
        <span class="line"></span>
        <span class="txt">Personal Information</span>
        <span class="line"></span>
        </div>';

$sql = "SELECT count(*) FROM manage WHERE manage_moderator_id = '" . $_SESSION['user_id'] . "'";

$result = mysqli_query($connect, $sql);



if (mysqli_fetch_row($result)[0] == 1 || $_SESSION['user_id'] == 1) {
    echo '<div style="text-align: center;" >';
    echo '<br>';
    echo '<a href="banlist.php?category_id=' . $category . '"><input class="post_button" type="submit" value="manage"/></a>';
    echo '<br>';
    echo '<br>';
    echo '<a href="history.php"><input class="post_button" type="submit" value="post history"/></a>';
    echo '<br>';
    echo '<br>';
    echo '<a href="followlist.php"><input class="post_button" type="submit" value="follow list"/></a>';
    echo '<br>';
    echo '<br>';
    echo '<a href="messages.php"><input class="post_button" type="submit" value="messages"/></a>';
    echo '</div>';
}
else {
    echo '<div style="text-align: center;">';
    echo '<br>';
    echo '<a href="history.php"><input class="post_button" type="submit" value="post history"/></a>';
    echo '<br>';
    echo '<br>';
    echo '<a href="followlist.php"><input class="post_button" type="submit" value="follow list"/></a>';
    echo '<br>';
    echo '<br>';
    echo '<a href="messages.php"><input class="post_button" type="submit" value="messages"/></a>';
    echo '</div>';
}


mysqli_free_result($result);
mysqli_close($connect);

include 'footer.php';