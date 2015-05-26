<?php  require_once("../includes/session.php"); ?>
<?php  require_once("../includes/db_connection.php"); ?>
<?php  require_once("../includes/functions.php"); ?>
<?php $layout_context = "public" ?>
<?php include("../includes/layouts/header.php"); ?>

<?php find_selected_page(true); ?>
<div id="main">
	<div id="navigation">
		<?php echo public_navigation($selected_subject_id,$selected_page_id); ?>
	</div>
	<div id="page">		
		
		<?php 	
			if(isset($selected_page_id))
			{	
				$page=find_page_by_id($selected_page_id,true);
		?>
				<div>
					<h2><?php echo htmlentities($page["menu_name"]); ?></h2>
					<?php echo nl2br(htmlentities($page["content"])); ?>
				</div>			
		<?php   
			}
			else
			{				
		?>		
				<h3>Welcome to Pritam Corp</h3>	 
		<?php 
			}
		?>	
	</div>
</div>

<?php include("../includes/layouts/footer.php"); ?>