<?php
date_default_timezone_set('Asia/Kolkata');
$time=time();
include("../connect.php");
$sl="SELECT end_time from test t JOIN marks m ON t.t_id=m.test_id WHERE m.status=1 ";
$re=mysqli_query($conn,$sl);
$ree = $re->fetch_assoc();
//echo $ree['end_time'];
$diff= $ree['end_time']-$time;
  
    
$hour =floor ($diff / 3600); 
  
$diff %= 3600; 
$minutes = floor($diff / 60) ; 
  
$diff %= 60; 
$seconds = $diff;
//echo ('time remaining is '.$hour.':'.$minutes.':'.$seconds.'<br>'); 

while($rrr=mysqli_fetch_assoc($re))
{
	if ($time>$ree['start_time'] && $time<$ree['end_time'])
	{
		echo ('time remaining is '.$hour.':'.$minutes.':'.$seconds.'<br>'); 

	}

}


?>