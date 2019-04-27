<?php
//connect.php

// The server should be localhost or other remote server address 
$server = '';

// The username should be mysql user name 
$username   = '';

// The password should be mysql password for the user
$password   = '';
$database   = 'ITforum';

$connect = mysqli_connect($server, $username, $password) or die(mysqli_connect_error());

if(!$connect) {
    exit('Error: could not establish database connection');
}

mysqli_select_db($connect, 'ITforum');
