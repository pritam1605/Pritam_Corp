<?php  require_once("../includes/session.php"); ?>
<?php  require_once("../includes/functions.php"); ?>
<?php 
	//session_start();
	// $_SESSION["admin_id"] = null;
	// $_SESSION["username"] = null;
	// redirect_to("login.php");
	
	$_SESSION = array();

	if(isset($_COOKIE[session_name()]))
	{
		setcookie(session_name(), '', time()-3600, '/');
	}
	session_destroy();
	redirect_to("login.php");
?>