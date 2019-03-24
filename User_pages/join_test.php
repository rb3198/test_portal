<?php
session_start();
include('../connect.php');
$sql = "SELECT * FROM test WHERE test_code= ?"; //Query
$stmt  = mysqli_stmt_init($conn);
mysqli_stmt_prepare($stmt, $sql);
mysqli_stmt_bind_param($stmt, "s", $_POST['test_code']);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);
if($res->num_rows == 0)
	echo "<p>Invalid Test</p>";
else
	echo "<p> Joined!</p>";
mysqli_stmt_close($stmt);


?>