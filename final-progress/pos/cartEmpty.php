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
	<title>Empty Cart</title>
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
		echo '<script type="text/javascript">alert("CANNOT CONNECT TO DB: mysql_connect error!")</script>';
		exit;
	}

	$db = mysql_select_db("$dbname", $con);
	if(! $db){
		//echo '.....onclick="removeElement(&quot;div'.$x.'&quot;)"...';
		echo '<script type="text/javascript">alert("CANNOT SELECT DB: mysql_select_db error!")</script>';
		exit;
	}

	// get the q parameter from URL
	$uid = $_REQUEST["eu"];

	if ($uid != NULL) {
		$deleteQ = "DELETE FROM shopping_cart WHERE user_num = $uid";
		$deleteResult = mysql_query($deleteQ);
		if (!$deleteResult){
			print "ERROR DELETE: ";
			print mysql_error();
			exit;
		}
	}
	
?>
</body>
</html>