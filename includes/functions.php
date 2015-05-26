<?php
	
	define("SALT_LENGTH",22);

	function confirm_query_result($result_set)
	{
		if(!$result_set)
			die("Database query failed!!!");
	}

	function redirect_to($location)
	{
		header("Location: " . $location);
		exit;
	}

	function mysql_prep($string)
	{
		global $connection;
		$escape_string = mysqli_real_escape_string($connection,$string);
		return $escape_string;
	}

	function find_all_admins()
	{
		global $connection;

		$query = "SELECT * FROM admins ORDER BY username ASC";
		$admin_set = mysqli_query($connection,$query);
		confirm_query_result($admin_set);

		return $admin_set;
	}

	function find_admin_by_id($admin_id)
	{
		global $connection;
		$safe_admin_id = mysql_prep($admin_id);

		$query = "SELECT * FROM admins WHERE id ={$safe_admin_id} LIMIT 1";
		$admin_set = mysqli_query($connection,$query);
		confirm_query_result($admin_set);
		if($admin = mysqli_fetch_assoc($admin_set))
			return $admin;

		return null;
	}

	function find_admin_by_username($username)
	{
		global $connection;
		$safe_admin_username = mysql_prep($username);

		$query = "SELECT * FROM admins WHERE username ='{$safe_admin_username}' LIMIT 1";
		$admin_set = mysqli_query($connection,$query);
		confirm_query_result($admin_set);
		if($admin = mysqli_fetch_assoc($admin_set))
			return $admin;

		return null;
	}

	function find_all_subjects($public=true)
	{
		global $connection;

		$query = "SELECT * FROM subjects ";

		if($public)
			$query .= " WHERE visible = 1 ";

		$query .= "ORDER BY position ASC";
		$subject_set = mysqli_query($connection,$query);
		confirm_query_result($subject_set);

		return $subject_set;
	}

	function find_subject_by_id($subject_id,$public=true)
	{
		global $connection;
		$safe_subject_id = mysql_prep($subject_id);

		$query = "SELECT * FROM subjects WHERE id = {$safe_subject_id}";
		if($public)
			$query .=" AND visible=1";
		
		$query .= " LIMIT 1";
		$result = mysqli_query($connection,$query);
		confirm_query_result($result);

		if($subject_details = mysqli_fetch_assoc($result))
			return $subject_details;
		else
			return null;
	}

	function find_pages_per_subject($subject_id,$public=true)
	{
		global $connection;
		$safe_subject_id = mysql_prep($subject_id);

		$query = "SELECT * FROM pages WHERE subject_id = {$safe_subject_id} ";
		if($public)
			$query .= " AND visible = 1 ";
		$query .= " ORDER BY position ASC";

		$page_set = mysqli_query($connection,$query);
		confirm_query_result($page_set);

		return $page_set;
	}

	function find_page_by_id($page_id,$public=true)
	{
		global $connection;
		$safe_page_id = mysql_prep($page_id);

		$query = "SELECT * FROM pages WHERE id = {$safe_page_id} ";
		if($public)
			$query .=" AND visible=1 ";

		$query .= " LIMIT 1";
		$result = mysqli_query($connection,$query);
		confirm_query_result($result);

		if($page_details = mysqli_fetch_assoc($result))
			return $page_details;
		else
			return null;
	}

	function navigation($subject_id,$page_id)
	{
		$subject_set = find_all_subjects(false);

		$output = "<ul class='subjects'>";
		while($subject = mysqli_fetch_assoc($subject_set))
		{
			$output .= "<li";
			if($subject_id == $subject['id'])
				$output .= " class='selected'";
			else
				$output .= ">" ;

			$output .= "<a href=\"manage_content.php?subject=";
			$output .= urlencode($subject['id']);
			$output .= "\">";
			$output .= htmlentities($subject['menu_name']);
			$output .= "</a>";
			
			$page_set = find_pages_per_subject($subject['id'],false);
			
			$output .= "<ul class=\"pages\">";
			while($page=mysqli_fetch_assoc($page_set))
			{
				$output .= "<li";
				
				if($page_id == $page['id'])
					$output .= " class=\"selected\"";
				else
					$output .= ">";
					
				$output .= "<a href=\"manage_content.php?page=";
				$output .= urlencode($page['id']);
				$output .= "\">";
				$output .= htmlentities($page['menu_name']);
				$output .= "</a></li>";
			}
			$output .= "</ul>";

			mysqli_free_result($page_set);
			$output .= "</li>";
		}
		mysqli_free_result($subject_set);
		$output .= "</ul>";

		return $output;
	}

	function public_navigation($subject_id,$page_id)
	{	
		//$subject_id = isset($subject_id) ? $subject_id : 0;
		//$page_id = isset($page_id) ? $page_id : 0;

		$subject_set = find_all_subjects(true);

		$output = "<ul class='subjects'>";
		while($subject = mysqli_fetch_assoc($subject_set))
		{
			$output .= "<li";
			if($subject_id == $subject['id'])
				$output .= " class='selected'";
			else
				$output .= ">" ;

			$output .= "<a href=\"index.php?subject=";
			$output .= urlencode($subject['id']);
			$output .= "\">";
			$output .= htmlentities($subject['menu_name']);
			$output .= "</a>";
			
			$page_set = find_pages_per_subject($subject['id'],true);
			//$subject_for_select_page = find_page_by_id($page_id);

			if($subject_id==$subject['id'])
			{
				$output .= "<ul class=\"pages\">";
				while($page=mysqli_fetch_assoc($page_set))
				{
					$output .= "<li";
					
					if($page_id == $page['id'])
						$output .= " class=\"selected\"";
					else
						$output .= ">";
						
					$output .= "<a href=\"index.php?page=";
					$output .= urlencode($page['id']);
					$output .= "\">";
					$output .= htmlentities($page['menu_name']);
					$output .= "</a></li>";
				}
				$output .= "</ul>";
			}
				mysqli_free_result($page_set);
				$output .= "</li>";
		}
		mysqli_free_result($subject_set);
		$output .= "</ul>";

		return $output;
	}

	function find_selected_page($public=true)
	{	
		global $selected_subject_id;
		global $selected_page_id;

		/*$selected_subject_id = isset($_GET['subject']) ? $_GET['subject'] : null;
		$selected_page_id = isset($_GET['page']) ? $_GET['page'] : null;*/
		if(isset($_GET['subject']))
		{	
			$selected_subject_id= $_GET['subject'];
			if($public)
				$selected_page_id =find_default_page_for_subject($selected_subject_id);
			else
				$selected_page_id =null;
		}
		elseif(isset($_GET['page']))
		{	
			$selected_subject_id= null;
			$selected_page_id =$_GET['page'];
			$current_page = find_page_by_id($selected_page_id,true);
		}
		else
		{
			$selected_subject_id= null;
			$selected_page_id = null;
		}
	}

	function find_default_page_for_subject($subject_id)
	{
		$page_details = find_pages_per_subject($subject_id,true);

		if($default_page = mysqli_fetch_assoc($page_details))
			return $default_page["id"];

		return null;
	}

	function display_errors($errors)
	{
		$output ="";
		if(!empty($errors))
		{
			$output .= "<div class=\"error\">";
			$output .= "<p>Please correct the following error(s): </p>";
			$output .= "<ul>";

			foreach ($errors as $error) 
			{
				$output .= "<li>";
				$output .= htmlentities($error);
				$output .="</li>";
			}

			$output .= "</ul>";
			$output .= "</div>";
		}
		return $output;
	}

	function encrypt_password($password)
	{
		$format = "$2y$10$";  // tells PHP to use Blowfih with a "cost" of 10
		$salt = generate_salt(SALT_LENGTH);
		$format_and_salt = $format.$salt;
		$hash = crypt($password,$format_and_salt);

		return $hash;
	}

	function generate_salt($length)
	{	
		// not 100% unique, not 100% random, but good enough for a salt
		// md5 returns 32 characters
		$unique_random_string = md5(uniqid(mt_rand(),true));

		// valid characters for a salt are [a-zA-Z0-9./]
		$base64_string = base64_encode($unique_random_string);

		// but not '+' which is valid in base64 encoding
		$modified_base64_string = str_replace('+','.',$base64_string);

		// truncate string to the correct length
		$salt = substr($modified_base64_string,0,$length);

		return $salt;
	}

	function check_password($password,$existing_hash)
	{
		$hash = crypt($password,$existing_hash);
		if($hash===$existing_hash)
			return true;
		return false;
	}

	function check_login($username,$password)
	{
		$admin = find_admin_by_username($username);
		if($admin)
		{
			$password_match=check_password($password,$admin["hashed_password"]);
			if($password_match)
				return $admin;
			return false;
		}
		else
			return false;
	}

	function logged_in()
	{
		return isset($_SESSION["admin_id"]);
	}
	function confirm_logged_in()
	{
		if(!logged_in())
			redirect_to("login.php");
	}

?>