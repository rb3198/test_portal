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
		<p>Aptitude Test,<br>
			26 Oct 2019</p>
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