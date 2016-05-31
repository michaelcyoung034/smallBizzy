<?php session_start();
    /****************************************************************************
    * File Name: accountEdit.php
    * Use-case: user account registration
    * Author: Victoria Phillips
    *
	* This page allows users to change their account details
    *****************************************************************************/
	//connect
	$db = mysql_connect("studentdb-maria.gl.umbc.edu","hf28974","hf28974");
	if (!$db)
	{
		exit("Error - could not connect");
	}
	else
	{
		//echo ("Connected".'<br/>');
	}
	
	//select database
	$er = mysql_select_db("hf28974");
	if (!$er)
	{
		exit("Error - could not select");
	}
	else
	{
		//echo ("Selected".'<br/>');
	}

	switch($_POST['instruction'])
	{
		case 'Update Account':
		{
			//check to see that the username is not already in use
			$uname = mysql_real_escape_string(htmlspecialchars($_POST['username']));
			$selectQuery = "SELECT username FROM users WHERE username = '$uname';";
			//run the statement
			$selectResult = mysql_query($selectQuery);
			
			if (!$selectResult)
			{
				print mysql_error();
				exit;
			}
			else
			{
				//echo ("query run".'<br/>');
			}
			//run the statement
			$selectResult = mysql_query($selectQuery);
			
			//no results means the information can be updated
			if (mysql_num_rows($selectResult) == 0)
			{
				updateUserInfo();
			}
			//the username may or may not be the user's current one. it should be allowed.
			else if (mysql_num_rows($selectResult) == 1)
			{
				//the use will be allowed to submit their already-in-use username
				$id = $_SESSION['user_num'];
				$selectQuery = "SELECT username FROM users WHERE user_num = '$id';";
				
				$selectResult = mysql_query($selectQuery);
			
				if (!$selectResult)
				{
					print mysql_error();
					exit;
				}
				else
				{
					//echo ("query run".'<br/>');
				}
				//run the statement
				$select_row_array = mysql_fetch_array($selectResult);
				
				$selectResult = mysql_query($selectResult);
				//username is user's current one and is in use, but will be allowed to submit because they are changing other info
				if ($select_row_array['username'] == $uname)
				{
					updateUserInfo();
				}
				//username is not user's current one and is in use
				else
				{
					?>
						<script type="text/javascript">
							alert("That username is already in use");
						</script>
					<?php
				}
			}
		}
		break;
	}
	
	function updateUserInfo()
	{
		$uid = $_SESSION['user_num'];
		$fname = mysql_real_escape_string(htmlspecialchars($_POST['f_name']));
		$lname = mysql_real_escape_string(htmlspecialchars($_POST['l_name']));
		$uname = mysql_real_escape_string(htmlspecialchars($_POST['username']));
		$address = mysql_real_escape_string(htmlspecialchars($_POST['address']));
		$city = mysql_real_escape_string(htmlspecialchars($_POST['city']));
		$state = mysql_real_escape_string(htmlspecialchars($_POST['state']));
		$zip = mysql_real_escape_string(htmlspecialchars($_POST['zip']));
		$email = mysql_real_escape_string(htmlspecialchars($_POST['email']));
		$phone = mysql_real_escape_string(htmlspecialchars($_POST['phone']));
		
		$updateQuery = "UPDATE users SET f_name='$fname', l_name='$lname', username='$uname', address='$address', city='$city', state='$state', zip='$zip', email='$email', phone='$phone' WHERE (user_num='$uid');";
		//run the statement
		$updateResult = mysql_query($updateQuery);
		
		if (!$updateResult)
		{
			print mysql_error();
			exit;
		}
		else
		{
			//echo ("query run".'<br/>');
		}
	}
	
	//generate the select statement
	$uid = $_SESSION['user_num'];
	$selectQuery = "SELECT user_num, f_name, l_name, username, password, address, city, state, zip, email, phone, account_type FROM users WHERE (user_num='$uid');";
	
	//run the statement
	$selectResult = mysql_query($selectQuery);
	
	if (!$selectResult)
	{
		print mysql_error();
		exit;
	}
	else
	{
		//echo ("query run".'<br/>');
	}
	
	$user_info_row_array = mysql_fetch_array($selectResult);
	//echo mysql_num_rows($selectResult);
	$pass = $user_info_row_array['password'];
	mysql_close();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>Edit Account</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <link href="../styleSheets/masterPageStyle.css" type="text/css" rel="Stylesheet" />
		<script type="text/javascript" src="../validationScripts/updateValidation.js"></script>
    </head>
    <body>
	<?php
		include('../pageHeaderScripts/header.php');
    ?>
        <div id="content">
			<!-- make an input field for each field in the database for users -->
			<form id="editAccount" method="post" action="accountEdit.php" onsubmit = "return accountCheck();">
				<table class="accountInfo">
					<tr>
						<th>First Name</th>
						<td><input type = "text" name="f_name" id="f_name" value="<?php echo $user_info_row_array['f_name'] ?>" /></td>
					</tr>
					<tr>
						<th>Last Name</th>
						<td><input type = "text" name="l_name" id="l_name" value="<?php echo $user_info_row_array['l_name'] ?>" /></td>
					</tr>
					<tr>
						<th>Username</th>
						<td><input type = "text" name="username" id="username" value="<?php echo $user_info_row_array['username'] ?>"  /></td>
						<td> <span id = "msgbox"> </td>
					</tr>
					<tr>
						<th>Address</th>
						<td><input type = "text" name="address" id="address" value="<?php echo $user_info_row_array['address'] ?>" /></td>
					</tr>
					<tr>
						<th>City</th>
						<td><input type = "text" name="city" id="city" value="<?php echo $user_info_row_array['city'] ?>" /></td>
					</tr>
					<tr>
						<th>State</th>
						<td><input type = "text" name="state" id="state" value="<?php echo $user_info_row_array['state'] ?>" /></td>
					</tr>
					<tr>
						<th>ZIP</th>
						<td><input type = "text" name="zip" id="zip" value="<?php echo $user_info_row_array['zip'] ?>" /></td>
					</tr>
					<tr>
						<th>Email</th>
						<td><input type = "text" name="email" id="email" value="<?php echo $user_info_row_array['email'] ?>" /></td>
					</tr>
					<tr>
						<th>Phone</th>
						<td><input type = "text" name="phone" id="phone" value="<?php echo $user_info_row_array['phone'] ?>" /></td>
					</tr>
					<tr>
						<td colspan="2">
							<input type="submit" name="instruction" value="Update Account" id = "submit" onclick = "return checkFormInput();"/>
							<input type = "reset" name = "cancellation" value = "Cancel" id = "reset"/>
						</td>
					</tr>
				</table>
				<br />
			</form>
			<a href="accountView.php">Back to My Account</a>

        </div>
	<?php
		include('../pageFooterScripts/footer.php');
    ?>
    </body>
</html>