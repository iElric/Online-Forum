<?php
//category.php
session_start();
include 'connect.php';
include 'header.php';

if($_SESSION['signed_in'] == false)
{
    //the user is not signed in
    echo 'Sorry, you have to be <a href="signin.php">signed in</a> to create a category.';
}
else {
    if ($_SERVER['REQUEST_METHOD'] != 'POST') {
        //the form hasn't been posted yet, display it
        echo "<form method='post' action=''>
        Category name: <input type='text' name='category_name' />
        Category description: <textarea name='category_description' /></textarea>
        <input type='submit' value='Add category' />
     </form>";
    } else {
        //the form has been posted, so save it
        $sql = "INSERT INTO
                    categories(category_name, category_description)
                VALUES('" . mysqli_real_escape_string($connect, $_POST['category_name']) . "',
                       '" . mysqli_real_escape_string($connect, $_POST['category_description']) . "')";


        $result = mysqli_query($connect, $sql);

        if (!$result) {
            //something went wrong, display the error
            echo 'Error' . mysqli_error($connect);
        } else {
            echo 'New category successfully added.';
        }
    }
}

mysqli_free_result($result);
mysqli_close($connect);
include 'footer.php';