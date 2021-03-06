window.theme = 0; //Global variable for theme of the page: 0= Dark Theme, 1= Light theme
window.upcomingShow = 0; //Global variable for display status of upcoming div: 0=Hidden, 1= Shown
window.flag = 0; //Global variable to track the number of times submitf() function is run

window.onload = function() {
	//If test is going on, make Write a Test the default
	//If not, make Home the default option
	var li = document.querySelectorAll("#main #options nav ul li")[0]; //select home
	var status = document.getElementById('userRollNo');
	var xmlhttp = new  XMLHttpRequest();
	xmlhttp.onreadystatechange = function() {
		if(this.status == 200 && this.readyState == 4) {
			var res = this.responseText;
			if(res == "-1") {
				//Server error, mail the admin about it: TODO
				console.log(res);
				select_li(li,0);
				change_img_color(li);
			}
			else if(res == "-2") {
				//no test going on, go to home
				select_li(li,0);
				change_img_color(li);
			}
			else {
				//Test going on, redirect to write.php asynchronously
				var json = JSON.parse(res);
				//Write code
				console.log(json.startTime);
				console.log(json.endTime);
				console.log(json.id);
				li = document.querySelectorAll("#main #options nav ul li")[1]; //select writeTest
				select_li(li,1);
				change_img_color(li);
			}
		}
	}
	xmlhttp.open('GET', 'Backend/fetchLatestTest.php', true);
	// xmlhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	xmlhttp.send();

	
}

//function to change icons of li upon hover/click
function change_img_color(li) {
	var img = li.childNodes[1]; //select image inside the li 
	var orig = img.src;
	if(orig.match(/home/g)) {
		img.src = '../Icons/Selected/home.png'
	}
	if(orig.match(/write/g)) {
		img.src = '../Icons/Selected/write.png'
	}
	if(orig.match(/upcoming/g)) {
		img.src = '../Icons/Selected/upcoming.png'
	}
}

function change_back(li) {
	var li_class = li.className;
	//dont change image if li is selected
	if(li_class.match(/selected/g)) 
		return; 

	var head = document.getElementsByTagName('header')[0];
	var img = li.childNodes[1]; //select image inside the li 
	if(head.className == "dark_header") {
		var orig = img.src;
		if(orig.match(/home/g)) {
			img.src = '../Icons/dark/home.png'
		}
		if(orig.match(/write/g)) {
			img.src = '../Icons/dark/write.png'
		}
		if(orig.match(/upcoming/g)) {
			img.src = '../Icons/dark/upcoming.png'
		} 
	}
	else if(head.className == "light_header") {
		var orig = img.src;
		if(orig.match(/home/g)) {
			img.src = '../Icons/light/home.png'
		}
		if(orig.match(/write/g)) {
			img.src = '../Icons/light/write.png'
		}
		if(orig.match(/upcoming/g)) {
			img.src = '../Icons/light/upcoming.png'
		} 
	}
}

function select_li(li, no) {
	//front end changes
	var selectedLi = document.querySelector("#main .activeLi");
	if(no == selectedLi.innerText)
		return;
	selectedLi.innerText = no;
	var li_class = li.className;
	if(li.classList.length > 0)
		li.classList.toggle(li.className);
	if(li_class.match(/dark/g)) {
		li.classList.toggle('dark_selected_li');
	}
	else if(li_class.match(/light/g)) {
		li.classList.toggle('light_selected_li');
	}
	//toggle other selected li
	var li_list = document.querySelectorAll("#main #options nav ul li");
	for (var i = 0; i <= li_list.length - 1; i++) {
		if(i == no)
			continue;
		li_list[i].classList.remove("dark_selected_li");
		li_list[i].classList.remove("light_selected_li");
		li_list[i].classList.add(li_class);
		change_back(li_list[i]); //make the icons back to default from selected. since selected class has been removed function will change image back.
	}

	//back-end: request pages
	var content = document.querySelector("#main #content");
	var xhttp = new XMLHttpRequest();
	xhttp.onreadystatechange = function() {
		if(this.readyState < 4) 
			content.innerHTML = '<img src="../Icons/loading1.gif" style="width: 150px; height: 150px" alt="loading">';
		if(this.readyState == 4 && this.status == 200) {
			content.innerHTML = this.responseText;
			//load chart
			loadChart();
		}
	};
	if(no == 0) {
		// content.innerHTML = "<?php include 'home.php';?>";
		xhttp.open("POST", 'home.php', true);
		xhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded')
		xhttp.send("lol=1&theme="+window.theme);
	}
	else if(no == 1) {
		// content.innerHTML = "<?php include 'write.php';?>";
		xhttp.open("POST", 'write.php', true);
		xhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded')
		xhttp.send("lol=1&age=2&theme="+window.theme);
		getTime();
	}
	else if(no == 2) {
		// content.innerHTML = "<?php include 'upcoming.php';?>";
	}
}

//Join Test
function jt() {
	var head = document.getElementsByTagName('header')[0];
	var form = document.querySelectorAll("#main #content #first_div div")[0];
	var init = form.innerHTML;
	// console.log(init);
	var code = document.forms["join_test"]["test_code"].value;
	if(code == "" || code.length < 6)
		alert("ERROR: Please Enter a valid Value");
	else {
		var xmlhttp = new XMLHttpRequest();
		var padding = form.style.padding;
		xmlhttp.onreadystatechange = function() {
			form.style.padding = 0;	
			form.style.justifyContent = 'center';
			if(this.readyState < 4) 
				form.innerHTML = '<img src="../Icons/loading1.gif" style="width: 100px; height: 10vh; " alt="loading">';
			if(this.readyState == 4 && this.status == 200) {
				// form.innerHTML = this.responseText;
				console.log(this.responseText);
				var res = JSON.parse(this.responseText);
				console.log(res);
				if(res.error != "null") {
					//Display error
					form.innerHTML = '<p style="color:red; font-weight: bold">'+res.error+'</p>';
				}
				else if(res.message == "live") {
					//Test is live, Start the test
					console.log('Test is live, End Time '+res.endTime);
					select_li(document.querySelectorAll('#main #options ul li')[1], 1);
				}
				else if(res.message == "Joined!") {
					//Test has been joined but isnt live, display message & get on 
					form.innerHTML = '<p style="color: #F3C400">'+res.message+'</p>';
					load_join();
				}
			}
		};
		xmlhttp.open("POST",'join_test.php', true);
		xmlhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
		xmlhttp.send("test_code="+code);
	}
	
}

function load_join() {
	var obj = document.querySelector('#first_div div');
	console.log(obj);
	// obj.innerHTML = response;
	var xmlhttp = new XMLHttpRequest();
	xmlhttp.onreadystatechange = function() {
		if(this.readyState < 4) 
				obj.innerHTML = '<img src="../Icons/loading1.gif" style="width: 100px; height: 10vh; " alt="loading">';
		if(this.readyState == 4 && this.status == 200) {
			obj.innerHTML = this.responseText;
		} 
	}
	setTimeout(function(){ xmlhttp.open("GET",'join_disp.php', true); xmlhttp.send();},3000);
	load_nextTest();
	load_upcoming();
}

function load_nextTest() {
	console.log('entered nexttest');
	var obj = document.querySelectorAll('#first_div div')[1];
	console.log(obj);
	var xmlhttp = new XMLHttpRequest();
	xmlhttp.onreadystatechange = function() {
		if(this.readyState < 4) {
			obj.innerHTML = '<img src="../Icons/loading1.gif" style="width: 100px; height: 10vh; " alt="loading">';
		}
		if(this.readyState == 4 && this.status == 200) {
			obj.innerHTML = this.responseText;
		}
	}
	xmlhttp.open('GET', 'nextTest_disp.php', true);
	xmlhttp.send();

}

function load_upcoming() {
	console.log('entered upcoming');
	var obj = document.querySelector('#content #upcoming');
	console.log(obj.childNodes);

	var xmlhttp = new XMLHttpRequest();
	xmlhttp.onreadystatechange = function() {
		if(this.readyState == 4 && this.status == 200) {
			obj.removeChild(obj.childNodes[2]);
			// obj.removeChild(obj.childNodes[3]);
			obj.innerHTML += this.responseText;
		}
	}
	xmlhttp.open('POST', 'table_disp.php', true);
	xmlhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
	xmlhttp.send('theme='+window.theme);
}

function getTime() {
	console.log('entered');
	var xmlhttp = new XMLHttpRequest();
	xmlhttp.onreadystatechange = function() {
		if(this.status == 200 && this.readyState == 4) {
			if(this.responseText == "-1") {
				//No test going on, do nothing
			}
			else { 
				//there is a test, Display test timer
				var time1 = JSON.parse(this.responseText);
				console.log(time1.endTime);
				document.querySelector('#main #options').innerHTML += '<h1>Time Remaining</h1><br><h1 id="tim"></h1><button name="submit1"><span>Submit</span></button>';
				//Disable all other options

				//Disable Home
				var home = document.querySelectorAll('#main #options nav ul li')[0];
				home.onclick="";
				home.style.cursor="default";
				//Disable Results Analysis
				var res = document.querySelectorAll('#main #options nav ul li')[2];
				res.onclick="";
				res.style.cursor="default";
				var x = setInterval(function() {
					var button=document.querySelector("#main #options button");
					button.addEventListener("click",function(){
							submitf(x);
				});
					var y = startTimer(time1.endTime);
					if(y < 0) {
						clearInterval(x);
					    //request write.php to display result: TO-DO
					    //-----------------------------------------------------------------------//
					    //-----------------------------------------------------------------------//
						//Call write.php to display the marks
					    var marks = 0;
						var tot_q = Number(document.getElementById('count_ques').innerText);
						if(window.theme == 0)
							var options = document.querySelectorAll('.dark_div1 input');
						else
							var options = document.querySelectorAll('.light_div1 input');
						for(var i = 0; i < options.length; i++) {
							if(options[i].hasAttribute('attri')) {
								if(options[i].checked) {
									marks+=1;
								}
							}
						}
						console.log(marks);
						var xmlhttp = new XMLHttpRequest();
						xmlhttp.onreadystatechange = function() {
							if(this.readyState == 4 && this.status == 200) {
								document.querySelector('#main #content').innerHTML = this.responseText;
							}
						}
						xmlhttp.open('POST', 'write.php', true);
						xmlhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
						xmlhttp.send('marks='+marks+'&theme='+window.theme);
						var button = document.querySelector("#main #options button");
						button.parentNode.removeChild(button);
					    //remove the timer
					    var timerArr = document.querySelectorAll('#main #options h1');
					    
					    timerArr[0].parentNode.removeChild(timerArr[0]);
					    timerArr[1].parentNode.removeChild(timerArr[1]);
					    //restore the ability of clicking home and res buttons

					    //Enable Home
					    var home = document.querySelectorAll('#main #options nav ul li')[0];
					    console.log(home);
						// home.onclick="select_li("+home+", 0)";
						// home.onclick="select_li(this, 0)";
						home.addEventListener("click", function() {
							select_li(this, 0);
						});
						home.style.cursor="pointer";
						//Enable Results Analysis
						var res = document.querySelectorAll('#main #options nav ul li')[2];
						console.log(res);
						// res.onclick="select_li("+res+", 2)";
						res.addEventListener("click", function() {
							select_li(this, 2);
						});
						res.style.cursor="pointer";
					}
				}, 1000);
			}
		}
	}
	xmlhttp.open('GET','timecheck.php', true);
	xmlhttp.send();

}

function startTimer(endTime, x) {
	var now = new Date().getTime();
	var distance = endTime*1000 - now;
	// console.log(distance);
	// console.log('entered timer '+distance);
	// var sidebar = document.querySelector('#main #options');
	var days = Math.floor(distance / (1000 * 60 * 60 * 24));
  	var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
  	var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
  	var seconds = Math.floor((distance % (1000 * 60)) / 1000);
    
  	// Output the result 
  	document.querySelector('#main #options #tim').innerHTML = days + "d " + hours + "h "+ minutes + "m " + seconds + "s ";
    return distance;
  // If the count-down is over, perform some functions 
  if (distance <= 0) {
    clearInterval(x);

    //restore the ability of clicking home and res buttons

    //Enable Home
    var home = document.querySelectorAll('#main #options nav ul li')[0];
    console.log(home);
	// home.onclick="select_li("+home+", 0)";
	// home.onclick="select_li(this, 0)";
	home.addEventListener("click", function() {
		select_li(this, 0);
	});
	home.style.cursor="pointer";
	//Enable Results Analysis
	var res = document.querySelectorAll('#main #options nav ul li')[2];
	console.log(res);
	// res.onclick="select_li("+res+", 2)";
	res.addEventListener("click", function() {
		select_li(this, 2);
	});
	res.style.cursor="pointer";
  }
}
function submitf(x){
		if(window.flag == 1)
			return;
		clearInterval(x);
		//request write.php to display result
		var marks = 0;
		var tot_q = Number(document.getElementById('count_ques').innerText);
		if(window.theme == 0)
			var options = document.querySelectorAll('.dark_div1 input');
		else
			var options = document.querySelectorAll('.light_div1 input');
		for(var i = 0; i < options.length; i++) {
			if(options[i].hasAttribute('attri')) {
				if(options[i].checked) {
					marks+=1;
				}
			}
		}
		console.log(marks);
		window.flag = 1;
		//Call write.php to display the marks
		var xmlhttp = new XMLHttpRequest();
		xmlhttp.onreadystatechange = function() {
			if(this.readyState == 4 && this.status == 200) {
				document.querySelector('#main #content').innerHTML = this.responseText;
			}
		}
		xmlhttp.open('POST', 'write.php?', true);
		xmlhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
		xmlhttp.send('marks='+marks+'&theme='+window.theme);
		//remove the timer
		var timerArr = document.querySelectorAll('#main #options h1');
		var button = document.querySelector("#main #options button");
		button.parentNode.removeChild(button);
		timerArr[0].parentNode.removeChild(timerArr[0]);
		
		timerArr[1].parentNode.removeChild(timerArr[1]);
		//restore the ability of clicking home and res buttons

		//Enable Home
		var home = document.querySelectorAll('#main #options nav ul li')[0];
		// home.onclick="select_li("+home+", 0)";
						// home.onclick="select_li(this, 0)";
		home.addEventListener("click", function() {
			select_li(this, 0);
		});
		home.style.cursor="pointer";
		//Enable Results Analysis
		var res = document.querySelectorAll('#main #options nav ul li')[2];
		// res.onclick="select_li("+res+", 2)";
		res.addEventListener("click", function() {
			select_li(this, 2);
		});
		res.style.cursor="pointer";
		
}

//Function to change the theme of the page on click
function changeTheme() {

	//Toggle Header Class
	document.querySelector('header').classList.toggle('light_header');
	document.querySelector('header').classList.toggle('dark_header');

	//Toggle Options Class
	document.querySelector('#main #options').classList.toggle('light_opt');
	document.querySelector('#main #options').classList.toggle('dark_opt');

	//Toggle li class
	var li_list = document.querySelectorAll('#main #options nav ul li')
	for(var i=0; i< li_list.length; i++) {
		li_list[i].classList.toggle('light_li');
		li_list[i].classList.toggle('dark_li');

		//Switch icons
		//childNodes[1] corresponds to the image of the li
		if(li_list[i].childNodes[1].src.match(/dark/g))
			//If dark change source to light
			li_list[i].childNodes[1].src = li_list[i].childNodes[1].src.replace('dark', 'light');
		else
			//If light change source to dark
			li_list[i].childNodes[1].src = li_list[i].childNodes[1].src.replace('light', 'dark');
	}

	//Select the selected li class and toggle it light/dark
	document.querySelectorAll('#main #options nav ul li')[document.querySelector('#main .activeLi').innerText].classList.toggle('light_selected_li');
	document.querySelectorAll('#main #options nav ul li')[document.querySelector('#main .activeLi').innerText].classList.toggle('dark_selected_li');
	document.querySelectorAll('#main #options nav ul li')[document.querySelector('#main .activeLi').innerText].classList.toggle('dark_li');
	document.querySelectorAll('#main #options nav ul li')[document.querySelector('#main .activeLi').innerText].classList.toggle('light_li');
	console.log(document.querySelectorAll('#main #options nav ul li')[document.querySelector('#main .activeLi').innerText]);

	//Toggle Content Class
	document.querySelector('#main #content').classList.toggle('light_cont');
	document.querySelector('#main #content').classList.toggle('dark_cont');

	//Toggle div class
	// var divs = [];
	if(window.theme == 0)
		var divs = document.getElementsByClassName('dark_div');
	else if(window.theme == 1)
		var divs = document.getElementsByClassName('light_div');
	for (var i = divs.length - 1; i >= 0; i--) {
		divs[i].classList.add('dark_div');
		divs[i].classList.add('light_div');
		if(window.theme == 0)
			divs[i].classList.remove('dark_div');
		else if(window.theme == 1)
			divs[i].classList.remove('light_div');
	}

	//Toggle table if exists 
	if(typeof(document.getElementsByClassName('light_table')[0]) != 'undefined' && document.getElementsByClassName('light_table')[0] != null) {
		document.getElementsByClassName('light_table')[0].classList.add('dark_table');
		document.getElementsByClassName('light_table')[0].classList.remove('light_table');
	}
	else if(typeof(document.getElementsByClassName('dark_table')[0]) != 'undefined' && document.getElementsByClassName('dark_table')[0] != null) {
		document.getElementsByClassName('dark_table')[0].classList.add('light_table');
		document.getElementsByClassName('dark_table')[0].classList.remove('dark_table');
	}

	//Toggle dark_div1, light_div1 if exists
	if(typeof(document.getElementsByClassName('light_div1')[0]) != 'undefined' && document.getElementsByClassName('light_div1')[0] != null) {
		var lightDivs = document.getElementsByClassName('light_div1');
		for(var i=lightDivs.length -1; i >= 0; i--) {
			lightDivs[i].classList.add('dark_div1');
			lightDivs[i].classList.add('light_div1');
			if(window.theme == 0)
				lightDivs[i].classList.remove('dark_div1');
			else if(window.theme == 1)
				lightDivs[i].classList.remove('light_div1');
		}
	}
	if(typeof(document.getElementsByClassName('dark_div1')[0]) != 'undefined' && document.getElementsByClassName('dark_div1')[0] != null) {
		var darkDivs = document.getElementsByClassName('dark_div1');
		for(var i=darkDivs.length -1; i >= 0; i--) {
			darkDivs[i].classList.add('dark_div1');
			darkDivs[i].classList.add('light_div1');
			if(window.theme == 0)
				darkDivs[i].classList.remove('dark_div1');
			else if(window.theme == 1)
				darkDivs[i].classList.remove('light_div1');
		}
	}

	loadChart();

	if( window.theme == 0) {
		//Theme is dark, change to Light
		window.theme = 1;
	}
	else {
		//Theme is light, change to Dark
		window.theme = 0;
	}
	console.log('Theme changed to '+window.theme);	
}

function showUpcoming() {
	//onclick of image, reveal the table
	document.querySelector('#main #upcoming h2 img').style.transform = 'rotate(180deg)';
	if(window.upcomingShow == 0) {
		//CSS transitions dont work on height auto
		// document.querySelector('#main #upcoming').style.height = 'auto'; 
		//So this was my second approach
		var finHgt = document.querySelector('#main #upcoming').offsetHeight+ document.querySelector('#main #upcoming table').offsetHeight+20;
		document.querySelector('#main #upcoming').style.height = String(finHgt)+'px';
		window.upcomingShow = 1;
	}
	else {
		document.querySelector('#main #upcoming h2 img').style.transform = 'rotate(-360deg)';
		document.querySelector('#main #upcoming').style.height = '6vh';
		window.upcomingShow = 0;
	}
}

function loadChart() {
	//Fetch data about all tests of the user
	var xmlhttp = new XMLHttpRequest();
	xmlhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
			if(this.responseText == '-1') 
				document.getElementById('allUp').innerHTML+= "<p>Server Error!</p>";
			else {
				//fetch data in JSON
				console.log(this.responseText);
				var history = JSON.parse(this.responseText);
				console.log(history);
				if(history.error == 0) {
					document.getElementById('allUp').innerHTML+= '<p style="color: #F3C400">No tests Completed</p>';
					return;
				}

				//load chart
				google.charts.load('current', {'packages':['corechart']});
				google.charts.setOnLoadCallback(drawChart);

				var subs = ['Test Number'];
				if(history.DBMS.length != 0)
					subs.push('DBMS');
				if(history.Maths.length != 0)
					subs.push('Maths');
				if(history.Quants.length != 0)
					subs.push('Quants');
				if(history.English.length != 0)
					subs.push('English');
				if(history.DSA.length != 0)
					subs.push('DSA');
				var arr = [];
				arr[0] = subs;
				var j = 1;
				for(var i = 0; i < Math.max(history.DBMS.length,history.Maths.length,history.Quants.length,history.English.length,history.DSA.length);i++) {
					var arr1 = [i+1];
					if(history.DBMS.length != 0) {
						if(history.DBMS.length  > i)
							arr1.push(history.DBMS[i]);
						else
							arr1.push(null);
					}
					if(history.Maths.length != 0) {
						if(history.Maths.length  > i)
							arr1.push(history.Maths[i]);
						else
							arr1.push(null);	
					}
					if(history.Quants.length != 0) {
						if(history.Quants.length  > i)
							arr1.push(history.Quants[i]);
						else
							arr1.push(null);
					}
					if(history.English.length != 0) {
						if(history.DBMS.length  > i)
							arr1.push(history.English[i]);
						else
							arr1.push(null);
					}
					if(history.DSA.length != 0) {
						if(history.DBMS.length  > i)
							arr1.push(history.English[i]);
						else
							arr1.push(null);
					}
					arr.push(arr1);
				}
				console.log(arr);
				function drawChart() {
					// var data = google.visualization.arrayToDataTable([
					// ['Test Number', 'DBMS', 'Maths'],
					// ['1',  75,      90],
					// ['2',  80,      82],
					// ['3',  60,       70],
					// ['4',  90,      45]
					// ]);
					var data = google.visualization.arrayToDataTable(arr);
					var bgcolor = 'white';
					var textColor = 'black';
					var gridColor = '#CCC';
					if (window.theme == 0) {
						bgcolor = '#2c2f34';
					    textColor = 'white';
					    gridColor = '#28434E';
					}
					var options = {
					    // title: '',
					    hAxis: {title: 'Test Number',  titleTextStyle: {color: '#F3C400', fontName: 'Roboto', bold:true}, textStyle: {color: textColor, fontName: 'Roboto'}, slantedText: false, gridlines:{color: gridColor}},
					    vAxis: {title: 'Marks', minValue: 0,  textStyle: {color: textColor}, titleTextStyle: {color: '#F3C400', bold: true}, slantedText: false},
					    backgroundColor: bgcolor,
					    legend: {position: 'top', alignment: 'center', textStyle: {color: textColor}},
					    slantedText: false,
					    fontSize: 16
					};
					        
					if(typeof(document.getElementById('chart_div')) != 'undefined' && document.getElementById('chart_div') != null) {
					    var chart = new google.visualization.AreaChart(document.getElementById('chart_div'));
					    chart.draw(data, options);
					}
					        	
				}
			}
		}
	}
	xmlhttp.open('GET', 'Backend/fetchTestHistory.php', true);
	xmlhttp.send();
	
}