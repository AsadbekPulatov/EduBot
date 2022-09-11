<?php

require_once 'User.php';

$chat_id = 967469906;
$user = new User($chat_id);

$centers = $user->getTrainingCentres();

echo '<pre>';
var_dump($centers);
echo '</pre>';