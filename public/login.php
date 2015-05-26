<?php  require_once("../includes/session.php"); ?>
<?php  require_once("../includes/db_connection.php"); ?>
<?php  require_once("../includes/functions.php"); ?>
<?php  require_once("../includes/validation_functions.php"); ?>
<?php
	 
	$username = "";
	
	if(isset($_POST['submit']))
	{	
		$username = isset($_POST["username"]) ? ($_POST["username"]) : null;
		$password = isset($_POST["password"]) ? ($_POST["password"]) : null;
		
		// validation
		$required_fields = array("username","password");
		validate_presences($required_fields);

		if(empty($errors))
		{	
			$login_result = check_login($username,$password);
			if($login_result)
			{
				$_SESSION["admin_id"] = $login_result["id"];
				$_SESSION["username"] = $login_result["username"];
				redirect_to("admin.php");
			}
			else
				$_SESSION["message"] = "Username/Password do not match";
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
			
		<h2>LOGIN</h2>
		<form action="<?php echo $_SERVER['PHP_SELF']  ?>" method="post">
			<label for="txtusername">Username:</label>
			<input type="text" name="username" id="txtusername" value="<?php echo htmlentities($username) ?>"></input>
			<br /><br />
			<label for="txtpassword">Password:</label>
			<input type="password" name="password" id="txtpassword" value=""></input>
			<br /><br />
			<input type="submit" name="submit" value="Submit"></input>
		</form>
	</div>
</div>

<?php include("../includes/layouts/footer.php"); ?>