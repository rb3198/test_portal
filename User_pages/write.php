<!-- <head>
	<link rel="stylesheet" type="text/css" href="writestyle.css">
</head> -->
<?php
session_start();
// date_default_timezone_set('Asia/Kolkata');
$time=time();
include("../connect.php");
if($_POST['theme'] == 0)
	$theme = 'dark';
else
	$theme = 'light';
$rank = 0;
if(array_key_exists('marks', $_POST)) {
	//Invoked on submit, else invoked on request to display questions of the test
	$stmt = mysqli_stmt_init($conn);
	$sql = 'UPDATE marks SET marks.marks = ?, marks.status = ? WHERE student_id = ? AND test_id = ? ';
	mysqli_stmt_prepare($stmt, $sql);
	$status = 2;
	mysqli_stmt_bind_param($stmt, "iiii", $_POST['marks'], $status ,$_SESSION['userRollNo'], $_SESSION['liveTestId']);
	mysqli_stmt_execute($stmt);
	$res = mysqli_stmt_affected_rows($stmt);
	if($res > 0) {
		mysqli_stmt_close($stmt);
		$stmt1 = mysqli_stmt_init($conn);
		$sql = 'SELECT * FROM marks WHERE student_id=? AND test_id = ? ORDER BY marks.marks DESC';
		mysqli_stmt_prepare($stmt1, $sql);
		mysqli_stmt_bind_param($stmt1, "ii", $_SESSION['userRollNo'], $_SESSION['liveTestId']);
		mysqli_stmt_execute($stmt1);
		$res = mysqli_stmt_get_result($stmt1);
		while($row = $res->fetch_assoc()) {
			$rank++;
			if($row['student_id'] == $_SESSION['userRollNo'])
				break;
		}
	}
	else {
		//TODO
	}

	echo 'Marks = '.$_POST['marks'];
	echo 'Provisional Rank = '.$rank;
	exit();
}
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
$sql="SELECT * FROM questions WHERE qp_id = ".$_SESSION['liveTestId'];
$sql1='SELECT * FROM test AS t WHERE (t_id IN (SELECT test_id FROM marks WHERE student_id='.$_SESSION['userRollNo'].' ) AND start_time<'.$time.' AND end_time >'.$time.')';
$result=mysqli_query($conn,$sql);
$result1=mysqli_query($conn,$sql1);
$rc=mysqli_num_rows($result);
$rc1=mysqli_num_rows($result1);
$questions=array();
$ans=array();
$right = array();
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
		array_push($right, $row['ans']);
		}

	}
}

echo '<p style="height:0; width:0" id="count_ques">'.count($questions).'</p>';
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
			if($ans[$j] == $right[$i])
				echo '<div><input type="radio" value="'.$jk.'"name="answer'.$i.'" style="margin-left:15px " attri="rt"><p>'.$ans[$j].'</p></div>';
			else
				echo  '<div><input type="radio" value="'.$jk.'"name="answer'.$i.'" style="margin-left:15px" ><p>'.$ans[$j].'</p></div>';
			$j++;
		}
		$flag=1;	
		while ($j == 0 || $j % 4 != 0){
			$jk = $j % 4;
			if($ans[$j] == $right[$i])
				echo '<div><input type="radio" value="'.$jk.'"name="answer'.$i.'" style="margin-left:15px " attri="rt"><p>'.$ans[$j].'</p></div>';
			else
				echo  '<div><input type="radio" value="'.$jk.'"name="answer'.$i.'" style="margin-left:15px" ><p>'.$ans[$j].'</p></div>';
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
