<?php
session_start();
require '../../connect.php';

//Class for storing subjects & Marks
class Profile {
	public $DBMS = array(); //array for storing marks of DBMS
	public $Maths = array(); //array for storing marks of Maths
	public $Quants = array(); //array for storing marks of Quantitative subject
	public $English = array(); //array for storing marks of English subject
	public $DSA = array(); //array for storing marks of DSA subject

}
$stmt = mysqli_stmt_init($conn);
$sql = 'SELECT marks.test_id, marks.rank, marks.subject, marks.marks, test.name FROM marks JOIN test ON marks.test_id = test.t_id WHERE student_id = ? AND status = ? ORDER BY end_time ASC
';
if(!mysqli_stmt_prepare($stmt, $sql)) {
	echo '-1';
	die();
}
$status = 2;
mysqli_stmt_bind_param($stmt, 'ii', $_SESSION['userRollNo'], $status);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);
if($res->num_rows == 0) {
	echo '{"error": 0}';
}
else {
	$subj_count = 0;
	$last_sub = '';
	//Make a new object of the user to store marks in each Subject
	$user = new Profile();
	//Push marks of each subject inside the object made
	while($row = $res->fetch_assoc()) {
		if($row['subject'] == 'Maths')
			array_push($user->Maths, $row['marks']);
		else if($row['subject'] == 'DBMS')
			array_push($user->DBMS, $row['marks']);
		elseif($row['subject'] == 'Quants')
			array_push($user->Quants, $row['marks']);
		elseif($row['subject'] == 'English')
			array_push($user->English, $row['marks']);
		elseif($row['subject'] == 'DSA')
			array_push($user->DSA, $row['marks']);
	}
	$json = json_encode($user);
	echo $json;
}
mysqli_stmt_close($stmt);
mysqli_close($conn);
?>