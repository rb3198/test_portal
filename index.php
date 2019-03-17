<?php 
session_start();
if(isset($_SESSION['userId'])) {
	header("Location: User_pages/dashboard.php");
}
$err = 0;
if(isset($_GET['error'])) {
	if($_GET['error'] == 'notVesId') {
		$err = 1;
	}
	elseif ($_GET['error'] == 'Server') {
		$err = 2;
	}
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
	<title>Test Portal</title>
	<link rel="stylesheet" type="text/css" href="homestyle.css">
	<link href="https://fonts.googleapis.com/css?family=Open+Sans|Roboto" rel="stylesheet">
	<meta name="viewport" content="width=device-width">
	<meta charset="utf-8">
	<meta name="theme-color" content="yellow">
</head>
<body>
	<header>
		<img src="Icons/ves.png" alt="VES logo">
	</header>
	<div id="main">
	<div class="divi">
		<h1>Welcome to<br> Aptitude Test Portal.</h1>
		<button><span>Login</span></button>
		<?php 
			if(isset($_GET['status'])) {
				if($_GET['status'] = 'loggedOut') 
					echo "<p><b>Logged Out Successfully!</b></p>";
			}
			else
				echo "<p><b>Note: Enter VES ID</b></p>";			
		?>
		
	</div>
	<div class="divi">
		
	</div>
</div>
<?php
	if($err == 1) {
		echo '<script>alert("ERROR: Enter VESIT ID"); console.log('.$err.')</script>';
	}
	if($err == 2) {
		echo '<script>alert("ERROR: Server Error"); console.log('.$err.')</script>';
	}
?>
<script type="text/javascript" src="script.js"></script>
</body>
</html>