<div id="header">
	<table class="headerTable">
		<tr>
			<td class="headerLogo">
				<a href="../home/index.php">
					<img src="../images/smallBizzyLogo.png" alt="Small Bizzy official logo and site home page link" />
				</a>
			</td>
			<td class = "headerSearch">
				<!--<form action="../home/sellers.php" method="post">
					<input type="text" name="searchTopic" value="Search for items or shops" class="searchTextbox"/> 
					<fieldset id="headerSearchForm">
						<input type="submit" value="Search" class="searchSubmitButton"/>
					</fieldset>
				</form>-->
				<a href="../home/sellers.php">List of Sellers</a>
			</td>
				<!-- log in or log out -->
			<?php
			switch($_SESSION["account_type"])
			{
				case "administrator":
				case "seller":
				case "shopper":
			?>
			<td class = "emailCell">
				<a href = "../home/inbox.php"><img src = "../images/email.PNG" alt = "send email"/></a> <!--to-do: link corresponding.php pages, update image -->
			</td>
			<td class="authenticationCell">
				<form id="logout" method="post" action="../home/index.php">
					<fieldset id="loginForm">
						<input type="hidden" name="instruction" value="logout" />
						<input type="submit" value="Logout" />
					</fieldset>
				</form>
			</td>
			<?php
					break;
				default:
			?>
			<td class="authenticationCell">
				<form id="login" method="post" action="../home/index.php" onsubmit="return validInput();">
					<fieldset id="loginForm">
						Sign in or <a href="../account/accountCreate.php">sign up</a><br />
						<input name="instruction" value="login" type="hidden" />
						<input name="login[username]" id="login_username" onblur="checkUsername()" onmouseover="showUsername()" onmouseout="revert()" /><br />
						<input name="login[password]" id="login_pass" type="password" onmouseover="showPassword()" onmouseout="revert()" /><br />
						<input type="submit" id="Submit" value="Submit" onmouseover="showLogin()" onmouseout="revert()" />
						<span id="loginInfo"></span>
					</fieldset>
				</form>
			</td>
			<?php
					break;
				}
			?>
		</tr>
	</table>
	<table>
		<tr>
		<?php
			switch ($_SESSION["account_type"])
			{
				case "administrator":
		?>
			<!-- load the "Financial Home" link -->
			
			<td>
				<a href="../home/index.php">Financial Home</a> |
			<!-- load the "Customer Account View" link -->
				<a href="../home/index.php">Customer Accounts</a> |
			<!-- load the "post announcement" link -->
				<a href="../admin/postAnnouncement.php">Post Announcement</a> |
			<!-- load the "my account" link -->
				<a href="../account/accountView.php">My Account</a>
			</td>
			<td align="right">
				<a href="../pos/shoppingCart.php">
					<img src="../images/shoppingCart.png" alt="View my Shoping Cart" />
				</a><br />
			</td>
		<?php
				break;
			case "seller":
		?>
			<!-- load the "Small Business Home Page" link -->
			<td>
			<!-- load the "Pending Orders" link -->
				<a href="../myShop/pendingOrders.php">Pending Orders</a> |
			<!-- load the "Point-of-sale" link -->
				<a href="../pos/invoice.php">Point Of Sale</a> |
			<!-- load the "inventory manager" link -->
				<a href="../myShop/manageMyShop.php">Inventory Manager</a> |
			<!-- load the "my account" link -->
				<a href="../account/accountView.php">My Account</a>
			</td>
			<td align="right">
				<a href="../pos/shoppingCart.php">
					<img src="../images/shoppingCart.png" alt="View my Shoping Cart" />
				</a><br />
			</td>
		<?php
				break;
			case "shopper":
		?>
			<!-- load the "account/my account" link -->
			<td style="width:10%">
				<a href="../account/accountView.php">My Account</a>
			</td>
			<td align="right">
				<a href="../pos/shoppingCart.php">
					<img src="../images/shoppingCart.png" alt="View my Shoping Cart" />
				</a><br />
			</td>
		<?php
				break;
			default:
		?>
			<td>
			</td>
		<?php
				break;
			}
		?>
		</tr>
	</table>
	<table>
		<tr>
			<td class="announcement">
				<!-- the title of the most recent announcement posted will go here. It will link to the announcement page. If the newest announcement is more than 7 days old, do not show this banner. -->
				<?php
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
					
					//bring the title of the most recent announcement to the front page.
					$query = "SELECT title FROM announcements ORDER BY -announcement_timestamp LIMIT 1;";
					
					//execute query
					$result = mysql_query($query);
							
					if (!$result)
					{
						print mysql_error();
						exit;
					}
					else
					{
						//echo ("select query run");
					}
					$row_array=mysql_fetch_array($result);
				?>
				<a href="../info/messageBoard.php">
					<?php echo $row_array['title'] ?>
				</a>
			</td>
		</tr>
	</table>
	<p class="spacer"></p>
</div>