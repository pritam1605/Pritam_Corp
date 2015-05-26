<?php  require_once("../includes/session.php"); ?>
<?php  require_once("../includes/db_connection.php"); ?>
<?php  require_once("../includes/functions.php"); ?>
<?php  require_once("../includes/validation_functions.php"); ?>
<?php confirm_logged_in(); ?>
<?php 

	if(!isset($_GET["page"]))
		redirect_to("manage_content.php");

	find_selected_page(false); 		// for navigation bar
	
	$current_page = find_page_by_id($_GET["page"],false);
	if(!$current_page)
	{
		$_SESSION["message"] = "Page with id {$_GET['page']} not found.";
		redirect_to("manage_content.php");
	}

	define("MAX_LENGTH", 20);
	
	if(isset($_POST['submit']))
	{	
		$subject_id = $current_page["subject_id"];
		$page_id = $current_page["id"];
		$menu_name = isset($_POST["menu_name"]) ? mysql_prep($_POST["menu_name"]) : null;
		$position = isset($_POST["position"]) ? (int) ($_POST["position"]) : null;
		$visible = isset($_POST["visible"]) ? (int) ($_POST["visible"]) : null;
		$content = isset($_POST["content"]) ? mysql_prep($_POST["content"]) : null;

		// Validation 
		$required_fields = array("menu_name","position","visible","content");
		validate_presences($required_fields);

		$max_length_fields = array("menu_name" => MAX_LENGTH);
		validate_max_lengths($max_length_fields);

		if(empty($errors))
		{
			$query = "Update pages set menu_name='{$menu_name}',position={$position},visible={$visible},content='{$content}' Where id = {$page_id} Limit 1";

			$result = mysqli_query($connection,$query);
			if($result && mysqli_affected_rows($connection) >=0)
			{
				$_SESSION["message"] = " Page Details Successfully Updated.";
				redirect_to("manage_content.php?page={$page_id}");
			}
			else
				$message = " Page Details not updated.";					
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
			
		<h2>EDIT PAGE : <?php echo $current_page["menu_name"]; ?></h2>
		<form action="edit_page.php?page=<?php echo urlencode($current_page['id'])?>" method="post">
			
			<p>Menu name:<input type="text" name="menu_name" value="<?php echo htmlentities($current_page['menu_name']) ?>"></p>
			<p>Position: 	
			<select name="position">
				<?php
					$page_set_result = find_pages_per_subject($current_page["subject_id"],false);
					$page_count = mysqli_num_rows($page_set_result);

					for ($i=1; $i<=$page_count; $i++) 
					{
						echo "<option value=\"{$i}\" ";
						if($i == $current_page["position"])
							echo "selected";
						echo " >{$i}</option>";
					}
				?>				
			</select>
			</p>
			<p>Visible: 
				<input type="radio" name="visible" value="1" <?php if($current_page["visible"]==1) echo "checked" ?>/>Yes
				&nbsp;
				<input type="radio" name="visible" value="0" <?php if($current_page["visible"]==0) echo "checked" ?>/>No
			</p>
			<p>Content:<br/><textarea cols="80" rows="10" name="content"><?php echo htmlentities($current_page['content']) ?></textarea></p>
			<br/ ><br/ >
			<input type="submit" name="submit" value="Edit Page"/>

		</form>
		<br />
		<a href="manage_content.php?page=<?php echo $current_page['id'] ?>">Cancel</a>
		&nbsp;
		&nbsp;
		<a href="delete_page.php?page=<?php echo urlencode($current_page['id']) ?>" onclick="return confirm('Are you sure?')">Delete Page</a>
	</div>
</div>

<?php include("../includes/layouts/footer.php"); ?>