<?php

require_once 'User.php';

//$chat_id = 967469906;
//$user = new User($chat_id);


$sql = "select * from users where chat_id=" . $chat_id . " limit 1";
$result = $connect->query($sql)->fetch_assoc();
$district_id = $result['district_id'];
$subject_id = $result['subject_id'];
$sql = "select keyword from subjects where id=" . $subject_id . " limit 1";
$result = $connect->query($sql)->fetch_assoc();
$subject = $result['keyword'];
$sql = "select keyword from districts where id=" . $district_id . " limit 1";
$result = $connect->query($sql)->fetch_assoc();
$district = $result['keyword'];
$sql = "select * from trainingcentres WHERE district = {$district}";
$result = $connect->query($sql);
$centers = [];
while ($row = $result->fetch_assoc()) {
//    $subjects = explode(',', $row['subjects']);
//    if (in_array($subject, $subjects)) {
        $centers[] = $row;
//    }
}


echo "district {$district}<br>";
echo "subject {$subject}<br>";

//$centers = $user->getTrainingCentres();

echo '<pre>';
var_dump($centers);
echo '</pre>';