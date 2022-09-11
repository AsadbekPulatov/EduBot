<?php

require_once 'User.php';

$user = new User("967469906");

$centers = $user->getTrainingCentres();

$sql = "select * from trainingcentres WHERE district = 1";
$result = $connect->query($sql);
$centers = [];
while ($row = $result->fetch_assoc()) {
    $centers[] = $row;
}

echo "<pre>".
var_dump($centers)."</pre>";
