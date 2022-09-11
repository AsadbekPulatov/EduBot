<?php

require_once 'User.php';

$user = new User("967469906");

$centers = $user->getTrainingCentres();

echo "<pre>".
var_dump($centers)."</pre>";
