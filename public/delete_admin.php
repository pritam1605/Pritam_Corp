<?php  require_once("../includes/session.php"); ?>
<?php  require_once("../includes/db_connection.php"); ?>
<?php  require_once("../includes/functions.php"); ?>
<?php confirm_logged_in(); ?>
<?php
	if(!isset($_GET["admin"])) 			// if query string not supplied
		redirect_to("manage_admin.php");	

	$current_admin = find_admin_by_id($_GET["admin"]);

	if(!$current_admin)
	{
		$_SESSION["message"] ="Admin with id {$_GET["admin"]} not found";
		redirect_to("manage_admin.php");
	}

	
	$query = "DELETE FROM admins WHERE id = {$current_admin['id']} LIMIT 1";
	$result = mysqli_query($connection,$query);

	if($result && mysqli_affected_rows($connection) ==1)
	{
		$_SESSION["message"] = "Admin successfully deleted";
		redirect_to("manage_admin.php");
	}
	else
	{
		$_SESSION["message"] = "Admin deletion failed";
		redirect_to("manage_admin.php");	
	}
?>	
