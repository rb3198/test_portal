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
			content.innerHTML = '<img src="../Icons/ves.png">';
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
		xhttp.send("lol=1");
	}
	else if(no == 2) {
		// content.innerHTML = "<?php include 'upcoming.php';?>";
	}
}