<?php
session_start();

include 'connect.php';
include 'header.php';

$sql = "select post_content from posts where post_created_user_id = '" .$_SESSION['user_id'] ."'";
$result = mysqli_query($connect, $sql);

echo '<table frame="void">
              <tr>
                <th>Post History</th>
              </tr>';

while ($row = mysqli_fetch_assoc($result)) {
    echo '<tr>';
    echo '<td style="text-align:center;">';
    echo $row['post_content'];
    echo '</td>';
    echo '</tr>';
}


echo '</table>';