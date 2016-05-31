<?php session_start(); ?>
<!--
	/****************************************************************************
	* File Name: cartEmpty.php
	* Use-case: customer view inventory
	* Author: Kayoung Kim
	* E-mail: kayoung2@umbc.edu
	*
	* This php file is only via emptyCart javascript function in kayoung2.js
	* Simply it delete all the rows where user_num equals to $uid
	*
	*****************************************************************************/
-->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Update Cart</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
</head>

<body>
<?php


	$servername = "studentdb-maria.gl.umbc.edu";
	$username = "hf28974";
	$password = "hf28974";
	$dbname = "hf28974";

  
	//DB connect info and SELECT Query
	$con = mysql_connect("$servername", "$username", "$password");
	if (!$con){
		print mysql_error();
		exit;
	}

	$db = mysql_select_db("$dbname", $con);
	if(! $db){
		print mysql_error();
		exit;
	}

	// get the q parameter from URL
	$newQ = $_REQUEST["q"];
	$user_num = $_REQUEST["u"];
	$item_num = $_REQUEST["i"];

	if ( ($user_num != NULL) && ($user_num != NULL) && ($user_num != NULL) ){
		$updateQ = "UPDATE shopping_cart SET quantity=$newQ WHERE (user_num = $user_num) AND (item_num = $item_num)";
		$updateResult = mysql_query($updateQ);
		if (!$updateResult){
			print "ERROR UPDATE3: ";
			print mysql_error();
			exit;
		}
	}


	
?>
</body>
</html>