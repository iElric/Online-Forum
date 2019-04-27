<?php
//category.php
session_start();
include 'connect.php';
include 'header.php';

$length = 10;
$pagenum = @$_GET['page'] ? $_GET['page'] : 1;

$sqltot="select count(*) from threads WHERE thread_category_id = '" . mysqli_real_escape_string($connect, $_GET['category_id']) . "'";

$arrtot=mysqli_fetch_row(mysqli_query($connect, $sqltot));
$pagetot=ceil($arrtot[0]/$length);

$offset=($pagenum-1)*$length;


$sql = "SELECT  
                    thread_id,
                    thread_title,
                    thread_created_time,
                    thread_category_id,
                    thread_created_user_id
                FROM
                    threads
            
                WHERE
                    thread_category_id = '" . mysqli_real_escape_string($connect, $_GET['category_id']) . "'
                ORDER BY thread_id DESC limit {$offset},{$length};";

$result = mysqli_query($connect, $sql);

if (!$result) {
    echo 'The topics could not be displayed, please try again later.';
} else {
    if (mysqli_num_rows($result) == 0) {
        echo $_GET['page'];
        echo 'There are no topics in this category yet. you could post a 
                <a href="create_topic.php?category_id= ' . mysqli_real_escape_string($connect, $_GET['category_id']) . '">
                new topic</a>';
    } else {
        //prepare the table

        $category_id = mysqli_real_escape_string($connect, $_GET['category_id']);

        echo '<table>
                      <tr>
                        <th>Topic</th>
                        <th>Posted by</th>
                        <th>Reply</th>
                        <th>Created at</th>
                      </tr>';

        while ($row = mysqli_fetch_assoc($result)) {
            $sqlname = "select user_name from users where user_id = '". $row['thread_created_category_id'] . "'";
            $sqlnumber = "select count(*) from posts where post_thread_id = '". $row['thread_id'] . "'";

            $name = mysqli_fetch_row(mysqli_query($connect, $sqlname));
            $number = mysqli_fetch_row(mysqli_query($connect, $sqlnumber));
            echo '<tr>';
            echo '<td class="topic_one">';
            echo '<a href="topic.php?id=' . $row['thread_id'] . '&category_id=' . mysqli_real_escape_string($connect, $_GET['category_id']) . '">' . $row['thread_title'] . '</a>';
            echo '</td>';
            echo '<td class="topic_two" style="text-align: center">';
            echo $name[0];
            echo '</td>';
            echo '<td class="topic_three" style="text-align: center">';
            echo $number[0] - 1;
            echo '</td>';
            echo '<td class="topic_four" style="text-align: center">';
            echo date('d-m-Y', strtotime($row['thread_created_time']));
            echo '</td>';
            echo '</tr>';
        }

        echo '</table>';

        if ($pagetot == 1) {
            $prevpage = $pagenum;
            $nextpage = $pagenum;
        }
        else if ($pagenum == 1) {
            $prevpage = $pagenum;
            $nextpage = $pagenum + 1;
        }
        else if ($pagenum == $pagetot) {
            $prevpage = $pagenum - 1;
            $nextpage = $pagenum;
        }
        else {
            $prevpage = $pagenum - 1;
            $nextpage = $pagenum + 1;
        }

        echo '<br> <span>
                    <a href="create_topic.php?category_id= ' . mysqli_real_escape_string($connect, $_GET['category_id']) . '">
                    <button class = "post_button">New Thread</button></a>';

        echo "<a class='transfer_page' href= 'category.php?category_id={$category_id}&page={$nextpage}'>
                    <button class = post_button>next page</button></a>";

        echo "
                    <a class='transfer_page' href='category.php?category_id={$category_id}&page={$prevpage}'>
                    <button class = post_button>pre page</button></a></span>
                    ";

    }


mysqli_free_result($result);
mysqli_close($connect);
include 'footer.php';
}

