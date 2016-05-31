<?php
	session_start();
	/****************************************************************************
    * File Name: index.php
    * Use-case: 
    * Author: Mike Young
    *
	* This page is the landing page for users to be welcomed to the site and permits navigation pretty much anywhere else.
    *****************************************************************************/
	//We will always assume the user intended to log out each time they close their browser. Every new session starts with a 'logged out' state.
	
	//users may be returning to this page from this page by logging in. In that case, check the user's input from the login form. If it matches a user_num in the database (it should only match one or none), check to see if that user's password matches the user's input. If both cases are true, set the session variable of "logged_in" to true and "user_num" to the user's user_num.
	
	//if the user is not logged in or has just arrived to this page, set the loggedin variable.
	if (!isset($_SESSION['loggedin']))
	{
		$_SESSION['account_type'] = 'unreg';
		$_SESSION['f_name'] = 'guest';
		$_SESSION['loggedin'] = false;
	}
	switch ($_POST['instruction'])
	{
		case "login":
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
			
			//generate the select statement
			$uname = mysql_real_escape_string(htmlspecialchars($_POST['login']['username']));
			$pass = mysql_real_escape_string(htmlspecialchars($_POST['login']['password']));
			$query = "SELECT user_num, f_name, username, password, account_type FROM users WHERE (username='$uname' AND password='$pass');";
			
			//run the statement
			$result = mysql_query($query);
			
			if (!$result)
			{
				print mysql_error();
				exit;
			}
			else
			{
				//echo ("query run".'<br/>');
			}
			
			$row_array = mysql_fetch_array($result);
			$uid = $row_array['user_num'];
			//there should only be one record with that user_num and should not be the unregistered user account
			if (mysql_num_rows($result) == 1 && $uid != 0)
			{
				$_SESSION['user_num'] = $row_array['user_num'];
				$_SESSION['username'] = $row_array['username'];
				$_SESSION['f_name'] = $row_array['f_name'];
				$_SESSION['account_type'] = $row_array['account_type'];
				$_SESSION['loggedin'] = true;
			}
			//if the user is not found, they are not a registered user. allow them to try again, but be sure they are logged out.
			else if (mysql_num_rows($result) == 0 || $uid == 0)
			{
				?>
				<script type="text/javascript">
					alert("Account not found. Please try again.");
				</script>
				<?php
				//make sure that invalid input is not logged in
				logout();
			}
			
			mysql_close();
		}
		break;
		case "logout":
		{
			logout();
		}
		break;
	}
	
	function logout()
	{
		unset($_SESSION['user_num'], $_SESSION['username']);
		$_SESSION['account_type'] = 'unreg';
		$_SESSION['f_name'] = 'guest';
		$_SESSION['loggedin'] = false;
	}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>smallBizzy Home</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <link href="../styleSheets/masterPageStyle.css" type="text/css" rel="Stylesheet" />
        <link href="home_rollingBanner/home_rollingBanner.css" type="text/css" rel="Stylesheet" />
		<script type="text/javascript" src="../validationScripts/loginValidation.js"></script>
		<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/prototype/1.6.0.3/prototype.js"></script>
		<script src="http://ajax.googleapis.com/ajax/libs/scriptaculous/1.8.2/scriptaculous.js" type="text/javascript"> </script>
    </head>
    <body>
	<?php
		include('../pageHeaderScripts/header.php');
    ?>
        <div id="content">
			Welcome, <?php echo $_SESSION['f_name'] ?>.<br />
			<!-- list of featured products and businesses here -->
			<div class="homeBody">

				<!--from HERE : rolling banner-->
				<script type='text/javascript' src='home_rollingBanner/jquery-1.4.2.js'></script>
				<script type="text/javascript" src="home_rollingBanner/stepcarousel.js"></script>
				<!--<link rel=stylesheet type=text/css href="home_rollingBanner/home_rollingBanner.css">-->


				<script type="text/javascript">
					stepcarousel.setup(
					{
						galleryid: 'promotion',
						beltclass: 'belt',
						panelclass: 'panel',
						panelbehavior: {speed:500, wraparound:true, wrapbehavior:'pushpull', persist:false},
						defaultbuttons: {enable: false, moveby: 1, leftnav: ['/arrowl.gif', -10, 100], rightnav: ['/arrowr.gif', -10, 100]},
						statusvars: ['reportA', 'reportB', 'reportC'],
						contenttype: ['inline']
					});

					//Every 4 seconds, it moves
					function moveStep()
					{
						stepcarousel.stepBy('promotion', 1);
						setTimeout(moveStep,4000);
					}
					moveStep();
				</script>
			<!--<style type="text/css">
			#promotion{overflow: hidden;}
			</style>-->
				<table class="homeBodyTable" cellpadding="0" cellspacing="0">
					<tr>
						<td width="755" height="325">
							<div id="promotion" class="stepcarousel">
								<div class="belt">
									<div class="panel" style="float: none; position: absolute; left: 0px; ">
										<img src="home_rollingBanner/home_img/banner01.jpg" class="hand" onclick="location.href='#item1';" alt="" />
									</div>
									<div class="panel" style="float: none; position: absolute; left: 755px; ">
										<img src="home_rollingBanner/home_img/banner02.jpg" class="hand" onclick="location.href='#item2';" alt="" />
									</div>
									<div class="panel" style="float: none; position: absolute; left: 1510px; ">
										<img src="home_rollingBanner/home_img/banner03.jpg" class="hand" onclick="location.href='#item3';" alt="" />
									</div>
								</div>
							</div>
						</td>
						<td width="5px">
						</td>
						<td>
							<table width="220px">
								<tr style="background-color:lightgrey; height:200px;">
									<td>Right Banner#1</td>
								</tr>
								<tr style="height:4px"><td></td></tr>
								<tr style="background-color:lightgrey; height:118px;">
									<td>Right Banner#2</td>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<td height="5"></td>
					</tr>
					<tr>
						<td>
							<p id="promotion-paginate" style="position:relative; width:755px; top:-40px; text-align:center; z-index:999;">
							<img src="home_rollingBanner/home_img/icon_off.gif" alt="" /> <!--data-over="home_rollingBanner/home_img/icon_on.gif" data-select="home_rollingBanner/home_img/icon_on.gif" data-moveby="1"-->
							</p>
						</td>
					</tr>
				</table>
				<!--to HERE: rolling banner-->
				<hr/>
				<table class="previewInfo">
					<tr class="previewInfo"><td class="previewInfoHeader" colspan="8"><p class="previewTitle">:: THE BEST SELLING PRODUCTS ::</p></td></tr>
						<?php
							$numOfRows = 2;
							$listingsPerRow = 4;
							$numOfItems = 8;
							//get the best-selling products
							$query = "SELECT item_num, item_name, description, sell_price FROM inventory WHERE quantity > 0 ORDER BY -sales_volume LIMIT 8;";
							
							//run the statement
							$result = mysql_query($query);
							
							if (!$result)
							{
								print mysql_error();
								exit;
							}
							else
							{
								//echo ("query run".'<br/>');
							}
							//echo mysql_num_rows($result);
							for ($i = 0; $i < $numOfRows; $i++)
							{
						?>
					<tr>
						<?php
								for ($j = 0; $j < $listingsPerRow; $j++)
								{
									$row_array = mysql_fetch_array($result);
						?>
					<!--<tr>
						<td class="previewTD"></td>
						<td class="previewSpace"></td>
						<td class="previewTD"></td>
						<td class="previewSpace"></td>
						<td class="previewTD"></td>
						<td class="previewSpace"></td>
						<td class="previewTD"></td>
					</tr>
					<tr>-->
						<td class="previewInfo">
							<p>Item#<?php echo ($i * $listingsPerRow) + $j + 1 ?><br/>
								Item: <a href="viewItem.php?item_num=<?php echo $row_array['item_num']; ?>"><?php echo $row_array['item_name'] ?></a><br/>
								Description: <?php echo $row_array['description'] ?>
							</p>
							<p class="previewPrice">
								Price: $<?php echo $row_array['sell_price'] ?>
							</p>
						</td>
						<td class=""></td>
						<?php
								}
						?>
					</tr>
						
						<?php
							}
						?>
						<!--<td class="previewInfo"><p>Item#2<br/>very good product<br/></p><p class="previewPrice">$19.99</p></td>
						<td class=""></td>
						<td class="previewInfo"><p>Item#3<br/>very good product<br/></p><p class="previewPrice">$19.99</p></td>
						<td class=""></td>
						<td class="previewInfo"><p>Item#4<br/>very good product<br/></p><p class="previewPrice">$19.99</p></td>
						<td class=""></td>
					</tr>
					<tr>
						<td class="previewTD"></td>
						<td class="previewSpace"></td>
						<td class="previewTD"></td>
						<td class="previewSpace"></td>
						<td class="previewTD"></td>
						<td class="previewSpace"></td>
						<td class="previewTD"></td>
					</tr>
					<tr>
						<td class="previewInfo"><p>Item#5<br/>very good product<br/></p><p class="previewPrice">$19.99</p></td>
						<td class=""></td>
						<td class="previewInfo"><p>Item#6<br/>very good product<br/></p><p class="previewPrice">$19.99</p></td>
						<td class=""></td>
						<td class="previewInfo"><p>Item#7<br/>very good product<br/></p><p class="previewPrice">$19.99</p></td>
						<td class=""></td>
						<td class="previewInfo"><p>Item#8<br/>very good product<br/></p><p class="previewPrice">$19.99</p></td>
						<td class=""></td> -->
				</table>
				<hr/>
				<table class="homeBodyTable">
				<tr class="previewTitle"><td><p class="previewTitle">:: THE TOP SELLER ::</p></td></tr>
				<tr>
				<?php
					$numOfItems = 4;
					//get the best-selling products
						//echo "combined query<br/>";
					$query = "SELECT SUM(inventory.sales_volume) AS sales_volume, users.user_num, users.username FROM inventory INNER JOIN users ON inventory.seller_num = users.user_num WHERE sales_volume > 0 GROUP BY inventory.seller_num ORDER BY -SUM(inventory.sales_volume) LIMIT 4;";
					
					//run the statement
					$result = mysql_query($query);
					
					if (!$result)
					{
						print mysql_error();
						exit;
					}
					else
					{
						//echo ("query run".'<br/>');
					}
					//echo "num of rows: ".mysql_num_rows($result)."<br/>";
					for ($i = 0; $i < $numOfItems; $i++)
					{
						$row_array = mysql_fetch_array($result);
						if ($row_array == false)
						{
							break;
						}
						else
						{
				?>
					<td>
							username: <a href="listOfProducts.php?seller_num=<?php echo $row_array['user_num'] ?>"><?php echo $row_array['username'] ?></a><br/>
							items sold: <?php echo $row_array['sales_volume'] ?><br/>
					</td>
				<?php
						}
					}
					/*for ($i = 0; $i < mysql_num_rows($result); $i++)
					{
						echo "username: ".$row_array['username']."<br/>";
						echo "num sold: ".$row_array['SUM(inventory.sales_volume)']."<br/>";
						
					}*/
					
					//the "separated" query tells us each individual item's sale volume and how it adds up to the seller's sale total
					/*	echo "separated query<br/>";
					$query = "SELECT inventory.sales_volume, inventory.item_name, users.username FROM inventory INNER JOIN users ON inventory.seller_num = users.user_num ORDER BY -sales_volume;";
					
					//run the statement
					$result = mysql_query($query);
					
					if (!$result)
					{
						print mysql_error();
						exit;
					}
					else
					{
						//echo ("query run".'<br/>');
					}
					
					for ($i = 0; $i < mysql_num_rows($result); $i++)
					{
					$row_array = mysql_fetch_array($result);
						echo "username: ".$row_array['username']."<br/>";
						echo "username: ".$row_array['item_name']."<br/>";
						echo "num sold: ".$row_array['sales_volume']."<br/>";
						
					}*/
				?>
					<!--<tr>
						<td class="previewTD"></td>
						<td class="previewSpace"></td>
						<td class="previewTD"></td>
						<td class="previewSpace"></td>
						<td class="previewTD"></td>
						<td class="previewSpace"></td>
						<td class="previewTD"></td>
					</tr>
					<tr>
						<td class="previewInfo">
							<p class="topSellerName">THE TOP SELLER#1</p>
							<p class="topSellerInfo">Seller Name:<br/>Sales Volume: </p></td>
						<td class=""></td>
						<td class="previewInfo">
							<p class="topSellerName">THE TOP SELLER#2</p>
							<p class="topSellerInfo">we have very good product<br/>We do best we can...</p></td>
						<td class=""></td>
						<td class="previewInfo">
							<p class="topSellerName">THE TOP SELLER#3</p>
							<p class="topSellerInfo">we have very good product<br/>We do best we can...</p></td>
						<td class=""></td>
						<td class="previewInfo">
							<p class="topSellerName">THE TOP SELLER#4</p>
							<p class="topSellerInfo">we have very good product<br/>We do best we can...</p></td>
						<td class=""></td>
					</tr>-->
					</tr>
				</table>
				<br/><br/><br/>
			</div>
        </div>
	<?php
		include('../pageFooterScripts/footer.php');
    ?>
    </body>
</html>