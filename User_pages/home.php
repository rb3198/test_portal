<?php 
date_default_timezone_set('Asia/Kolkata');

session_start();
include '../connect.php';
$sql = "SELECT id FROM users WHERE email = ?;";
$stmt = mysqli_stmt_init($conn);
mysqli_stmt_prepare($stmt, $sql);
mysqli_stmt_bind_param($stmt, "s", $_SESSION['userEmail']);
if(mysqli_stmt_execute($stmt)) {
	$res = mysqli_stmt_get_result($stmt);
	if($res->num_rows > 0) {
		while($row = $res->fetch_assoc())
			$_SESSION['userRollNo'] = $row['id'];
	}
	mysqli_stmt_close($stmt);
}
?>
<div id="first_div" >
	<div class="dark_div"> 
		<h2>JOIN A TEST</h2>
		<form name="join_test" onsubmit="jt()" >
			<input type="text" name="test_code" placeholder="Enter Test Code..."><br>
			<input type="submit" name="sub" style="height: 30px; width: 50px; border: none; margin-top: 10px; background-color: #F3C400; cursor: pointer;" value="Go!">
		</form>
	</div>
	<div class="dark_div">
		<h2>NEXT TEST</h2>
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
					echo '<p style= "margin-top: 20px">'.$testName.',<br>'.date('jS F Y', $testTime).'</p>';
				}
			}
			else
				echo '<p style= "margin-top: 20px; color: #F3C400">No Tests due!</p>';
		}
		mysqli_stmt_close($stmt);
		mysqli_close($conn);
		?>
		
	</div>
	<div class="dark_div">
		<h2>PREVIOUS TEST</h2> 
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
				echo '<p style = "margin-top: 15px; color: #F3C400">No tests Given Previously</p>'.$time;
			else {
					while($row = $res->fetch_assoc()) {
						$test_rank = $row['rank'];
						$test_name = $row['name'];
						$test_marks = $row['marks'];
					}
					echo '<p style = "margin-top: 15px; align-self: flex-start;"><b style = "color: #F3C400">Test  : </b>  '.$test_name.'</p>';
					echo '<p style = "align-self: flex-start"><b style = "color: #F3C400">Marks : </b>  '.$test_marks.'</p>';
					echo '<p style = "align-self: flex-start"><b style = "color: #F3C400">Rank  : </b>  '.$test_rank.'</p>';
			}
			
		}
		?>
	</div>
</div>
<div class="dark_div">
<h2>Upcoming Tests</h2>
<table class="dark_table">
	<tr>
		<th>No</th>
		<th>Name</th>
		<th>Subject</th>
		<th>Date</th>
		<th>Start Time</th>
		<th>End Time</th>
	</tr>
	<tr>
		<td>1</td>
		<td>DBMS Test 1</td>
		<td>DBMS</td>
		<td>26th April 2019</td>
		<td>5 PM</td>
		<td>7 PM</td>
	</tr>
	<tr>
		<td>2</td>
		<td>DBMS Test 2</td>
		<td>DBMS</td>
		<td>28th April 2019</td>
		<td>5 PM</td>
		<td>7 PM</td>
	</tr>
</table>
</div>
<div class="dark_div">
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