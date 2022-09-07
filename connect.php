<?php

$servername = "us-cdbr-east-06.cleardb.net";
$username = "b6286921c7a2a1";
$password = "ffd09433";
$database = "heroku_7784e9053df43f3";

$connect = new mysqli($servername, $username, $password, $database);

//if ($connect->connect_error) {
//    die("Connection failed: " . $connect->connect_error);
//}
//echo "Connected successfully";