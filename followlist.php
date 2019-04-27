<?php
session_start();
include 'connect.php';
include 'header.php';

if (!$_SESSION['signed_in']) {
    echo 'You must be signed in to get a list!';
}
else {
    $sql = "select user_name from follow join users on following_id = user_id where follower_id = '" . $_SESSION['user_id'] . "'";
    $result = mysqli_query($connect, $sql);

    echo '<table frame="void">
              <tr>
                <th>followed user name</th>
              </tr>';

    while ($row = mysqli_fetch_assoc($result)) {
        echo '<tr>';
        echo '<td style="text-align:center;">';
        echo $row['user_name'];
        echo '</td>';
        echo '</tr>';
    }
}

mysqli_close($connect);