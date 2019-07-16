<?php
$stmt = mysqli_stmt_init($conn);
$sql = "SELECT end_time, start_time, test_id FROM marks JOIN test ON marks.test_id = test.t_id WHERE marks.student_id=? AND test.start_time <= ? AND test.end_time >= ? AND (marks.status = ? OR marks.status = ?) LIMIT 1";
if(!mysqli_stmt_prepare($stmt, $sql)) {
	//MODIFY

}
else {
	$time = time();
	$st0 = 0;
	$st1 = 1;
	mysqli_stmt_bind_param($stmt, 'iiiii', $_SESSION['userRollNo'],$time, $time, $st0,$st1);
	mysqli_stmt_execute($stmt);
	$res = mysqli_stmt_get_result($stmt);
	if($res->num_rows == 0) {
		//no test going on
		$_SESSION['liveTestEndTime'] = -1;
		$_SESSION['liveTestStartTime'] = -1;
		$_SESSION['liveTestId'] = -1;
		mysqli_stmt_close($stmt);
		mysqli_close($conn);
	}  
	else {
		//test going on, return the details
		while($row = $res->fetch_assoc()) {
			$_SESSION['liveTestEndTime'] = $row['end_time'];
			$_SESSION['liveTestStartTime'] = $row['start_time'];
			$_SESSION['liveTestId'] = $row['test_id'];
		}
		$json = '{"startTime":'.$_SESSION['liveTestStartTime'].', "endTime":'.$_SESSION['liveTestEndTime'].', "id":'.$_SESSION['liveTestId'].'}';
	}
}
