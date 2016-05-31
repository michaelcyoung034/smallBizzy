<?php session_start();
    /****************************************************************************
    * File Name: siteMap.php
    * Use-case: user message inbox
    * Author: Imane Badra Tate
    *
    * This php file displays the site map.
    *****************************************************************************/
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>Site Map</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <link href="../styleSheets/masterPageStyle.css" type="text/css" rel="Stylesheet" />
    </head>
    <body>
	<?php
		include('../pageHeaderScripts/header.php');
    ?>
        <div id="content">
            Site Map<br />
            <ul>
                <li><a href="../home/index.php">Home Page</a></li>
                <li>
                    <a href="about.php">About Us</a>
                    <ul>
                        <li>
                            <a href="faq.php">FAQ</a>
                        </li>
                        <li>
                            <a href="contactus.php">Contact Us</a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="messageBoard.php">Message Board</a>
                </li>
            </ul>
        </div>
	<?php
		include('../pageFooterScripts/footer.php');
    ?>
    </body>
</html>