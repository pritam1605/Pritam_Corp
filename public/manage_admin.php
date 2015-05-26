<?php  require_once("../includes/session.php"); ?>
<?php  require_once("../includes/db_connection.php"); ?>
<?php  require_once("../includes/functions.php"); ?>
<?php $layout_context = "admin" ?>
<?php confirm_logged_in(); ?>
<?php include("../includes/layouts/header.php"); ?>
<div id="main">
	<div id="navigation">
		<br />
		<a href="admin.php">&laquo; Main Menu</a>
		<br />
	</div>
	<div id="page">		
		<?php echo display_message(); ?>
		<h2>MANAGE ADMIN</h2>
		<div>
			<table>
				<thead>
					<tr>
						<th style="text-align: left; width: 200px;">Username</th>
						<th colspan="2" style="text-align: left;">Action</th>
					</tr>
				</thead>
				<?php $admins=find_all_admins(); ?>
				<?php 	while($admin=mysqli_fetch_assoc($admins))
						{
				?>
							<tr>
								<td><?php echo htmlentities($admin["username"])?></td>
								<td><a href="edit_admin.php?admin=<?php echo urlencode($admin['id'])?>">Edit</a></td>
								<td><a href="delete_admin.php?admin=<?php echo urlencode($admin['id']) ?>" onclick="return confirm('Are you sure?')">Delete</a></td>
							</tr>
				<?php
						}
				?>
			</table>
		</div>
		<br /><br />
		<a href="create_admin.php">+ Create New Admin</a>
	</div>
<?php include("../includes/layouts/footer.php"); ?>