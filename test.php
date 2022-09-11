<?php

require_once 'User.php';

//$chat_id = 967469906;
//$user = new User($chat_id);


$sql = "SELECT * FROM districts WHERE `uz` = 'Yunusobod tumani'";
$result = $connect->query($sql);
$row = $result->fetch_assoc();

echo 'DISTRICTS<br>';
echo '<pre>';
var_dump($row);
echo '</pre>';

$sql = "SELECT * FROM subjects WHERE `uz` = 'Shaxmat'";
$result = $connect->query($sql);
$row = $result->fetch_assoc();

echo 'SUBJECT<br>';
echo '<pre>';
var_dump($row);
echo '</pre>';


//$centers = $user->getTrainingCentres();
//
//echo '<pre>';
//var_dump($centers);
//echo '</pre>';