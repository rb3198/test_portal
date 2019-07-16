<?php
date_default_timezone_set('Asia/Kolkata');
//Commenting the following lines for myself. Uncomment on yours 
// require '..\vendor\autoload.php';
// use \League\OAuth2\Client\Provider\Google;
// // use vendor\league\oauth2-google;
// $hitesh='http://localhost/test2/test_portal/User_pages/dashboard.php';
// $ronit='http://localhost:4433/scripts/Side%20Project/User_pages/dashboard.php';
// $ronit1 = 'http://localhost/scripts/Side%20Project/User_pages/dashboard.php';
// $anirudh = 'http://localhost:81/test_portal/User_pages/dashboard.php';
session_start();
// if(!isset($_SESSION['userId'])) {
	
// 	//contact google
// 	$provider = new Google ([
// 		'clientId' => '714746811221-eet31prm86rj325hoafsht7alabauv1a.apps.googleusercontent.com',
// 		'clientSecret' => 'gQUA31WhyhdnbrQxe9YsI4uh',
// 		'redirectUri' => $ronit1
// 	]);

// 	$provider->setHttpClient(new GuzzleHttp\Client(['verify' => 'C:\Program Files\OpenSSL-Win64\certs\ca-cert.pem']));

// 	if (!empty($_GET['error'])) {

//     // Got an error, probably user denied access
//     exit('Got error: ' . htmlspecialchars($_GET['error'], ENT_QUOTES, 'UTF-8'));

// 	} elseif (empty($_GET['code'])) {

// 	    // If we don't have an authorization code then get one
// 	    $authUrl = $provider->getAuthorizationUrl();
// 	    $_SESSION['oauth2state'] = $provider->getState();
// 	    header('Location: ' . $authUrl);
// 	    exit;

// 	} elseif (empty($_GET['state']) || ($_GET['state'] !== $_SESSION['oauth2state'])) {

// 	    // State is invalid, possible CSRF attack in progress
// 	    unset($_SESSION['oauth2state']);
// 	    exit('Invalid state');

// 	} else {

// 	    // Try to get an access token (using the authorization code grant)
// 	    $token = $provider->getAccessToken('authorization_code', [
// 	        'code' => $_GET['code']
// 	    ]);

// 	    // Optional: Now you have a token you can look up a users profile data
// 	    try {
// 	        // We got an access token, let's now get the owner details
// 	        $ownerDetails = $provider->getResourceOwner($token);
// 	        // Compare email from Google & from Database and check if theres a match
// 	        // If not, reject and get back to the front page
// 	        $email_goog = $ownerDetails->getEmail(); //Email from google
// 	        require('../connect.php');
// 	        $sql = 'SELECT * FROM users WHERE email = ?';
// 	        $stmt = mysqli_stmt_init($conn);
// 	        if(!mysqli_stmt_prepare($stmt, $sql)) {
// 	        	session_unset();
// 	        	session_destroy();
// 	        	header("Location: ../index.php?error=Server");
// 	        }
// 	        else {
// 	        	mysqli_stmt_bind_param($stmt, "s", $email_goog);
// 	        	mysqli_stmt_execute($stmt);
// 				$res = mysqli_stmt_get_result($stmt);
// 				if($res->num_rows > 0)
// 					while($row = $res->fetch_assoc()) {
// 						$_SESSION['userRollNo'] = $row['id'];
// 						echo '<p style="height:0px; width:0px" id="userRollNo">'.$_SESSION['userRollNo'].'</p>';
// 					}
// 	        	mysqli_stmt_store_result($stmt);
// 	        	$row = mysqli_stmt_num_rows($stmt);
// 	        	if($row < 1 ) {
// 	        		mysqli_stmt_close($stmt);
// 	        		mysqli_close($conn);
// 	        		session_unset();
// 	        		session_destroy();
// 	        		header('Location: ../index.php?error=notVesId');
// 	        	}
// 	        	else {
// 	        		// Use these details to create a new profile
// 			        $_SESSION['userImg'] = $ownerDetails->getAvatar();
// 			        //Set Session
// 			        $_SESSION['userId'] = $ownerDetails->getId();
			
// 			        $_SESSION['userEmail'] = $email_goog;
// 			        $_SESSION['username'] = $ownerDetails->getName();
// 			        $_SESSION['userFN'] = $ownerDetails->getFirstName();
// 	        	}
// 	        }
	        //mysqli_stmt_close($stmt);
// 			mysqli_close($conn);
	        

// 	    } catch (Exception $e) {

// 	        // Failed to get user details
// 	        exit('Something went wrong: ' . $e->getMessage());

// 	    }

// 	    // Use this to interact with an API on the users behalf
// 	    // echo $token->getToken();

// 	    //  Use this to get a new access token if the old one expires
// 	    //echo $token->getRefreshToken();

// 	    // Unix timestamp at which the access token expires
// 	     // echo $token->getExpires();

// 	}
// }
//end here
//comment the following:
$_SESSION['userImg'] = 'https://lh3.googleusercontent.com/-qS2CLioSf3I/AAAAAAAAAAI/AAAAAAAAAAA/ACHi3rcEbkKpH4InJ3tHiFZRFzhlWHyvCQ.CMID/s192-c/photo.jpg';
//Set Session
$_SESSION['userId'] = 'ronit.bhatia';
$_SESSION['userRollNo'] = 13;
echo '<p style="height:0px; width:0px" id="userRollNo">'.$_SESSION['userRollNo'].'</p>';
$_SESSION['userEmail'] = '2016.ronit.bhatia@ves.ac.in';
$_SESSION['username'] = 'RONIT BHATIA';
$_SESSION['userFN'] = 'Ronit';
//end here
$_SESSION['liveTestEndTime'] = -1;
$_SESSION['liveTestStartTime'] = -1;
$_SESSION['liveTestId'] = -1
//fetch the start time of the latest test that the user is registered to

?>
<!DOCTYPE html>
<html lang="en">
<head><?php
	echo '<title>Dashboard: '.$_SESSION['username'].'</title>';
	?>
	<link rel="stylesheet" type="text/css" href="dashstyle.css">
	<link href="https://fonts.googleapis.com/css?family=Open+Sans|Roboto" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="homestyle.css">
	<meta name="viewport" content="width=device-width">
	<meta charset="utf-8">
	<meta name="description" content="VESIT Aptitude Test Portal">
</head>
<body>
	<header class="dark_header">
		<img src="../Icons/ves_drk.png" alt="VESIT">
		<div style="display: flex; justify-content: space-between; width: 20%; min-width: min-content;">
			<!-- Switch to change the theme of the page -->
			<label class="switch">
		  		<input type="checkbox" checked onclick="changeTheme()">
			  	<span class="slider round"></span>
			</label>
			<div onclick="window.location.href = 'logout.php'" style="margin-right: 5%">
				<p>Logout</p>
			</div>
			<!-- <img src="../Icons/dark/logout.png" alt="Logout"> -->
		</div>
	</header>
	<div id="main">
		<p class="activeLi" style="height: 0; width: 0">5</p>
		<p id="isTestLive" style="height: 0; width: 0">-1</p>
		<div id="options" class="dark_opt">
			<div class="dash_head">
				<!-- Profile Picture -->
				<div>
				<?php 
				echo '<img src="'.$_SESSION['userImg'].'" alt="Profile Pic"/>';
				echo '</div>';
				// <!-- Welcome Message -->
				echo '<div><p>'.$_SESSION['username'].'</p></div>';
				?>
			</div>
			<nav>
				<ul>
					<li class="dark_li" onmouseover="change_img_color(this)" onmouseout="change_back(this)" onclick="select_li(this, 0)">
						<img src="../Icons/dark/home.png" alt="Home">
						<p>Home</p>
					</li>
					<li class="dark_li" onmouseover="change_img_color(this)" onmouseout="change_back(this)" onclick="select_li(this, 1)">
						<img src="../Icons/dark/write.png" alt="Write">
						<p>Write a Test</p>
					</li>
					<li class="dark_li" onmouseover="change_img_color(this)" onmouseout="change_back(this)" onclick="select_li(this, 2)">
						<img src="../Icons/dark/upcoming.png" alt="Results">
						<p>Results Analysis</p>
					</li>
				</ul>
			</nav>
		</div>
		<div id="content" class="dark_cont">
		</div>
		</div>
	</div>
	<script type="text/javascript" src="dashscript.js"></script>
	<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

</body>
</html>