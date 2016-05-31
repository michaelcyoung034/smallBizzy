<?php session_start();

    /****************************************************************************
    * File Name: accountCreate.php
    * Use-case: user account registration
    * Author: Victoria Phillips
    *
	* This page allows users to register themselves or business with SmallBizzy. From here, they will be able to login and use the site's features
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
	//if the user must retry a login, this variable will change to true. It will allow the form to fill in the fields with the data from the previous page submission
	$retry = false;
	
	switch($_POST['instruction'])
	{
		case 'Create Account':
		{
			$uname = $_POST['username'];
			$query = "SELECT username FROM users WHERE username = '$uname';";
								
			//execute query
			$result = mysql_query($query);
					
			if (!$result)
			{
				print mysql_error();
				exit;
			}
			else
			{
				//echo ("select query run");
			}
			
			//store the resulting query
			$row_array = mysql_fetch_array($result);
			
			//the user cannot register if the user_num is already taken. a 0-row result means the user_num is available.
			if (mysql_num_rows($result) == 0)
			{
				$fname = mysql_real_escape_string(htmlspecialchars($_POST['f_name']));
				$lname = mysql_real_escape_string(htmlspecialchars($_POST['l_name']));
				$uname = mysql_real_escape_string(htmlspecialchars($_POST['username']));
				$addr = mysql_real_escape_string(htmlspecialchars($_POST['address']));
				$city = mysql_real_escape_string(htmlspecialchars($_POST['city']));
				$state = mysql_real_escape_string(htmlspecialchars($_POST['state']));
				$zip = mysql_real_escape_string(htmlspecialchars($_POST['zip']));
				$email = mysql_real_escape_string(htmlspecialchars($_POST['email']));
				$phone = mysql_real_escape_string(htmlspecialchars($_POST['phone']));
				$pass = mysql_real_escape_string(htmlspecialchars($_POST['uPassword']));
				$acct = mysql_real_escape_string(htmlspecialchars($_POST['account_type']));
				$date = date("Y-m-d");
				
				$query = "INSERT INTO users (f_name, l_name, username, address, city, state, zip, email, phone, password, account_type, date_joined) VALUES ('$fname', '$lname', '$uname', '$addr', '$city', '$state', '$zip', '$email', '$phone', '$pass', '$acct', '$date');";
				
				//execute query
				$result = mysql_query($query);
						
				if (!$result)
				{
					print mysql_error();
					exit;
				}
				else
				{
					//echo ("select query run");
				}
				?>
					<script type="text/javascript">
						alert("You are now registered");
					</script>
				<?php
			}
			//the user_num already exists
			else if (mysql_num_rows($result) == 1)
			{
				?>
					<script type="text/javascript">
						alert("Sorry, that user_num is already taken");
					</script>
				<?php
				$retry = true;
			}
		}
	}
	mysql_close();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>Create Account</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <link href="../styleSheets/masterPageStyle.css" type="text/css" rel="Stylesheet" />
		<script type="text/javascript" src="../validationScripts/acctCheck.js"></script>
		<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/prototype/1.6.0.3/prototype.js"></script>
		<script src="http://ajax.googleapis.com/ajax/libs/scriptaculous/1.8.2/scriptaculous.js" type="text/javascript"> </script>
    </head>
    <body>
	<?php
		include('../pageHeaderScripts/header.php');
    ?>
        <div id="content">
			<!-- make an input field for each field in the database for users -->
			<form id="createAccount" method="post" action="accountCreate.php">
				<table class="accountInfo">
					<tr>
						<td>
							First Name:
						</td>
						<td>
							<input type = "text" name = "f_name" id = "f_name" />
						</td>
					</tr>
					<tr>
						<td>
							Last Name:
						</td>
						<td>
							<input type = "text" name = "l_name" id = "l_name"/>
						</td>
					</tr>
					<tr>
						<td id = "remember1" onmouseover = "remember();" onmouseout = "normal();">
							username:
						</td>
						<td>
							<input type = "text" name = "username" id = "username" onblur = "checkUName();"/>
							<span id = "msgbox"></span>
						</td>
					</tr>
					<tr>
						<td id = "remember2" onmouseover = "remember();" onmouseout = "normal();">
							password:
						</td>
						<td>
							<input type = "password"  name = "uPassword" id = "uPassword"/>
						</td>
					</tr>
					<tr>
						<td>
							address:
						</td>
						<td>
							<input type = "text" name = "address" id = "address"/>
						</td>
					</tr>
					<tr>
						<td>
							city:
						</td>
						<td>
							<input type = "text" name = "city" id = "city"/>
						</td>
					</tr>
					<tr>
						<td>
							state:
						</td>
						<td>
							<input type = "text" maxlength = "2" name = "state" id = "state"/>
						</td>
					</tr>
					<tr>
						<td>
							zip:
						</td>
						<td>
							<input type  = "text" name = "zip" id = "zip"/>
						</td>
					</tr>
					<tr>
						<td>
							email address:
						</td>
						<td>
							<input type = "text" name = "email" id = "email" />
						</td>
					</tr>
					<tr>
						<td>
							phone Number:
						</td>
						<td>
							<input type = "text" name = "phone" id = "phone" />
						</td>
					</tr>
					<tr>
						<td>
							Desired Account Type:
						</td>
						<td>
							<select name="account_type" id="account_type">
								<option value="seller">Seller</option>
								<option value="shopper">Shopper</option>
							</select>
						</td>
					</tr>
					<tr>
						<td colspan="2">
						</td>
					</tr>
				</table>
				<fieldset>
					<input type="submit" name="instruction" value="Create Account" id = "submit" onclick = "return checkFormInput();" disabled />
					<input type = "reset" name = "cancellation" value = "Cancel" id = "reset" />
				</fieldset>
				<!-- when this button is pressed, first validate the user's input. Allow only non-empty fields, then insert the user's input into the database. -->
			</form>
        </div>
	<?php
		include('../pageFooterScripts/footer.php');
    ?>
    </body>
</html>