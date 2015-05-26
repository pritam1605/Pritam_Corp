<?php  require_once("../includes/session.php"); ?>
<?php  require_once("../includes/db_connection.php"); ?>
<?php  require_once("../includes/functions.php"); ?>
<?php  require_once("../includes/validation_functions.php"); ?>
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

	define("MAX_LENGTH", 15);
	
	if(isset($_POST['submit']))
	{	

		$admin_id = (int)($_GET["admin"]);
		$username = isset($_POST["username"]) ? mysql_prep($_POST["username"]) : null;
		$hashed_password = isset($_POST["password"]) ? encrypt_password($_POST["password"]) : null;

		// validation
		$required_fields = array("username","password");
		validate_presences($required_fields);

		$max_length_fields = array("username" => MAX_LENGTH,"password" => MAX_LENGTH);
		validate_max_lengths($max_length_fields);

		if(empty($errors))
		{	
			$query = "UPDATE admins SET username='{$username}',hashed_password='{$hashed_password}' WHERE id = {$current_admin['id']} LIMIT 1";

			$result = mysqli_query($connection,$query);
			if($result && mysqli_affected_rows($connection) >= 0)
			{
				$_SESSION["message"] = " Admin Successfully Updated.";
				redirect_to("manage_admin.php");
			}
			else
			{
				$_SESSION["message"] = " Admin Updation Failed.";
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
		<br />
		<a href="admin.php">&laquo; Main Menu</a>
		<br />
	</div>
	<div id="page">	
		<?php echo display_message(); ?>
		<?php $errors = check_error(); //checks into the session global variable for the presence of errors?>
		<?php echo display_errors($errors); ?>
			
		<h2>EDIT ADMIN</h2>
		<form action="edit_admin.php?admin=<?php echo $current_admin['id'] ?>" method="post">
			<label for="txtusername">Username:</label>
			<input type="text" name="username" id="txtusername" value="<?php echo htmlentities($current_admin['username']) ?>"></input>
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