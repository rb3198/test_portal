<?php
date_default_timezone_set('Asia/Kolkata');
session_start();
include('../connect.php');
//Check if Test Code is Valid
$sql = "SELECT t_id, subject, start_time, end_time FROM test WHERE test_code= ?"; //Query
$stmt  = mysqli_stmt_init($conn);
mysqli_stmt_prepare($stmt, $sql);
mysqli_stmt_bind_param($stmt, "s", $_POST['test_code']);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);
if($res->num_rows == 0){
	echo "<p>Invalid Test</p>";
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
		echo '<p style="color: red">Cannot join a Completed Test!</p>';
		exit();
	}
	//Check if user has already joined the test
	$stmt0 = mysqli_stmt_init($conn);
	$sql = "SELECT student_id FROM marks WHERE student_id = ?;";
	mysqli_stmt_prepare($stmt0, $sql);
	mysqli_stmt_bind_param($stmt0, "i", $_SESSION['userRollNo']);
	mysqli_stmt_execute($stmt0);
	$res = mysqli_stmt_get_result($stmt0);
	if($res->num_rows > 0){
		echo '<p>Test Already Joined!</p>';
		mysqli_stmt_close($stmt0);
		exit();
	}
	mysqli_stmt_close($stmt0);
	//Everything is valid, add the user info to marks table
	$stmt1 = mysqli_stmt_init($conn);
	$sql = "INSERT INTO marks(student_id, test_id, subject, status) VALUES (?,?, ?, ?) ;";
	mysqli_stmt_prepare($stmt1, $sql);
	mysqli_stmt_bind_param($stmt1, "iisi", $_SESSION['userRollNo'], $tid, $sub, $status);
	mysqli_stmt_execute($stmt1);
	if($stmt1->affected_rows === 0)
		echo '<p>Joining Failed. Please Try Again</p>';
	else
		echo "<p> Joined!</p>";
	mysqli_stmt_close($stmt1);
}
mysqli_close($conn);


?>