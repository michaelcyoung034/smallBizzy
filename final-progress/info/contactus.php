<?php session_start();
    /****************************************************************************
    * File Name: contactus.php
    * Use-case: user message inbox
    * Author: Imane Badra Tate
    *
    * This page allows the user to send a message to the inbox of the communications admin
    *****************************************************************************/
	switch ($_POST['instruction'])
	{
		case 'Submit':
		{
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
			
			if ($_SESSION['loggedin'])
			{
				//the registered user will have their username already provided
				$email=$_SESSION['username'];
			}
			else
			{
				//the unregistered user will have provided an email address to answer
				$email=$_POST['sender'];
			}
			//generate the select statement
			//Imane is our resident customer correspondent. This is why her name is hard-coded as the recipient.
			$recipient = "imane";
			$subject=$_POST['subject'];
			$message=$_POST['message'];
			$date = date("Y-m-d H:i:s");
			$query="INSERT INTO messages (sender, subject, recipient, message, message_timestamp) VALUES ('$email','$subject','$recipient','$message','$date');";
			
			//run the query
			$result=mysql_query($query);
			if (!$result)
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
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
	  <title>Contact us </title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	  <link href="../styleSheets/masterPageStyle.css" type="text/css" rel="Stylesheet" />
  </head>
	<body>
			<!-- the page header will be a separate file  -->
		<?php
			include('../pageHeaderScripts/header.php');
		?>
		<div id="content">
			<h1> :: Contact Us ::  </h1>
			<img src="../images/smallBizzyLogo.png" alt="logo" /> <br />
			+1(111)-111-1111 <br />
			SMALL-BIZZY <br />
			1000 Hilltop Cir <br />
			Baltimore, MD 21250 <br />
			<a >noreply@smallbizzy.com</a>
			<form name="contactus" method="post" action="contactus.php">
				<table>
					<?php
						if (!$$_SESSION['loggedin'])
						{
					?>
					<tr>
						<td>
							Email:
						</td>
						<td>
							<input name="sender" />
						</td>
					</tr>
					<?php
						}
					?>
					<tr>
						<td>
							Subject:
						</td>
						<td>
							<input name="subject" />
						</td>
					</tr>
					<tr>
						<td>
							Your Message:
						</td>
						<td>
							<textarea name="message" rows="15" cols="20"></textarea>
						</td>
							<td colspan="2">
						</td>
					</tr>
				</table>
				<input type="submit" name="instruction" value="Submit" /><br />
			</form>
		</div>
			<!-- the page footer will be a separate file  -->
		<?php
			include('../pageFooterScripts/footer.php');
		?>
	</body>
</html>