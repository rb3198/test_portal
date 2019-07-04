//Make home selected by default
window.onload = function() {
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
				console.log(res);
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
		}
	};
	if(no == 0) {
		// content.innerHTML = "<?php include 'home.php';?>";
		xhttp.open("POST", 'home.php', true);
		xhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded')
		xhttp.send("lol=1");
	}
	else if(no == 1) {
		// content.innerHTML = "<?php include 'write.php';?>";
		xhttp.open("POST", 'write.php', true);
		xhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded')
		xhttp.send("lol=1&age=2");
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
	if(code == "" || code.length < 4)
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
				if(this.responseText == "<p> Joined! </p>") {
					load_nextTest();
					load_upcoming();
				}
				load_join(this.responseText);
			}
		};
		xmlhttp.open("POST",'join_test.php', true);
		xmlhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
		xmlhttp.send("test_code="+code);
	}
	
}

function load_join(response) {
	var obj = document.querySelector('#first_div div');
	console.log(obj);
	obj.innerHTML = response;
	var xmlhttp = new XMLHttpRequest();
	xmlhttp.onreadystatechange = function() {
		if(this.readyState < 4) 
				obj.innerHTML = '<img src="../Icons/loading1.gif" style="width: 100px; height: 10vh; " alt="loading">';
		if(this.readyState == 4 && this.status == 200) {
			obj.innerHTML = this.responseText;
		} 
	}
	setTimeout(function(){ xmlhttp.open("GET",'join_disp.php', true); xmlhttp.send();},3000);
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

function load_upcoming(obj) {
	console.log('entered upcoming');
}

function getTime() {
	console.log('entered');
	var xmlhttp = new XMLHttpRequest();
	xmlhttp.onreadystatechange = function() {
		if(this.status == 200 && this.readyState == 4) {
			var time1 = JSON.parse(this.responseText);
			console.log(time1);
		}
	}
	xmlhttp.open('GET','timecheck.php', true);
	xmlhttp.send();
}