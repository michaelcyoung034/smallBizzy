<?php session_start();
    /****************************************************************************
    * File Name: faq.php
    * Use-case: user message inbox
    * Author: Imane Badra Tate
    *
    * This page shows the answers to some frequently asked questions
    *****************************************************************************/ 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>Frequently Asked Questions</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <link href="../styleSheets/masterPageStyle.css" type="text/css" rel="Stylesheet" />
    </head>
    <body>
	<?php
		include('../pageHeaderScripts/header.php');
    ?>
        <div id="content">
            Frequently Asked Questions<br />
			
			Q: How do I set up an account?<br />
			A: Go to the "Sign in" link at the top of the page and fill out the form.

        </div>
	<?php
		include('../pageFooterScripts/footer.php');
    ?>
    </body>
</html>