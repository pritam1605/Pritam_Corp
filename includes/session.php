<?php session_start(); ?>
<?php
	function display_message()
	{
		if(isset($_SESSION["message"]))
		{
			$output = "<div class=\"message\">";
			$output .= htmlentities($_SESSION["message"]);
			$output .= "</div>";

			$_SESSION["message"] =null;
			return $output;
		}
	}

	function check_error()
	{	
		$errors ="";
		
		if(isset($_SESSION["errors"]))
			$errors = $_SESSION["errors"];

		$_SESSION["errors"] = null;

		return $errors;
	}
?>