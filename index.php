<?php
//index.php

session_start();
include 'connect.php';
include 'header.php';


$sql = "SELECT
            *
        FROM
            categories";

$result = mysqli_query($connect, $sql);

if(!$result)
{
    echo 'The categories could not be displayed, please try again later.';
}
else
{
    if(mysqli_num_rows($result) == 0)
    {
        echo 'No categories defined yet.';
    }
    else
    {
        //prepare the table
        echo '<table frame="void">
              <tr>
                <th>Category</th>
                <th>Last topic</th>
              </tr>';


        while($row = mysqli_fetch_assoc($result))
        {
            $sql = "SELECT
                        max(thread_id)
                    FROM
                        threads
                    WHERE
                        thread_category_id =  '" . $row['category_id'] . "' ";

            $last = mysqli_fetch_row(mysqli_query($connect, $sql));

            $sql = "SELECT
                        thread_title
                    FROM
                        threads
                    WHERE
                        thread_id =  '" . $last[0] . "' ";

            $lastname = mysqli_fetch_row(mysqli_query($connect, $sql));

            echo '<tr>';
            echo '<td class="leftpart">';
            echo '<h3><a href="category.php?category_id= ' .$row['category_id']. ' ">' . $row['category_name'] . '</a></h3>' . $row['category_description'];
            echo '</td>';
            echo '<td class="rightpart">';
            echo '<a href="topic.php?id= ' . $last[0] . ' " style="text-align: center">' . $lastname[0] . '</a>';
            echo '</td>';
            echo '</tr>';
        }
        echo '</table>';


    }
}
mysqli_free_result($result);
mysqli_close($connect);
include 'footer.php';