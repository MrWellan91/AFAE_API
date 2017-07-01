<?php
include('functions.php');
$date = $_GET['date'];
$d = testDate($date);
echo $d;