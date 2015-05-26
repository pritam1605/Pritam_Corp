<?php  require_once("../includes/session.php"); ?>
<?php  require_once("../includes/db_connection.php"); ?>
<?php  require_once("../includes/functions.php"); ?>
<?php confirm_logged_in(); ?>
<?php 
	if(!isset($_GET["page"]))
		redirect_to("manage_content.php");
	
	$id = $_GET["page"];
	$current_page = find_page_by_id($id,false);

	if(!$current_page)
	{
		$_SESSION["message"] ="Page with id {$id} not found";
		redirect_to("manage_content.php");
	}

	$query = "DELETE FROM pages WHERE id = {$id} LIMIT 1";
	$result = mysqli_query($connection,$query);

	if($result && mysqli_affected_rows($connection) ==1)
	{
		$_SESSION["message"] = "Page successfully deleted";
		redirect_to("manage_content.php");
	}
	else
	{
		$_SESSION["message"] = "Page deletion failed";
		redirect_to("manage_content.php?subject={$id}");	
	}
?>	
