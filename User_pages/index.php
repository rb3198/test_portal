<!DOCTYPE html>
<html>
<head>
	<title>Football Statistics</title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<link href="https://fonts.googleapis.com/css?family=Major+Mono+Display|Roboto" rel="stylesheet">
</head>
<body>
	<header>
		<a href=""></a>
		<nav>
			<ul>
				<li onclick="makeActive('pl')">Premier League</li>
				<li onclick="makeActive('ll')">La Liga</li>
				<li onclick="makeActive('bn')">Bundesliga</li>
				<li onclick="makeActive('sa')">Serie A</li>
				<li onclick="makeActive('l1')">Ligue 1</li>
			</ul>
		</nav>
	</header>
	<div id="main">
	</div>
	<script type="text/javascript">
		
			function makeActive(league) {
				var k = 0;
				var i = document.querySelectorAll("header nav ul li")[0]; 
				var flag = 0;
				while(flag != 1) {
					i.classList.remove("active_ch_header");
					k++;
					if(document.querySelectorAll("header nav ul li")[k] != null)
						i = document.querySelectorAll("header nav ul li")[k];
					else
						break;
				}
				if(league == "pl") {
					var plhead = document.querySelectorAll("header nav ul li")[0];
					
					plhead.classList.toggle("active_ch_header");
				}
				else if(league == ("ll")) {
					var plhead = document.querySelectorAll("header nav ul li")[1];
					plhead.classList.toggle("active_ch_header");
				}
				else if(league == ("bn")) {
					var plhead = document.querySelectorAll("header nav ul li")[2];
					plhead.classList.toggle("active_ch_header");
				}
				else if(league == ("sa")) {
					var plhead = document.querySelectorAll("header nav ul li")[3];
					plhead.classList.toggle("active_ch_header");
				}
				else if(league == ("l1")) {
					var plhead = document.querySelectorAll("header nav ul li")[4];
					plhead.classList.toggle("active_ch_header");
				}
			}
	</script>
</body>
</html>