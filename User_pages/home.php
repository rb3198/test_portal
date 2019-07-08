<?php 
date_default_timezone_set('Asia/Kolkata');

session_start();
// include '../connect.php';
// $sql = "SELECT id FROM users WHERE email = ?;";
// $stmt = mysqli_stmt_init($conn);
// mysqli_stmt_prepare($stmt, $sql);
// mysqli_stmt_bind_param($stmt, "s", $_SESSION['userEmail']);
// if(mysqli_stmt_execute($stmt)) {
// 	$res = mysqli_stmt_get_result($stmt);
// 	if($res->num_rows > 0) {
// 		while($row = $res->fetch_assoc())
// 			$_SESSION['userRollNo'] = $row['id'];
// 	}
// 	mysqli_stmt_close($stmt);
// 	mysqli_close($conn);
// }
if($_POST['theme'] == 0)
	$theme = 'dark';
else
	$theme = 'light';
?>
<div id="first_div">
	<?php echo '<div class="'.$theme.'_div">'; ?>
		<h2>JOIN A TEST</h2>
		<form name="join_test" onsubmit="jt()" style="margin-top:5vh">
			<input type="text" name="test_code" placeholder="Enter Test Code..."><br>
			<input type="submit" name="sub" style="height: 30px; width: 50px; border: none; margin-top: 10px; background-color: #F3C400; cursor: pointer;" value="Go!">
		</form>

	</div>
	<?php echo '<div class="'.$theme.'_div">'; ?>
		<h2 style="margin-bottom:5vh">NEXT TEST</h2>
		<?php 
		include '../connect.php';
		$stmt = mysqli_stmt_init($conn);
		$time = time();
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
		
	</div>
	<?php echo '<div class="'.$theme.'_div">'; ?>
		<h2 style="margin-bottom:5vh">PREVIOUS TEST</h2> 
		<?php 
		$time=time();
		include '../connect.php';
		$stmt = mysqli_stmt_init($conn);
		$sql = "SELECT `name`, `rank`, `marks` FROM `marks` m JOIN test t ON m.test_id = t.t_id WHERE m.student_id = ?  and t.end_time < ? ORDER BY end_time DESC LIMIT 1";
		mysqli_stmt_prepare($stmt, $sql);
		mysqli_stmt_bind_param($stmt, "ii", $_SESSION['userRollNo'],$time);
		if(mysqli_stmt_execute($stmt)) {
			$res = mysqli_stmt_get_result($stmt);
			if($res->num_rows == 0)
				echo '<p style = "margin-top: 15px; color: #F3C400">No tests Given Previously</p>';
			else {
					while($row = $res->fetch_assoc()) {
						$test_rank = $row['rank'];
						$test_name = $row['name'];
						$test_marks = $row['marks'];
					}
					echo '<p style = "margin-top: 15px; align-self: flex-start; margin-left:5%"><b style = "color: #F3C400">Test  : </b>  '.$test_name.'</p>';
					echo '<p style = "align-self: flex-start; margin-left:5%"><b style = "color: #F3C400">Marks : </b>  '.$test_marks.'</p>';
					echo '<p style = "align-self: flex-start; margin-left:5%"><b style = "color: #F3C400">Rank  : </b>  '.$test_rank.'</p>';
			}
			
		}
		else
			echo '<p style = "align-self: flex-start"><b style = "color: red">We faced a Server Error. Sorry!</p>';
		mysqli_stmt_close($stmt);
		mysqli_close($conn);
		?>
	</div>
</div>
<?php echo '<div class="'.$theme.'_div" id="upcoming">'; ?>
<h2 style="align-self: flex-start; margin-left: 3%; margin-top: 2vh">All Upcoming Tests<img src="../Icons/down_arr.png" onclick="showUpcoming()"></h2>
<?php
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
?>

</div>
<?php echo '<div class="'.$theme.'_div">'; 
?>

last test performance
	<!-- <div id="chart_div" style="width: 70%; height: 20vh;"></div> -->
</div>
<!-- <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Year', 'Sales', 'Expenses'],
          ['2013',  1000,      400],
          ['2014',  1170,      460],
          ['2015',  660,       1120],
          ['2016',  1030,      540]
        ]);

        var options = {
          title: 'Company Performance',
          hAxis: {title: 'Year',  titleTextStyle: {color: '#333'}},
          vAxis: {minValue: 0}
        };

        var chart = new google.visualization.AreaChart(document.getElementById('chart_div'));
        chart.draw(data, options);
      }
</script> -->