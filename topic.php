<?php
//topic.php
session_start();
include 'connect.php';
include 'header.php';

$sql = "SELECT
            thread_id,
            thread_title
        FROM
            threads
        WHERE
            thread_id =  '" . mysqli_real_escape_string($connect, $_GET['id']) . "'";

$result = mysqli_query($connect, $sql);

if (!$result) {
    echo 'The topic could not be displayed, please try again later.' . mysqli_error($connect);
} else {
    if (mysqli_num_rows($result) == 0) {
        echo 'the topic does not exist.';
        echo "<meta http-equiv='refresh' content='3; url = index.php'>";
    } else {
        //display category data
        while ($row = mysqli_fetch_assoc($result)) {
            $subject = '<a>' . $row['thread_title'] . '</a>';
        }

        //do a query for the topics
        $sql = "SELECT
                    posts.post_id,
                    posts.post_thread_id,
                    posts.post_content,
                    posts.post_created_time,
                    posts.post_created_user_id,
                    users.user_id,
                    users.user_name,
                    users.user_follower_number,
                    users.user_following_number,
                    users.user_experience_point
                FROM
                    posts
                LEFT JOIN
                    users
                ON
                    posts.post_created_user_id = users.user_id
                WHERE
                    posts.post_thread_id = '" . mysqli_real_escape_string($connect, $_GET['id']) . "'
                order by post_created_time";

        $result = mysqli_query($connect, $sql);

        if (!$result) {
            echo 'The posts could not be displayed, please try again later.';
        } else {
            if (mysqli_num_rows($result) == 0) {
                $sql = "delete from threads where thread_id = '" . mysqli_real_escape_string($connect, $_GET['id']) . "'";
                $result = mysqli_query($connect, $sql);
                echo 'There are no post in this topic yet.';
                echo "<meta http-equiv='refresh' content='1; url = index.php'>";
            } else {
                //prepare the table
                if ($_SERVER['REQUEST_METHOD'] != 'POST') {
                    echo '<table border="1">
                      <tr>
                        <th colspan="3"><a>' . $subject . '</a> </th>
                    
                      </tr>';

                    $temp = mysqli_real_escape_string($connect, $_GET['category_id']);

                    $sql = "select count(*) from manage where manage_moderator_id = '" . $_SESSION['user_id'] . "' and manage_category_id = '" . mysqli_real_escape_string($connect, $_GET['category_id']) . "'";
                    $manda = mysqli_fetch_row(mysqli_query($connect, $sql));

                    if ($manda[0] != 0 or $_SESSION['user_id'] == 1) {
                        while ($row = mysqli_fetch_assoc($result)) {
                            $sql2 = "select count(*) from follow where follower_id = '" . $_SESSION['user_id'] . "'  and following_id = '" . $row['user_id'] . "'";
                            $result2 = mysqli_query($connect, $sql2);
                            $sql3 = "select get_user_level({$row['user_experience_point']})";
                            $result3 = mysqli_query($connect, $sql3);
                            echo '<tr>';
                            echo '<td class="post_left">';
                            echo $row['user_name'];
                            echo '<br>';
                            echo 'LV'.mysqli_fetch_row($result3)[0];
                            echo '<br>';
                            echo date('d-m-Y', strtotime($row['post_created_time']));
                            echo '<br>';
                            echo date('H:i:s', strtotime($row['post_created_time']));
                            echo '<br>';
                            if ($_SESSION['signed_in'] == false || mysqli_fetch_row($result2)[0] == 0) {
                                echo '<a href="follow.php?user_id=' . $row['user_id'] . '"><input type="submit" value="follow"/></a>';
                                echo '<br> follower: '. $row['user_follower_number'];
                                echo '<br> following: '. $row['user_following_number'];
                            }
                            else {
                                echo '<a href="unfollow.php?user_id=' . $row['user_id'] . '"><input type="submit" value="unfollow"/></a>';
                                echo '<br> follower: '. $row['user_follower_number'];
                                echo '<br> following: '. $row['user_following_number'];
                            }

                            echo '<br>';
                            echo '<a href="ban.php?user_id=' . $row['user_id'] . '&category_id=' . $_GET['category_id'] . '"><input type="submit" value="ban"/></a>';
                            echo '<br>';
                            echo '<a href="message.php?user_id=' . $row['user_id'] . '"><input type="submit" value="message"/></a>';
                            echo '</td>';
                            echo '<td class="post_middle">';
                            echo $row['post_content'];
                            echo '</td>';
                            echo '<td class="post_right">';
                            echo '<div class = "like">';
                            echo '<a href="delete.php?post_id=' . $row['post_id'] . '"> 
                                  <input type="submit" value="delete"/></a>';
                            $likesql = "select post_liked_number, post_disliked_number from posts where post_id = '" . $row['post_id'] . "'";
                            $likeresult = mysqli_query($connect, $likesql);
                            $like = 0;
                            $dislike = 0;
                            while ($likerow = mysqli_fetch_assoc($likeresult)) {
                                $like = $likerow['post_liked_number'];
                                $dislike = $likerow['post_disliked_number'];
                            }
                            echo '<a href="like.php?post_id=' . $row['post_id'] . '"><input type="submit" value="like" ></a>' . $like;
                            echo '<a href="dislike.php?post_id=' . $row['post_id'] . '"><input type="submit" value="dislike" name="likess"></a>' . $dislike;
                            echo '</div>';
                            echo '</td>';
                            echo '</tr>';
                        }

                    } else {
                        while ($row = mysqli_fetch_assoc($result)) {
                            $sql2 = "select count(*) from follow where follower_id = '" . $_SESSION['user_id'] . "'  and following_id = '" . $row['user_id'] . "'";
                            $result2 = mysqli_query($connect, $sql2);
                            $sql3 = "select get_user_level({$row['user_experience_point']})";
                            $result3 = mysqli_query($connect, $sql3);
                            echo '<tr>';
                            echo '<td class="post_left">';
                            echo $row['user_name'];
                            echo '<br>';
                            echo 'LV'.mysqli_fetch_row($result3)[0];
                            echo '<br>';
                            echo date('d-m-Y', strtotime($row['post_created_time']));
                            echo '<br>';
                            echo date('H:i:s', strtotime($row['post_created_time']));
                            echo '<br>';
                            if ($_SESSION['signed_in'] == false || mysqli_fetch_row($result2)[0] == 0) {
                                echo '<a href="follow.php?user_id=' . $row['user_id'] . '"><input type="submit" value="follow"/></a>';
                                echo '<br> follower: '. $row['user_follower_number'];
                                echo '<br> following: '. $row['user_following_number'];
                            }
                            else {
                                echo '<a href="unfollow.php?user_id=' . $row['user_id'] . '"><input type="submit" value="unfollow"/></a>';
                                echo '<br> follower: '. $row['user_follower_number'];
                                echo '<br> following: '. $row['user_following_number'];
                            }
                            echo '<br>';
                            echo '<a href="message.php?user_id=' . $row['user_id'] . '"><input type="submit" value="message"/></a>';
                            echo '<br>';
                            echo '</td>';
                            echo '<td class="post_middle">';
                            echo $row['post_content'];
                            echo '</td>';
                            echo '<td class="post_right">';
                            $likesql = "select post_liked_number, post_disliked_number from posts where post_id = '" . $row['post_id'] . "'";
                            $likeresult = mysqli_query($connect, $likesql);
                            $like = 0;
                            $dislike = 0;
                            while ($likerow = mysqli_fetch_assoc($likeresult)) {
                                $like = $likerow['post_liked_number'];
                                $dislike = $likerow['post_disliked_number'];
                            }
                            echo '<a href="like.php?post_id=' . $row['post_id'] . '"><input type="submit" value="like" name="likes"></a>' . $like;
                            echo '<a href="dislike.php?post_id=' . $row['post_id'] . '"><input type="submit" value="dislike" name="likess"></a>' . $dislike;
                            echo '</td>';
                            echo '</tr>';
                        }
                    }

                    echo '</table>';

                    echo '<table class="Reply">';

                    echo '<tr>';
                    echo '<td colspan="2" class="reply">';
                    echo '<br>';
                    echo '<h3>Reply:</h3>';
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
                    $sql = "SELECT
                          ban_user_id
                            FROM
                          ban 
                          WHERE ban_user_id = '" . $_SESSION['user_id'] . "' 
                          AND ban_category_id =  '" . $_GET['category_id'] . "'";
                    $result = mysqli_query($connect, $sql);

                    if (!$_SESSION['signed_in']) {
                        echo 'You must be signed in to post a reply.';
                    }
                    else if (sizeof(mysqli_fetch_row($result)) != 0) {
                        echo 'You have been banned!';
                    }
                    else {
                        //a real user posted a real reply
                        $sql = "INSERT INTO 
                        posts(post_content,
                            post_created_time,
                            post_thread_id,
                            post_created_user_id) 
                            VALUES ('" . $_POST['reply_content'] . "',
                              NOW(),
                            " . mysqli_real_escape_string($connect, $_GET['id']) . ",
                            " . $_SESSION['user_id'] . ")";

                        $result = mysqli_query($connect, $sql);

                        if (!$result) {
                            echo 'Your reply has not been saved, please try again later.';
                        } else {
                            echo 'Your reply has been saved, check out <a href="topic.php?id=' . $_GET['id'] . '">the topic</a>.';
                            $url = "topic.php?id=" . $_GET['id'];
                            echo "<meta http-equiv='refresh' content='3; url = $url'>";
                        }
                    }
                }

            }
        }
    }
}

mysqli_free_result($result);
mysqli_close($connect);
include 'footer.php';