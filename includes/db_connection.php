<?php
	// Database Connection 		.....Step 1
	define("DB_HOST", "localhost");
	define("DB_USER","pritam");
	define("DB_PASSWORD","pritam");
	define("DB_NAME","pritam_corp");

	$connection = mysqli_connect(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME);
	// checking if the connection succeeded
	if(mysqli_connect_errno())
		die("Database connection failed: (" . mysqli_connect_errno() . ")" . mysqli_connect_error());
?>