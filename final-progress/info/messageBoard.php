<?php session_start(); 
   /****************************************************************************
    * File Name: messageBoard.php
    * Use-case: user message inbox
    * Author: Imane Badra Tate
    *
    * This php file displays all messages posted from the admins
    *****************************************************************************/
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>Message Board</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <link href="../styleSheets/masterPageStyle.css" type="text/css" rel="Stylesheet" />
        <link href="../styleSheets/messageBoardStyle.css" type="text/css" rel="Stylesheet" />
    </head>
    <body>
	<?php
		include('../pageHeaderScripts/header.php');
		//echo date("Y-m-d H:i:s");
    ?>
        <div id="content">
			<?php
				//get the messages from the database and limit the number of results to the newest 20. procedurally generate an html table with all of the data in it. see the static page below for an example.
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
				//get the post data
				$messageQuery = "SELECT poster_num, announcement_timestamp, title, message FROM announcements ORDER BY -announcement_timestamp;";
				
				//run messageQuery
				$messageResult = mysql_query($messageQuery);
				
				if (!$messageResult)
				{
					print mysql_error();
					exit;
				}
				else
				{
					//echo ("query run".'<br/>');
				}
				
				for ($index = 0; $index < mysql_num_rows($messageResult); $index++)
				{
					$message_row_array = mysql_fetch_array($messageResult);
						
					$uid = $message_row_array['poster_num'];
					$userQuery = "SELECT username FROM users WHERE (user_num='$uid');";
					//run userQuery
					$userResult = mysql_query($userQuery);
					
					if (!$userResult)
					{
						print mysql_error();
						exit;
					}
					else
					{
						//echo ("query run".'<br/>');
					}
					
					$user_row_array = mysql_fetch_array($userResult);
					?>
						<table class="message">
							<tr>
								<!--subject-->
								<th>
									<?php echo $message_row_array['title'] ?><hr/>
								</th>
							</tr>
							<tr>
								<!--message-->
								<td>
									<p class="content"><?php echo $message_row_array['message'] ?></p><hr/>
								</td>
							</tr>
							<tr>
								<!--posted by [poster] on [timestamp]-->
								<td class="footerCell">
									Posted by <?php echo $user_row_array['username'] ?> on <?php echo $message_row_array['announcement_timestamp'] ?>
								</td>
							</tr>
						</table>
					<?php
				}
			?>
        </div>
	<?php
		include('../pageFooterScripts/footer.php');
    ?>
	</body>
</html>