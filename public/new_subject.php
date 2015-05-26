<?php  require_once("../includes/session.php"); ?>
<?php  require_once("../includes/db_connection.php"); ?>
<?php  require_once("../includes/functions.php"); ?>
<?php find_selected_page(false); 		// for navigation bar ?>
<?php $layout_context = "admin" ?>
<?php confirm_logged_in(); ?>
<?php include("../includes/layouts/header.php"); ?>
<div id="main">
	<div id="navigation">
		<?php echo navigation($selected_subject_id,$selected_page_id); ?>
	</div>
	<div id="page">	
		<?php echo display_message(); ?>
		<?php $errors = check_error(); //checks into the session global variable for the presence of errors?>
		<?php echo display_errors($errors); ?>
			
		<h2>CREATE SUBJECT</h2>
		<form action="create_subject.php" method="post">
			<p>Menu name:<input type="text" name="menu_name" value=""></p>
			<p>Position: 	
			<select name="position">
				<?php
					$subject_set = find_all_subjects(false);
					$subject_count = mysqli_num_rows($subject_set);

					for ($i=1; $i<=$subject_count+1; $i++) 
						echo "<option value=\"{$i}\">{$i}</option>";
				?>				
			</select>
			</p>
			<p>Visible: 
				<input type="radio" name="visible" value="1"/>Yes
				&nbsp;
				<input type="radio" name="visible" value="0"/>No
			</p>
			<input type="submit" name="submit" value="Create Subject"/>
		</form>
		<br />
		<a href="manage_content.php">Cancel</a>
	</div>
</div>

<?php include("../includes/layouts/footer.php"); ?>