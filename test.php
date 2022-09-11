<?php

require_once 'User.php';

$centers = $user->getTrainingCentres();

$sql = "select * from trainingcentres WHERE district = 'yunusobod'";
$result = $connect->query($sql);
$centers = [];
while ($row = $result->fetch_assoc()) {
    $centers[] = $row;
}

$chat_id = 967469906;
$user = new User($chat_id);

echo '<pre>';
var_dump($centers);
echo '</pre>';