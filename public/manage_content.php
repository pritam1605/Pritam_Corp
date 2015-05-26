<?php  require_once("../includes/session.php"); ?>
<?php  require_once("../includes/db_connection.php"); ?>
<?php  require_once("../includes/functions.php"); ?>
<?php $layout_context = "admin" ?>
<?php confirm_logged_in(); ?>
<?php include("../includes/layouts/header.php"); ?>

<?php find_selected_page(false); 		// for navigation bar ?>
<div id="main">
	<div id="navigation">
		<br />
		<a href="admin.php">&laquo; Main Menu</a>
		<br />
		<?php echo navigation($selected_subject_id,$selected_page_id); ?>
		<br />
		<a href="new_subject.php">+ Add New Subject</a>
	</div>
	<div id="page">		
		<?php echo display_message(); ?>
		<?php 	
			if(isset($selected_subject_id))
			{
				$subject=find_subject_by_id($selected_subject_id);
		?>		
			<h2>Manage Subject</h2>
			Subject Menu Name : <?php echo htmlentities($subject['menu_name']); ?>
			<br />
			Position : <?php echo $subject['position']; ?>
			<br />
			Visible : <?php echo $subject['visible'] == 1 ? 'Yes' : 'No'; ?>
			<br /><br />
			<a href="edit_subject.php?subject=<?php echo urlencode($subject['id']) ?>"> Edit Subject</a>
			
			<?php 
				$page_set = find_pages_per_subject($subject['id'],false);
				
				if($page_set && mysqli_num_rows($page_set) > 0)
				{	
			?>
					<br /><br />
					<hr>
					<br />
					Pages included in this subject : <br />
			<?php   
					foreach ($page_set as $page) 
					{
			?>
						<a href="manage_content.php?page=<?php echo urlencode($page['id']) ?>"><?php echo htmlentities($page["menu_name"]) ?> </a>
						<br />
			<?php
					}
			?>
		<?php
			 	}
		?>		
				<br /><br />
				<a href="new_page.php?subject=<?php echo urlencode($selected_subject_id) ?>">+ Add another page</a>
		<?php
			}
			elseif(isset($selected_page_id))
			{
				$page=find_page_by_id($selected_page_id);
		?>
			<h2>Manage Page</h2>
			Page Menu Name : <?php echo htmlentities($page['menu_name']); ?>
			<br />
			Position : <?php echo $page['position']; ?>
			<br />
			Visible : <?php echo $page['visible'] == 1 ? 'Yes' : 'No'; ?>
			<br />
			<div class="view-content">
				Page-Content : <?php echo nl2br(htmlentities($page['content'])); ?>
			</div>

			<br /><br />
			<a href="edit_page.php?page=<?php echo urlencode($selected_page_id)?>">Edit Page</a>
			&nbsp;&nbsp;
			<a href="new_page.php?subject=<?php echo urlencode($page['subject_id']) ?>">+ Add another page</a>
		<?php   
			}
			else
				echo "Please select either a subject or a page.";
		?>			 
	</div>
</div>

<?php include("../includes/layouts/footer.php"); ?>