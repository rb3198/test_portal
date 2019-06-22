		<?php
		session_start();
		echo '<h2 style="margin-bottom:5vh">NEXT TEST</h2>'; 
		include '../connect.php';
		$stmt = mysqli_stmt_init($conn);
		$time = time();
		echo $time;
		$sql = "SELECT t.name, t.start_time FROM test as t WHERE t.start_time > ? AND t.t_id in (SELECT test_id from marks WHERE student_id = ? ) ORDER BY t.start_time ASC LIMIT 1";
		mysqli_stmt_prepare($stmt, $sql);
		mysqli_stmt_bind_param($stmt, "ii", $time, $_SESSION['userRollNo']);
		if(mysqli_stmt_execute($stmt)) {
			$res = mysqli_stmt_get_result($stmt);
			if($res->num_rows > 0 ) {
				while($row = $res->fetch_assoc()) {
					$testName = $row['name'];
					$testTime = $row['start_time'];
					echo '<p style= "margin-top: 20px; color: #F3C400"><b>'.$testName.',<br>'.date('jS F Y', $testTime).',<br>'.date('h:iA', $testTime).'</b></p>';
				}
			}
			else
				echo '<p style= "margin-top: 20px; color: #F3C400">No Tests due!</p>';
		}
		mysqli_stmt_close($stmt);
		mysqli_close($conn);
		?>