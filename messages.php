<?php
session_start();
include 'connect.php';
include 'header.php';

$sql = "select distinct (user_name), user_id from message join users on message_to_id = user_id where message_from_id = '" . $_SESSION['user_id'] . "'";

$result = mysqli_query($connect, $sql);

echo '<table frame="void">
              <tr>
                <th>Name of private messages</th>
              </tr>';

while ($row = mysqli_fetch_assoc($result)) {
    echo '<tr>';
    echo '<td style="text-align:center;">';
    echo '<a href="message.php?user_id=' . $row['user_id'] .'"> '.$row['user_name'] . ' </a>';
    echo '</td>';
    echo '</tr>';
}

