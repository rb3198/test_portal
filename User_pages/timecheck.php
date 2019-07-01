<?php
date_default_timezone_set('Asia/Kolkata');
$time=time();
include("../connect.php");
$diff = -1;
$sl="SELECT end_time from test t JOIN marks m ON t.t_id=m.test_id WHERE m.status=1 ";
$re=mysqli_query($conn,$sl);
$ree = $re->fetch_assoc();
$diff= $ree['end_time'] - time();
// date_default_timezone_set('Europe/London');
// $diff = $diff-3600;
// $date=new DateTime();
// $date->setTimestamp($diff);
// echo $date->format('H:i:s').'<br>';
// $diff = (string)date('H:i:s',$diff);
$json = '{"diff":'.$diff.'}';
echo $json;
?>