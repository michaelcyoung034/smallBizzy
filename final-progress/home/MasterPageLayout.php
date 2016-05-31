<?php session_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>Master Page Design</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
		<!-- make sure the style sheet is accessible from all web pages -->
        <link href="../styleSheets/masterPageStyle.css" type="text/css" rel="Stylesheet" />
    </head>
    <body>
	<?php
		include('../pageHeaderScripts/header.php');
    ?>
        <div id="content">
            Content will vary for each page design and will be a minimum of 400px tall<br />
			Test Table
		<?php
			/*//connect to the database
			$db = mysql_connect("studentdb-maria.gl.umbc.edu","hf28974","hf28974");
			if (!$db)
			{
				exit("Error - could not connect");
			}
			else
			{
				echo ("Connected");
			}
			
			//select the database
			$er = mysql_select_db("hf28974");
			if (!$er)
			{
				exit("Error - could not select");
			}
			else
			{
				echo ("Selected");
			}*/
		?>
        </div>
	<?php
		include('../pageFooterScripts/footer.php');
    ?>
    </body>
</html>