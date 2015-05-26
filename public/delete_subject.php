<?php  require_once("../includes/session.php"); ?>
<?php  require_once("../includes/db_connection.php"); ?>
<?php  require_once("../includes/functions.php"); ?>
<?php confirm_logged_in(); ?>
<?php 
	$id = $_GET["subject"];
	$current_subject = find_subject_by_id($id,false);

	if(!$current_subject)
	{
		$_SESSION["message"] ="Subject with id {$id} not found";
		redirect_to("manage_content.php");
	}

	$page_set = find_pages_per_subject($id,false);

	if(mysqli_num_rows($page_set) > 0)
	{
		$_SESSION["message"] ="Can't delete a subject having child pages";
		redirect_to("manage_content.php?subject={$id}");	
	}

	$query = "DELETE FROM subjects WHERE id = {$id} LIMIT 1";
	$result = mysqli_query($connection,$query);

	if($result && mysqli_affected_rows($connection) ==1)
	{
		$_SESSION["message"] = "Subject successfully deleted";
		redirect_to("manage_content.php");
	}
	else
	{
		$_SESSION["message"] = "Subject deletion failed";
		redirect_to("manage_content.php?subject={$id}");	
	}
?>	
