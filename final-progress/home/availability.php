<?php
	//Run a database query asking for the given username
	//Program extracts parameter-value from query and looks to see if value is already in the database
	//if there is a record returned, program echoes 'taken' as the response
	//if the recordset is empty, program echoes 'available' as the response
	$db = mysql_connect("studentdb-maria.gl.umbc.edu","hf28974","hf28974");

	if(!$db)
		exit("Error - could not connect to MySQL");

	$er = mysql_select_db("hf28974");
	if(!$er)
		exit("Error - could not select customer database");
	
	#retrieve value of parameter by name 'username' and store the value in the local variable $q
	$q=$_POST["uname"];
	$constructed_query = "SELECT username FROM users WHERE username='$q';";

	#Execute query
	$result = mysql_query($constructed_query);
	if(! $result){
		print("Error - query could not be executed");
		$error = mysql_error();
		print "<p> . $error . </p>";
		exit;
	}

	//Your query has been run at this point. all you need to do now is return 'true' or 'false' depending on the result.
	if (mysql_num_rows($result) == 1) 
	{
		$response="valid";
	}
	else{
		$response = "invalid";
	}
	echo $response;
?>