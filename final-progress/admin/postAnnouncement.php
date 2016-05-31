<?php session_start();
    /****************************************************************************
    * File Name: postAnnouncement.php
    * Use-case: 
    * Author: Mike Young
    *
	* This page allows administrators to post announcements which can be seen site-wide.
    *****************************************************************************/
	switch ($_POST['instruction'])
	{
		case 'Post':
		{
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
			
			//generate the insert statement
			$uid = $_SESSION['user_num'];
			$postTime = date("Y-m-d H:i:s");
			$title = $_POST['title'];
			$mess = $_POST['message'];
			
			$query = "INSERT INTO announcements (poster_num, announcement_timestamp, title, message) VALUES ('$uid','$postTime','$title','$mess');";
			
			//run the statement
			$result = mysql_query($query);
			
			if (!$result)
			{
				print mysql_error();
				exit;
			}
			else
			{
				?>
					<script type="text/javascript">
						alert("Message about '<?php echo $title; ?>' has been posted.");
					</script>
				<?php
				//echo ("query run".'<br/>');
			}
		}
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<!-- At the start of each admin page, check that the user is an admin. If not, redirect the user to the home page. -->
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>Post Announcement</title>
		<!-- make sure the style sheet is accessible from all web pages -->
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <link href="../styleSheets/masterPageStyle.css" type="text/css" rel="Stylesheet" />
		<script type="text/javascript" src="../validationScripts/announcementValidation.js">
		</script>
    </head>
    <body>
		<!-- the page header will be a separate file  -->
	<?php
		include('../pageHeaderScripts/header.php');
    ?>
        <div id="content">
			<form name="postAnnouncement" method="post" action="postAnnouncement.php">
				title<br />
				<input name="title" id="title"/><br />
				Message<br />
				<textarea name="message" id="message" rows="10" cols="40"></textarea><br />
				<!-- When this button is pressed, the subject and message will be appended to the database. -->
				<input name="instruction" type="submit" value="Post" />
			</form>
        </div>
		<!-- Like the header, the footer will be present on ALL pages -->
	<?php
		include('../pageFooterScripts/footer.php');
    ?>
    </body>
</html>