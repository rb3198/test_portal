<?php
session_start();
date_default_timezone_set('Asia/Kolkata');
include("../connect.php");
$diff = -1;
$time=time();
$sl='SELECT end_time from test t JOIN marks m ON t.t_id=m.test_id WHERE start_time <= '.$time.' AND end_time >= '.$time.' AND student_id='.$_SESSION['userRollNo'].' AND (m.status = 0 OR m.status = 1) ORDER BY start_time DESC LIMIT 1';
$re=mysqli_query($conn,$sl);
$json = "";
if($re->num_rows == 0)
	$json = "-1";
else {
	$ree = $re->fetch_assoc();
	$diff= $ree['end_time'];
	$json = '{"endTime":'.$diff.'}';
}
mysqli_close($conn);
echo $json;
?>