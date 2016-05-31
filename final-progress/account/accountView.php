<?php session_start();
    /****************************************************************************
    * File Name: accountEdit.php
    * Use-case: user account registration
    * Author: Victoria Phillips
    *
	* This page displays the user's account details as well as links to invoices related to their account.
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
	//generate the select statement
	$uid = $_SESSION['user_num'];
	$userInfoQuery = "SELECT user_num, f_name, l_name, username, address, city, state, zip, email, phone, account_type FROM users WHERE (user_num='$uid');";
	//run the statement
	$userInfoResult = mysql_query($userInfoQuery);
	
	if (!$userInfoResult)
	{
		print mysql_error();
		exit;
	}
	else
	{
		//echo ("query run".'<br/>');
	}
	
	$user_info_row_array = mysql_fetch_array($userInfoResult);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>My Account</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <link href="../styleSheets/masterPageStyle.css" type="text/css" rel="Stylesheet" />
    </head>
    <body>
	<?php
		include('../pageHeaderScripts/header.php');
    ?>
        <div id="content">
            <table class="accountInfo">
                <tr>
                    <th>Account Number</th>
					<td><?php echo $user_info_row_array['user_num']; ?></td>
                </tr>
                <tr>
                    <th>First Name</th>
                    <td><?php echo $user_info_row_array['f_name']; ?></td>
                </tr>
                <tr>
                    <th>Last Name</th>
                    <td><?php echo $user_info_row_array['l_name']; ?></td>
                </tr>
                <tr>
                    <th>Username</th>
                    <td><?php echo $user_info_row_array['username'] ?></td>
                </tr>
                <tr>
                    <th>Street Address</th>
                    <td><?php echo $user_info_row_array['address'] ?></td>
                </tr>
                <tr>
                    <th>City</th>
                    <td><?php echo $user_info_row_array['city'] ?></td>
                </tr>
                <tr>
                    <th>State</th>
                    <td><?php echo $user_info_row_array['state'] ?></td>
                </tr>
                <tr>
                    <th>ZIP</th>
                    <td><?php echo $user_info_row_array['zip'] ?></td>
                </tr>
                <tr>
                    <th>Email Address</th>
                    <td><?php echo $user_info_row_array['email'] ?></td>
                </tr>
                <tr>
                    <th>Phone Number</th>
                    <td><?php echo $user_info_row_array['phone'] ?></td>
                </tr>
                <tr>
                    <th>Account Type</th>
                    <td><?php echo $user_info_row_array['account_type'] ?></td>
                </tr>
            </table>
            <a href="accountEdit.php">Update Information</a><br/>
            <a href="invoiceListView.php">View Invoices</a><br />
			<?php
				if ($_SESSION['account_type'] == 'seller')
				{
			?>
            <a href="statementListView.php">View statements</a><br />
			<?php
				}
			?>
            <a href="../home/index.php">Back to Home</a>
        </div>
	<?php
		include('../pageFooterScripts/footer.php');
		
		mysql_close();
    ?>
    </body>
</html>