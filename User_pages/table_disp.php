<?php
date_default_timezone_set('Asia/Kolkata');
session_start();
if($_POST['theme'] == 0)
	$theme = 'dark';
else
	$theme = 'light';
include '../connect.php';
$stmt = mysqli_stmt_init($conn);
//Make an event schedule in Server then execute this
// $sql = 'SELECT `name`, t.subject, `start_time`, `end_time` FROM marks JOIN test t ON test_id=t_id WHERE student_id=? AND status=0 ORDER BY start_time ASC;';
//Until then execute this
$sql = 'SELECT `name`, t.subject, `start_time`, `end_time` FROM marks JOIN test t ON test_id=t_id WHERE student_id=? AND start_time > ? ORDER BY start_time ASC;';
mysqli_stmt_prepare($stmt, $sql);
$time = time();
mysqli_stmt_bind_param($stmt, "ii", $_SESSION['userRollNo'], $time);
if(mysqli_stmt_execute($stmt)) {
	$res = mysqli_stmt_get_result($stmt);
	if($res->num_rows == 0)
		echo '<p style = "margin: 2vh 0; color: #F3C400">No Upcoming Tests!</p>';
	else {
		$i = 1;
		echo '<table class="'.$theme.'_table">
	<tr>
		<th>No</th>
		<th>Name</th>
		<th>Subject</th>
		<th>Date</th>
		<th>Start Time</th>
		<th>End Time</th>
	</tr>';
		while($row = $res->fetch_assoc()) {
			$start = date('g:iA', $row['start_time']); //Text Representation of Start Time
			$end = date('g:iA', $row['end_time']); //Text Representation of End Time
			$date = date('jS F  Y', $row['start_time']); //Date of the test
			$sub = $row['subject']; //Test Subject
			$name = $row['name']; //Test Name
			echo '<tr>
					<td>'.$i.'</td>
					<td>'.$name.'</td>
					<td>'.$sub.'</td>
					<td>'.$date.'</td>
					<td>'.$start.'</td>
					<td>'.$end.'</td>
				</tr>';
			$i++;
		}
		echo '</table>';
	}
}
else
	echo '<p style = "align-self: flex-start"><b style = "color: red">We faced a Server Error. Sorry!</p>';
mysqli_stmt_close($stmt);
mysqli_close($conn);
?>