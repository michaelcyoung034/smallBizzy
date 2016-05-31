<?php session_start();
//Imane Badra Tate <- File Owner
    /****************************************************************************
    * File Name: viewMessage.php
    * Use-case: user message inbox
    * Author: Imane Badra Tate
    *
    * This php file displays a single messages sent to this user.
    *****************************************************************************/
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

	$mid=$_GET['message_num'];
	$selectQuery = "SELECT sender, recipient, subject, message, message_timestamp FROM messages WHERE message_num='$mid';";
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
	
	$message_row_array = mysql_fetch_array($selectResult);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title>smallBizzy Messaging </title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
		<link href="../styleSheets/masterPageStyle.css" type="text/css" rel="Stylesheet" />
	</head>
	<body class="sendmessage">
		<!-- the page header will be a separate file  -->
		<?php
		include('../pageHeaderScripts/header.php');
		?>
		<div id="content">
			<table width="450px" class="messageTable">
				<tr>
					<td valign="top" class="toColumn">
						<!--<label for="Username">-->From :<!--</label>-->
					</td>
					<td valign="top">
						<?php echo $message_row_array['sender']; ?>
					</td>
				</tr>
				<tr>
					<td valign="top">
						<!--<label for="subject">-->Subject :<!--</label>-->
					</td>
					<td valign="top">
						<?php echo $message_row_array['subject']; ?>
					</td>
				</tr>
				<tr>
					<td valign="top">
						<!--<label for="message">-->Message: <!--</label>-->
					</td>
					<td valign="top" class="messageCell">
						<?php echo $message_row_array['message']; ?>
					</td>
				</tr>
				<tr>
					<td valign="top">
						<!--<label for="timeSent">-->Time Sent :<!--</label>-->
					</td>
					<td valign="top">
						<?php echo $message_row_array['message_timestamp']; ?>
					</td>
				</tr>
				<tr>
					<td>
						<form name="replyForm" method="post" action="composeMessage.php">
							<input name="sender" value="<?php echo $_SESSION['username'] ?>" type="hidden" />
							<input name="sender" value="<?php echo $message_row_array['sender']; ?>" type="hidden" />
							<input name="recipient" value="<?php echo $message_row_array['recipient']; ?>" type="hidden" />
							<input name="subject" value="<?php echo $message_row_array['subject']; ?>" type="hidden" />
							<input type="submit" name="instruction" value="Reply" />
						</form>
					</td>
				</tr>
			</table>
		</div>
		<!-- the page footer will be a separate file  -->
		<?php
		include('../pageFooterScripts/footer.php');
		?>
	</body>
</html>