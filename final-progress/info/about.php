<?php session_start();
    /****************************************************************************
    * File Name: about.php
    * Use-case: user message inbox
    * Author: Imane Badra Tate
    *
    * This page has some info about the web site
    *****************************************************************************/ 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>About Us</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <link href="../styleSheets/masterPageStyle.css" type="text/css" rel="Stylesheet" />
    </head>
    <body>
	<?php
		include('../pageHeaderScripts/header.php');
    ?>
		<div id="content">
			<p class="previewTitle" > :: ABOUT US ::  </p>
			<p class="previewInfo">  At SmallBizy, our goal is to connect with you small business and provide you with a platform where you can find everything you need in one place! </p>
        </div>
	<?php
		include('../pageFooterScripts/footer.php');
    ?>
    </body>
</html>