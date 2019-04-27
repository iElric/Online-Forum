<?php
//create_cat.php
session_start();
include 'connect.php';
include 'header.php';

$sql = "SELECT
                          ban_user_id
                            FROM
                          ban WHERE ban_user_id = '" . $_SESSION['user_id'] . "'";
$result = mysqli_query($connect, $sql);

if ($_SESSION['signed_in'] == false) {
    //the user is not signed in
    echo 'Sorry, you have to be <a href="signin.php">signed in</a> to create a topic.';
}
else if(sizeof(mysqli_fetch_row($result)) != 0) {
    echo 'you have been banned!';
}
else {
    //the user is signed in
    if ($_SERVER['REQUEST_METHOD'] != 'POST') {
        //the form hasn't been posted yet, display it
        //retrieve the categories from the database for use in the dropdown
        $sql = "SELECT
                    category_id,
                    category_name,
                    category_description
                FROM
                    categories";

        $result = mysqli_query($connect, $sql);

        if (!$result) {
            //the query failed, uh-oh :-(
            echo 'Error while selecting from database. Please try again later.';
        } else {
            if (mysqli_num_rows($result) == 0) {
                //there are no categories, so a topic can't be posted

            } else {
                echo '<div class="order">
                    <span class="line"></span>
                    <span class="txt">New post</span>
                    <span class="line"></span>
                    </div>';

                echo '<form method="post" action="">
                    <div style="text-align: center;">
                    <br> Title: <input class="sign" type="text" name="thread_title" placeholder="Please enter title here"/> <br>';

                echo '<br> <textarea name="post_content" class="content" placeholder="Please enther content here"></textarea>
                    <br><input class="post_button" type="submit" value="Post" />
                    </div>
                 </form>';
            }
        }
    } else {
        //start the transaction


        //the form has been posted, so save it
        //insert the topic into the topics table first, then we'll save the post into the posts table

        $sql = "INSERT INTO 
                        threads(thread_title,
                               thread_created_time,
                               thread_last_reply_time,
                               thread_category_id,
                               thread_created_user_id)
                    VALUES('" . mysqli_real_escape_string($connect, $_POST['thread_title']) . "',
                               NOW(),
                               NOW(),
                               
                               " . mysqli_real_escape_string($connect, $_GET['category_id']) . ",
                               " . $_SESSION['user_id'] . "
                               )";

        $result = mysqli_query($connect, $sql);
        if (!$result) {
            //something went wrong, display the error
            echo 'An error occured while inserting your data. Please try again later.' . mysqli_error($connect);
            $sql = "ROLLBACK;";
            $result = mysqli_query($connect, $sql);
        } else {
            //the first query worked, now start the second, posts query
            //retrieve the id of the freshly created topic for usage in the posts query


            $topicid = mysqli_insert_id($connect);

            $sql = "INSERT INTO
                            posts(post_content,
                                  post_created_time,
                                  post_thread_id,
                                  post_created_user_id)
                        VALUES('" . mysqli_real_escape_string($connect, $_POST['post_content']) . "',
                                  NOW(),
                                  " . $topicid . ",
                                  " . $_SESSION['user_id'] . "
                            )";

            $result = mysqli_query($connect, $sql);

            if (!$result) {
                //something went wrong, display the error
                echo 'An error occured while inserting your post. Please try again later.' . mysqli_error($connect);
                $sql = "ROLLBACK;";
                $result = mysqli_query($connect, $sql);
            } else {
                $sql = "COMMIT;";
                $result = mysqli_query($connect, $sql);

                //after a lot of work, the query succeeded!
                echo 'You have successfully created your new topic, 
                            return to <a href="category.php?category_id=' . $_GET['category_id'] . '">category</a>.';
                $url = "category.php?category_id=" . $_GET['category_id'];
                echo "<meta http-equiv='refresh' content='3; url = $url'>";

            }
        }

    }
}

mysqli_free_result($result);
mysqli_close($connect);
include 'footer.php';
