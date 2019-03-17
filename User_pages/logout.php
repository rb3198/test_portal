<?php
session_start();
session_unset(); //deletes all session variables
session_destroy(); //destroys all sessions in the website
header("Location: ../index.php?status=loggedOut");
?>