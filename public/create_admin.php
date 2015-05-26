<?php  require_once("../includes/session.php"); ?>
<?php  require_once("../includes/db_connection.php"); ?>
<?php  require_once("../includes/functions.php"); ?>
<?php  require_once("../includes/validation_functions.php"); ?>
<?php confirm_logged_in(); ?>
<?php
	 
	define("MAX_LENGTH", 15);
	
	if(isset($_POST['submit']))
	{	
		$username = isset($_POST["username"]) ? mysql_prep($_POST["username"]) : null;
		$hashed_password = isset($_POST["password"]) ? encrypt_password($_POST["password"]) : null;
		
		// validation
		$required_fields = array("username","password");
		validate_presences($required_fields);

		$max_length_fields = array("username" => MAX_LENGTH,"password" => MAX_LENGTH);
		validate_max_lengths($max_length_fields);

		if(empty($errors))
		{	
			$query = "INSERT INTO admins(username,hashed_password) values('{$username}','{$hashed_password}')";

			$result = mysqli_query($connection,$query);
			if($result)
			{
				$_SESSION["message"] = " Admin Successfully Created.";
				redirect_to("manage_admin.php");
			}
			else
			{
				$_SESSION["message"] = " Admin Creation Failed.";
				redirect_to("manage_admin.php");	
			}
		}
		else
			$_SESSION["errors"] = $errors;
	}
?>
<?php $layout_context = "admin" ?>
<?php include("../includes/layouts/header.php"); ?>
<div id="main">
	<div id="navigation">
		&nbsp;
	</div>
	<div id="page">	
		<?php echo display_message(); ?>
		<?php $errors = check_error(); //checks into the session global variable for the presence of errors?>
		<?php echo display_errors($errors); ?>
			
		<h2>CREATE NEW ADMIN</h2>
		<form action="<?php echo $_SERVER['PHP_SELF']  ?>" method="post">
			<label for="txtusername">Username:</label>
			<input type="text" name="username" id="txtusername"></input>
			<br /><br />
			<label for="txtpassword">Password:</label>
			<input type="password" name="password" id="txtpassword"></input>
			<br /><br />
			<input type="submit" name="submit" value="Submit"></input>
		</form>
		<br />
		<a href="manage_admin.php">Cancel</a>
	</div>
</div>

<?php include("../includes/layouts/footer.php"); ?>