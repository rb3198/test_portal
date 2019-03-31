<?php 
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
		$sql = "SELECT t.name, t.start_time FROM test as t WHERE t.start_time > UNIX_TIMESTAMP() AND t.t_id in (SELECT test_id from marks WHERE student_id = ? ) ORDER BY t.start_time ASC LIMIT 1";
		mysqli_stmt_prepare($stmt, $sql);
		mysqli_stmt_bind_param($stmt, "i", $_SESSION['userRollNo']);
		if(mysqli_stmt_execute($stmt)) {
			$res = mysqli_stmt_get_result($stmt);
			if($res->num_rows > 0 ) {
				while($row = $res->fetch_assoc()) {
					$testName = $row['name'];
					$testTime = $row['start_time'];
					echo '<p>'.$testName.',<br>'.date('jS F Y', $testTime).'</p>';
				}
			}
		}

		?>
		
	</div>
	<div class="dark_div">
		<h2>PREVIOUS TEST</h2> 
	</div>
</div>
<div class="dark_div">
upcoming tests
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