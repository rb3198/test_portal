<?php

date_default_timezone_set('Asia/Kolkata');
$time=time();
include("../connect.php");


$sql="SELECT * FROM questions;";
$sql1="SELECT * FROM test as t where (t_id in (select test_id from marks where test_id=0 or test_id=1));";
$result=mysqli_query($conn,$sql);
$result1=mysqli_query($conn,$sql1);
$rc=mysqli_num_rows($result);
$rc1=mysqli_num_rows($result1);
$questions=array();
$ans=array();
$q=0;
$a=0;
$ro=mysqli_fetch_assoc($result1);
if($rc>0)
{
	while($row=mysqli_fetch_assoc($result)  )

	{
		if($time>$ro['start_time'] && $time<$ro['end_time'])
		{
		$questions[$q]=$row['question'];
		$q++;
		$ans[$a]=$row['a'];
		$a++;
		$ans[$a]=$row['b'];
		$a++;
		$ans[$a]=$row['c'];
		$a++;
		$ans[$a]=$row['d'];
		$a++;
		}

	}
}

$i = 0;
$j = 0;
$flag=0;
$flag1 = 0;
if ($time>$ro['start_time'] && $time<$ro['end_time'])
{
while($i < count($questions)) {
	echo '<div class= "dark_div">
		<h1>'.$questions[$i].'</h1>';
		if($flag==1){
			$jk = $j % 4;
			echo  '<div><div><input type="radio" value="'.$jk.'"name="answer"><p>'.$ans[$j].'</p></div>';
			$j++;
		}
		$flag=1;	
		while ($j == 0 || $j % 4 != 0){
			$jk = $j % 4;
			if($flag1 == 1)
				echo '<div><input type="radio" value="'.$jk.'"name="answer"><p>'.$ans[$j].'</p></div>';
			else
				echo '<div><div><input type="radio" value="'.$jk.'"name="answer"><p>'.$ans[$j].'</p></div></div>';
			$j++;
		}
		echo '</div></div>';
		$flag1 = 1;
		$i++;

	}
}
elseif($time>$ro['end_time'])
{
	echo"test has ended";
}
elseif($time<$ro['start_time'])
{
	echo"test is yet to start";
}
?>
