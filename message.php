<?php
session_start();
include 'connect.php';
include 'header.php';

if (!$_SESSION['signed_in']) {
    echo 'You must be signed in to send a message!';
    echo "<script>location.href='".$_SERVER["HTTP_REFERER"]."';</script>";
}
else {
    if ($_SERVER['REQUEST_METHOD'] != 'POST') {
        $sql = "SELECT
            message_content
        FROM
            message
        WHERE
            (message_from_id =  '" . $_SESSION['user_id'] . "'
        AND 
            message_to_id = '" . $_GET['user_id'] . "')
        OR 
          (message_from_id =  '" . $_GET['user_id'] . "'
        AND 
            message_to_id = '" . $_SESSION['user_id'] . "')";

        $result = mysqli_query($connect, $sql);
        if (!$result) {
            echo '好';
        }

        echo '<table frame="void">
              <tr>
                <th>users message</th>
              </tr>';

        while ($row = mysqli_fetch_assoc($result)) {
            echo '<tr>';
            echo '<td style="text-align:center;">';
            echo $row['message_content'];
            echo '</td>';
            echo '</tr>';
        }

        echo '</table>';

        echo '<table class="Reply">';

        echo '<tr>';
        echo '<td colspan="2" class="reply">';
        echo '<br>';
        echo '<h3>Private messages:</h3>';
        echo '<br>';
        echo '<div style="text-align: center">';
        echo "<form method='post' action=''> 
                        <textarea name='reply_content' class='content' /></textarea> <br>
                        <input class='post_button' type='submit' value='submit'/> 
                        </form>";
        echo '</td>';
        echo '</tr>';
        echo '</div>';

        echo '</table>';
    } else {
        $sql = "INSERT INTO 
                        message(message_content,
                            message_time,
                            message_from_id,
                            message_to_id) 
                            VALUES ('" . $_POST['reply_content'] . "',
                              NOW(),
                            " . $_SESSION['user_id'] . ",
                            " . $_GET['user_id'] . ")";

        $result = mysqli_query($connect, $sql);

        echo 'you sent to the user successfully!';

        echo "<script>alert('sent successfully!');location.href='" . $_SERVER["HTTP_REFERER"] . "';</script>";
    }
}


