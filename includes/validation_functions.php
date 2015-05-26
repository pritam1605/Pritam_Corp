<?php

	$errors  = array();
	
	function has_presence($value)
	{
		return (isset($value) && $value !=="");
	}

	function validate_presences($required_fields)
	{
		global $errors;

		foreach ($required_fields as $field) 
		{	
			$value = isset($_POST[$field]) ? trim($_POST[$field]) : "";
			if(!has_presence($value))
				$errors[$field] = fieldname_as_text($field) . " can't be blank";
		}
	}

	function has_max_length($value,$max)
	{
		return (strlen($value) <= $max);
	}

	function validate_max_lengths($fields_with_max_length)
	{
		global $errors;

		foreach ($fields_with_max_length as $key => $length) 
		{	
			$value = trim($_POST[$key]);
			if(!has_max_length($value,$length))
				$errors[$key] = fieldname_as_text($key) . " exceeds the max length of {$length}";
		}
	}

	function fieldname_as_text($field)
	{	
		// it will replace the underscore symbol with a space
		 $fieldname= str_replace("_", " ",$field);
		 return ucfirst($fieldname);
	}

	function has_presence_in($needle,$haystack)
	{
		return in_array($needle, $haystack);
	}
?>