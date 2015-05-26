<?php  require_once("../includes/session.php"); ?>
<?php  require_once("../includes/db_connection.php"); ?>
<?php  require_once("../includes/functions.php"); ?>
<?php  require_once("../includes/validation_functions.php"); ?>
<?php confirm_logged_in(); ?>
<?php 
	if(!isset($_GET["subject"]))
		redirect_to("manage_content.php");

	find_selected_page(false); 		// for navigation bar

	$current_subject = find_subject_by_id($_GET["subject"],false);
	if(!$current_subject)
	{
		$_SESSION["message"] = "Subject with id {$_GET['subject']} not found.";
		redirect_to("manage_content.php");
	}

	define("MAX_LENGTH", 20);
	
	if(isset($_POST['submit']))
	{	
		$id = $current_subject["id"];
		$menu_name = isset($_POST["menu_name"]) ? mysql_prep($_POST["menu_name"]) : null;
		$position = isset($_POST["position"]) ? (int) ($_POST["position"]) : null;
		$visible = isset($_POST["visible"]) ? (int) ($_POST["visible"]) : null;

		// validation
		$required_fields = array("menu_name","position","visible");
		validate_presences($required_fields);

		$max_length_fields = array("menu_name" => MAX_LENGTH);
		validate_max_lengths($max_length_fields);

		if(empty($errors))
		{
			$query = "Update subjects set menu_name='{$menu_name}' ,position={$position},visible={$visible} Where id = {$id} Limit 1";

			$result = mysqli_query($connection,$query);
			if($result && mysqli_affected_rows($connection) >=0)
			{
				$_SESSION["message"] = " Subject Details Successfully Updated.";
				redirect_to("manage_content.php");
			}
			else
				$message = " Subject Details not updated.";					
		}		
	}
?>
<?php $layout_context = "admin" ?>
<?php include("../includes/layouts/header.php"); ?>
<div id="main">
	<div id="navigation">
		<br />
		<a href="admin.php">&laquo; Main Menu</a>
		<br />
		<?php echo navigation($selected_subject_id,$selected_page_id); ?>
	</div>
	<div id="page">	
		<?php 	
			if(!empty($message))
			{
				echo "<div class=\"message\"> {$message} </div>";
			}
		 ?>
		<?php echo display_errors($errors); ?>
			
		<h2>EDIT SUBJECT : <?php echo $current_subject["menu_name"]; ?></h2>
		<form action="edit_subject.php?subject=<?php echo urlencode($current_subject['id']) ?>" method="post">
			
			<p>Menu name:<input type="text" name="menu_name" value="<?php echo htmlentities($current_subject['menu_name']) ?>"></p>
			<p>Position: 	
			<select name="position">
				<?php
					$subject_set = find_all_subjects(false);
					$subject_count = mysqli_num_rows($subject_set);

					for ($i=1; $i<=$subject_count; $i++) 
					{
						echo "<option value=\"{$i}\" ";
						if($i == $current_subject["position"])
							echo "selected";
						echo " >{$i}</option>";
					}
				?>				
			</select>
			</p>
			<p>Visible: 
				<input type="radio" name="visible" value="1" <?php if($current_subject["visible"]==1) echo "checked" ?>/>Yes
				&nbsp;
				<input type="radio" name="visible" value="0" <?php if($current_subject["visible"]==0) echo "checked" ?>/>No
			</p>
			<input type="submit" name="submit" value="Edit Subject"/>
		</form>
		<br />
		<a href="manage_content.php">Cancel</a>
		&nbsp;
		&nbsp;
		<a href="delete_subject.php?subject=<?php echo urlencode($current_subject['id']) ?>" onclick="return confirm('Are you sure?')">Delete Subject</a>
	</div>
</div>

<?php include("../includes/layouts/footer.php"); ?>