<?php

session_start();
include 'connect.php';
include 'header.php';

if ($_SESSION['user_id'] == 1) {
    $sql = "select ban_user_id, ban_category_id, user_name from ban join users on ban_user_id = user_id";
}
else {
    $sql = "select ban_user_id, ban_category_id, user_name from ban join users on ban_user_id = user_id where ban_category_id = '" . $_GET['category_id'] . "'";
}
$result = mysqli_query($connect, $sql);

echo '<table frame="void">
              <tr>
                <th>Banned user name</th>
                <th>Release</th>
              </tr>';

while ($row = mysqli_fetch_assoc($result)) {
    echo '<tr>';
    echo '<td class="leftpart" style="text-align:center;">';
    echo $row['user_name'];
    echo '</td>';
    echo '<td class="rightpart">';
    echo '<a href="release.php?user_id=' . $row['ban_user_id'] . '&category_id=' . $_GET['category_id'] . '"> 
                                  <input type="submit" value="release"/></a>';
    echo '</td>';
    echo '</tr>';
}

echo '</table>';

mysqli_close($connect);