<?php  require_once("../includes/session.php"); ?>
<?php  require_once("../includes/db_connection.php"); ?>
<?php  require_once("../includes/functions.php"); ?>
<?php  require_once("../includes/validation_functions.php"); ?>
<?php confirm_logged_in(); ?>
<?php
	
	define("MAX_LENGTH", 20);
	
	if(isset($_POST['submit']))
	{	
		$menu_name = isset($_POST["menu_name"]) ? mysql_prep($_POST["menu_name"]) : null;
		$position = isset($_POST["position"]) ? (int) ($_POST["position"]) : null;
		$visible = isset($_POST["visible"]) ? (int) ($_POST["visible"]) : null;

		// validation
		$required_fields = array("menu_name","position","visible");
		validate_presences($required_fields);

		$max_length_fields = array("menu_name" => MAX_LENGTH);
		validate_max_lengths($max_length_fields);

		if(!empty($errors))
		{
			$_SESSION["errors"] = $errors;
			redirect_to("new_subject.php");
		}

		$query = "INSERT INTO subjects(menu_name,position,visible) values('{$menu_name}',{$position},{$visible})";

		$result = mysqli_query($connection,$query);
		if($result)
		{
			$_SESSION["message"] = " Subject Successfully Created.";
			redirect_to("manage_content.php");
		}
		else
		{
			$_SESSION["message"] = " Subject Creation Failed.";
			redirect_to("new_subject.php");	
		}
	}
	else
	{	
		// probably a GET reqest
		redirect_to("new_subject.php");
	}

?>