<?php
//signup.php
include 'connect.php';
include 'header.php';
include 'mail.php';


if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    /*the form hasn't been posted yet, display it
      note that the action="" will cause the form to post to the same page it is on */

    echo '<div class="order">
        <span class="line"></span>
        <span class="txt">Sign up</span>
        <span class="line"></span>
        </div>';

    echo '<form method="post" action="">
        <div style="text-align: center;"> 
        
        <br> <input class="sign" type="text" name="user_name" placeholder="Username"/> <br>
        
        <br> <input class="sign" type="password" name="user_password" placeholder="Password"> <br>
        
        <br> <input class="sign" type="password" name="user_pass_check" placeholder="Confirm password"><br>
        
        <br> <input class="sign" type="email" name="user_email" placeholder="E-mail"><br>
        
        <br><input class="sign_button" type="submit" value="sign up" />
        </div>
     </form>';
} else {
    /* so, the form has been posted, we'll process the data in three steps:
        1.  Check the data
        2.  Let the user refill the wrong fields (if necessary)
        3.  Save the data
    */
    $errors = array(); /* declare the array for later use */

    if (isset($_POST['user_name'])) {
        //the user name exists
        if (!ctype_alnum($_POST['user_name'])) {
            $errors[] = 'The username can only contain letters and digits.';
        }
        if (strlen($_POST['user_name']) > 30) {
            $errors[] = 'The username cannot be longer than 30 characters.';
        }
    } else {
        $errors[] = 'The username field must not be empty.';
    }


    if (isset($_POST['user_password'])) {
        if ($_POST['user_password'] != $_POST['user_pass_check']) {
            $errors[] = 'The two passwords did not match.';
        }
    } else {
        $errors[] = 'The password field cannot be empty.';
    }

    if (!empty($errors)) /*check for an empty array, if there are errors, they're in this array (note the ! operator)*/ {
        echo 'Uh-oh.. a couple of fields are not filled in correctly..';
        echo '<ul>';
        foreach ($errors as $key => $value) /* walk through the array so all the errors get displayed */ {
            echo '<li>' . $value . '</li>'; /* this generates a nice error list */
        }
        echo '</ul>';
    } else {
        //the form has been posted without, so save it
        //notice the use of mysql_real_escape_string, keep everything safe!
        //also notice the sha1 function which hashes the password
        $sql = "INSERT INTO
                    users(user_name, user_password, user_email ,user_created_time, user_experience_point)
                VALUES('" . mysqli_real_escape_string($connect, $_POST['user_name']) . "',
                       '" . sha1($_POST['user_password']) . "',
                       '" . mysqli_real_escape_string($connect, $_POST['user_email']) . "',
                        NOW(),
                        0)";

        $result = mysqli_query($connect, $sql);

        if (!$result) {
            //something went wrong, display the error
            echo 'Something went wrong while registering. Please try again later.';
            //echo mysql_error(); //debugging purposes, uncomment when needed
        } else {
            echo 'Successfully registered. You can now <a href="signin.php">sign in</a> and start posting! :-)';
            send_mail($_POST['user_email'], $_POST['user_name']);
            echo "<meta http-equiv='refresh' content='3; url = index.php'>";
        }
    }
}
mysqli_free_result($result);
mysqli_close($connect);
include 'footer.php';
