<?php session_start();
//Imane Badra Tate <- File Owner;
    /****************************************************************************
    * File Name: inbox.php
    * Use-case: user message inbox
    * Author: Imane Badra Tate
    *
    * This php file displays all messages sent by and to this user.
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

	$uid=$_SESSION['username'];
	$receivedQuery = "SELECT message_num, sender, subject, message_timestamp FROM messages WHERE recipient='$uid' ORDER BY -message_timestamp;";
	$sentQuery = "SELECT message_num, recipient, subject, message_timestamp FROM messages WHERE sender='$uid' ORDER BY -message_timestamp;";
	
	//run the received query
	$receivedResult = mysql_query($receivedQuery);
	if (!$receivedResult)
	{
		print mysql_error();
		exit;
	}
	else
	{
		//echo ("query run".'<br/>');
	}
	
	//run the received query
	$sentResult = mysql_query($sentQuery);
	if (!$sentResult)
	{
		print mysql_error();
		exit;
	}
	else
	{
		//echo ("query run".'<br/>');
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
		<title>Inbox </title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
		<link href="../styleSheets/masterPageStyle.css" type="text/css" rel="Stylesheet" />
		<script type="text/javascript" src="../validationScripts/loginValidation.js"></script>
  </head>
  <body>
		<!-- the page header will be a separate file  -->
	<?php
		include('../pageHeaderScripts/header.php');
    ?>
	<div id="content">
		<a href="composeMessage.php">Compose message</a><br/>
		Messages Received<br/>
		<table width="450px" id="receivedTable" class="messageTable">
			<tr>
				<td class="fromColumn">
					FROM:
				</td>
				<td class="subjectColumn">
					Subject
				</td>
				<td class="timestampColumn">
					Time Recieved
				</td>
			</tr>
		<?php
			for ($i = 0; $i < mysql_num_rows($receivedResult); $i++)
			{
				$received_row_array = mysql_fetch_array($receivedResult);
		?>
			<tr>
				<td>
					<?php echo $received_row_array['sender'] ?>
				</td>
				<td>
					<a href="viewMessage.php?message_num=<?php echo $received_row_array['message_num'] ?>"><?php echo $received_row_array['subject'] ?></a>
				</td>
				<td>
					<?php echo $received_row_array['message_timestamp'] ?>
				</td>
			</tr>
		 <?php
			}
		 ?>
		</table>
		Messages Sent<br/>
		<table width="450px" class="messageTable">
			<tr>
				<td class="toColumn">
					To:
				</td>
				<td class="subjectColumn">
					Subject
				</td>
				<td class="timestampColumn">
					Time Recieved
				</td>
			</tr>
		<?php
			for ($i = 0; $i < mysql_num_rows($sentResult); $i++)
			{
				$sent_row_array = mysql_fetch_array($sentResult);
		?>
			<tr>
				<td>
					<?php echo $sent_row_array['recipient'] ?>
				</td>
				<td>
					<a href="viewMessage.php?message_num=<?php echo $sent_row_array['message_num'] ?>"><?php echo $sent_row_array['subject'] ?></a>
				</td>
				<td>
					<?php echo $sent_row_array['message_timestamp'] ?>
				</td>
			</tr>
		 <?php
			}
		 ?>
		</table>
	</div>
		<!-- the page footer will be a separate file  -->
	<?php
		include('../pageFooterScripts/footer.php');
    ?>
</body>
</html>