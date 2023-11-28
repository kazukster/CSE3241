
<?php

session_start();

//Clear the session variables
$_SESSION = array();

//Destroy the session
session_destroy();

//Redirect to the login page after logout
header("Location: index.php");
exit;
?>
