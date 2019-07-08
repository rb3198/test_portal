<head>
	<link rel="stylesheet" type="text/css" href="writestyle.css">
</head>
<?php
session_start();
// date_default_timezone_set('Asia/Kolkata');
$time=time();
include("../connect.php");
if($_POST['theme'] == 0)
	$theme = 'dark';
else
	$theme = 'light';
// $sl="SELECT end_time from test t JOIN marks m ON t.t_id=m.test_id WHERE m.status=1 ";
// $re=mysqli_query($conn,$sl);
// $ree = $re->fetch_assoc();
// $diff= $ree['end_time'] - time();
// // date_default_timezone_set('Europe/London');
// // $diff = $diff-3600;
// // $date=new DateTime();
// // $date->setTimestamp($diff);
// // echo $date->format('H:i:s').'<br>';
// $diff = $diff - 19800;
//echo date('H:i:s', $diff);
date_default_timezone_set('Asia/Kolkata');
$sql="SELECT * FROM questions;";
$sql1="SELECT * FROM test as t where (t_id in (select test_id from marks where status=0 or status=1));";
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
	echo '<div class= "'.$theme.'_div1">
		<h1>'.$questions[$i].'</h1>';
		if($flag==1){
			$jk = $j % 4;
			echo  '<div><input type="radio" value="'.$jk.'"name="answer" style="margin-left:15px" ><p>'.$ans[$j].'</p></div>';
			$j++;
		}
		$flag=1;	
		while ($j == 0 || $j % 4 != 0){
			$jk = $j % 4;
			if($flag1 == 1)
				echo '<div><input type="radio" value="'.$jk.'"name="answer" style="margin-left:15px"><p>'.$ans[$j].'</p></div>';
			else
				echo '<div><input type="radio" value="'.$jk.'"name="answer" style="margin-left:15px"><p>'.$ans[$j].'</p></div>';
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

// echo '<script type="text/javascript">
// getTime();
// </script>';
?>

<!-- <script type="text/javascript">
getTime();
</script> -->
