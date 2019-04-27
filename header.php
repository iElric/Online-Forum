
</html>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="nl" lang="nl">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="description" content="A short description." />
    <meta name="keywords" content="put, keywords, here" />
    <title>AlgoPlayers</title>
    <link rel="stylesheet" href="style.css" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
    <link rel="shortcut icon" href="favicon.ico" />
</head>
<body>


    <div class="topnav" id="myTopnav" style="vertical-align: middle">
        <a href="index.php" class="active" style="margin-left: 100px; color: grey" id = "logo">AlgoPlayers</a>
        <a href="index.php" style="color: grey">Home</a>
        <?php
        $sql = "select count(*) from moderators where moderator_id = '" . $_SESSION['user_id'] . "'";
        $result = mysqli_query($connect, $sql);

        if (!$result || mysqli_fetch_row($result)[0] == 0 || $_SESSION['user_id'] != 1) {
            //
        }
        else {
            echo '<a href="create_cat.php" style="color: grey">Create</a>';
        }

        if (isset($_SESSION['signed_in']) && $_SESSION['signed_in'] == true)
        {
            echo '<a class = "unique_name" href = "personal.php" style="margin-left: 50%" style="color: black">Welcome, ' . $_SESSION['user_name'] . '</a> <a href="signout.php" style="color: grey">Sign out</a>';
        }
        else
        {
            echo '<a href="signin.php" style="margin-left: 50%; color: grey">Sign in</a> <a href="signup.php" style="color: grey">Register</a>';
        }
        ?>
    </div>

    <div id="wrapper">

        <div id="content">
