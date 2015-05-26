<?php  require_once("../includes/session.php"); ?>
<?php  require_once("../includes/db_connection.php"); ?>
<?php  require_once("../includes/functions.php"); ?>
<?php  require_once("../includes/validation_functions.php"); ?>
<?php confirm_logged_in(); ?>
<?php
	 
	if(!isset($_GET["subject"]))
	{
		$_SESSION["message"] = "Orphan page can not exist";
		redirect_to("manage_content.php");
	}
	$subject_id = (int) $_GET["subject"];
	$subject_details = find_subject_by_id($subject_id,false);

	define("MAX_LENGTH", 20);
	
	if(isset($_POST['submit']))
	{	
		$menu_name = isset($_POST["menu_name"]) ? mysql_prep($_POST["menu_name"]) : null;
		$position = isset($_POST["position"]) ? (int) ($_POST["position"]) : null;
		$visible = isset($_POST["visible"]) ? (int) ($_POST["visible"]) : null;
		$content = isset($_POST["content"]) ? mysql_prep($_POST["content"]) : null;
		// validation
		$required_fields = array("menu_name","position","visible","content");
		validate_presences($required_fields);

		$max_length_fields = array("menu_name" => MAX_LENGTH);
		validate_max_lengths($max_length_fields);

		if(empty($errors))
		{	
			$query = "INSERT INTO pages(subject_id,menu_name,position,visible,content) values({$subject_id},'{$menu_name}',{$position},{$visible},'{$content}')";

			$result = mysqli_query($connection,$query);
			if($result)
			{
				$_SESSION["message"] = " Page Successfully Created.";
				redirect_to("manage_content.php?subject={$subject_id}");
			}
			else
			{
				$_SESSION["message"] = " Page Creation Failed.";
				redirect_to("new_page.php?subject={$subject_id}");	
			}
		}
		else
			$_SESSION["errors"] = $errors;
	}
?>
<?php find_selected_page(false); 		// for navigation bar ?>
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
		<?php echo display_message(); ?>
		<?php $errors = check_error(); //checks into the session global variable for the presence of errors?>
		<?php echo display_errors($errors); ?>
			
		<h2>ADD NEW PAGE TO SUBJECT : <?php echo strtoupper($subject_details["menu_name"])  ?></h2>
		<form action="new_page.php?subject=<?php echo urlencode($subject_id) ?>" method="post">
			<p>Menu name:<input type="text" name="menu_name" value=""></p>
			<p>Position: 	
			<select name="position">
				<?php
					$page_set_result = find_pages_per_subject($subject_id,false);
					$page_count = mysqli_num_rows($page_set_result);

					for ($i=1; $i<=$page_count+1; $i++) 
						echo "<option value=\"{$i}\">{$i}</option>";
				?>				
			</select>
			</p>
			<p>Visible: 
				<input type="radio" name="visible" value="1"/>Yes
				&nbsp;
				<input type="radio" name="visible" value="0"/>No
			</p>
			<p>Content:<br /><textarea cols="80" rows="10" name="content" value=""></textarea></p>
			<br/ ><br/ >
			<input type="submit" name="submit" value="Create Page"/>
		</form>
		<br />
		<a href="manage_content.php">Cancel</a>
	</div>
</div>

<?php include("../includes/layouts/footer.php"); ?>