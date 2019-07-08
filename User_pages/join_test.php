<?php
date_default_timezone_set('Asia/Kolkata');
session_start();
$json = "";
include('../connect.php');
//Check if Test Code is Valid
$sql = "SELECT t_id, subject, start_time, end_time FROM test WHERE test_code= ?"; //Query
$stmt  = mysqli_stmt_init($conn);
mysqli_stmt_prepare($stmt, $sql);
mysqli_stmt_bind_param($stmt, "s", $_POST['test_code']);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);
if($res->num_rows == 0){
	$json = '{"error": "Invalid Test"}';
	// echo '<p style="font-weight:bold; color: red">Invalid Test</p>';
	mysqli_stmt_close($stmt);
}
else{
	//Test is valid, store details for future use.
	while($row = $res->fetch_assoc()) {
		$tid = $row['t_id'];
		$sub = $row['subject'];
		$startTime = $row['start_time'];
		$endTime = $row['end_time'];
	}
	mysqli_stmt_close($stmt);
	//Check if test is completed. If yes, cannot Join.
	if(time() < $startTime)
		$status = 0;
	elseif(time() < $endTime)
		$status = 1;
	else
		$status = 2;
	if($status == 2) {
		// echo '<p style="font-weight:bold; color: red">Cannot join a Completed Test!<br>Time: '.time().'</p>';
		$json = '{"error":"Cannot join a Completed Test!"}';
		exit();
	}
	//Check if user has already joined the test
	$stmt0 = mysqli_stmt_init($conn);
	$sql = "SELECT student_id FROM marks WHERE student_id = ? AND test_id IN ( SELECT t_id FROM test WHERE test_code= ?);";
	mysqli_stmt_prepare($stmt0, $sql);
	mysqli_stmt_bind_param($stmt0, "is", $_SESSION['userRollNo'], $_POST['test_code']);
	mysqli_stmt_execute($stmt0);
	$res = mysqli_stmt_get_result($stmt0);
	if($res->num_rows > 0){
		// echo '<p>'.$res->num_rows.'<br></p>';
		// echo '<p>'.$_POST['test_code'].'<br></p>';
		// echo '<p style="font-weight:bold; color: red">Test Already Joined!</p>';
		$json = '{"error": "Test Already Joined"}';
		mysqli_stmt_close($stmt0);
		exit();
	}
	mysqli_stmt_close($stmt0);
	//Everything is valid, add the user info to marks table
	//Also check the end time of the test
	//update the details of latest test in session if test is ongoing 
	$stmt2 = mysqli_stmt_init($conn);
	$sql = "SELECT * FROM test WHERE t_id = ?";
	mysqli_stmt_prepare($stmt2, $sql);
	mysqli_stmt_bind_param($stmt2, "i", $tid);
	if(mysqli_stmt_execute($stmt2)) {
		$res = mysqli_stmt_get_result($stmt2);
		$row = $res->fetch_assoc();
		if(time() < $row['end_time'] && time() >= $row['start_time']) {
			//test is going on, set session variable & return the end time of the test
			$_SESSION['liveTestEndTime'] = $row['end_time'];
			$_SESSION['liveTestStartTime'] = $row['start_time'];
			$_SESSION['liveTestId'] = $row['t_id'];
			$json = '{"error":"null", "endTime": "'.$_SESSION['liveTestEndTime'].'", "message":"live"}';
		}
	}
	else
		$json = '{"error": "Server Error"}';
	mysqli_stmt_close($stmt2);

	$stmt1 = mysqli_stmt_init($conn);
	$sql = "INSERT INTO marks(student_id, test_id, subject,status) VALUES (?,?,?,?);";
	mysqli_stmt_prepare($stmt1, $sql);
	mysqli_stmt_bind_param($stmt1, "iisi", $_SESSION['userRollNo'], $tid, $sub,$status);
	mysqli_stmt_execute($stmt1);
	if($stmt1->affected_rows === 0)
		$json = '{"error":"Joining Failed. Please Try Again"}';
	else {
		if($_SESSION['liveTestEndTime'] != -1) {

		}
		else
			$json = '{"error": "null", "message": "Joined!"}';
	}
	mysqli_stmt_close($stmt1);

}
mysqli_close($conn);

echo $json;

?>