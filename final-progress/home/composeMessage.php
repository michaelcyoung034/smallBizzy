<?php session_start();
//Imane Badra Tate <- File Owner
    /****************************************************************************
    * File Name: composeMessage.php
    * Use-case: user message inbox
    * Author: Imane Badra Tate
    *
    * This php file allows a user to send messages to other users
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
	
	switch ($_POST['instruction'])
	{
		case 'Reply':
		{
			$sid = $_POST['sender'];
			$rid = $_POST['recipient'];
			$subj = $_POST['subject'];
		}
		break;
		case 'Send':
		{
			$sid = $_SESSION['username'];
			$rid = $_POST['recipient'];
			$subj = $_POST['subject'];
			$mess = $_POST['message'];
			$date = date("Y-m-d H:i:s");
			
			$insertQuery = "INSERT INTO messages (sender, recipient, subject, message, message_timestamp) VALUES ('$sid', '$rid', '$subj', '$mess', '$date');";
			//run the query
			$insertRresult=mysql_query($insertQuery);
			if (!$insertRresult)
			{
				print mysql_error();
				exit;
			}
			else
			{
				?>
					<script type="text/javascript">
						alert("Your message has been sent");
					</script>
				<?php
				//echo ("query run".'<br/>');
			}
		}
		break;
	}
?>
<!DOCTYPE html PUBLIC "-//w3c//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
	<head>
		<title>smallBizzy Messaging </title>
		    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/prototype/1.6.0.3/prototype.js"></script>
		<script  src = "../validationScripts/composeMessage.js" ></script>
		<link href="../styleSheets/masterPageStyle.css" type="text/css" rel="Stylesheet" />
	</head>
	<body class="sendmessage">
			<!-- the page header will be a separate file  -->
		<?php
			include('../pageHeaderScripts/header.php');
		?>
		<div id="content">
			<form name="contactForm" method="post" action="composeMessage.php">
				<table width="450px" class="messageTable">
					<?php
						
					?>
					<tr>
						<td valign="top">
							<label for="Username">From :</label>
						</td>
						<td>
							<?php echo $_SESSION['username'] ?>
							<input name="username" value="<?php echo $_SESSION['username'] ?>" type="hidden" />
						</td>
					</tr>
					<tr>
						<td valign="top">
							<label for="recipient">To :</label>
						</td>
						<td valign="top">
							<input  type="text" name="recipient" value="<?php echo $_POST['sender'] ?>"  id="recipient" maxlength="50" size="30" onblur="validateRecipient();"/>
							Valid : <span id="msgbox"></span>
						</td>
					</tr>
					<tr>
						<td valign="top">
							<label for="subject">Title: </label>
						</td>
						<td valign="top">
							<input  type="text" name="subject" value="<?php echo $_POST['subject'] ?>"  id="title" maxlength="80" size="30" onmouseover = "subjects(0)" onmouseout = "subjects(1)">
						</td>
					</tr>
					<tr>
						<td valign="top">
							<label for="message" id="message" onload="input()" > Message :</label>
						</td>
						<td valign="top" class="messageCell">
							<textarea  name="message" cols="25" rows="6" ></textarea>
							<br /> <br />
						</td>
					</tr>
					<tr>
						<td colspan="2" style="text-align:center">

							<textarea id = "messageBox"  rows = "3"  cols = "50">
This box provides advice on filling out the form
on this page. Put the mouse cursor over any input
field to get advice.
        </textarea>
        <br /><br />
							<input name="instruction" id="Send" type="submit" value="Send" onclick="return checkval();" />
							<input name="instruction" type="reset" value="Reset"  />
						</td>
					</tr>
				</table>
			</form>
		</div>
			<!-- the page footer will be a separate file  -->
		<?php
			include('../pageFooterScripts/footer.php');
		?>
	</body>
</html>