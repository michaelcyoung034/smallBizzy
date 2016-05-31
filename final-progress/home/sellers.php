<?php session_start();
    /****************************************************************************
    * File Name: sellers.php
    * Use-case: search results for seller search
    * Author: Mike Young
    *
	* This page shows the user a list of sellers available to buy from
    *****************************************************************************/
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
	//get the user data
	$userQuery = "SELECT SUM(inventory.sales_volume) AS sales_volume, users.user_num, users.username, users.address, users.city, users.state, users.zip, users.email, users.phone, users.date_joined FROM inventory INNER JOIN users ON inventory.seller_num = users.user_num WHERE account_type = 'seller' AND sales_volume >= 0 GROUP BY users.user_num;";
	
	
	//run messageQuery
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
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>List of sellers</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
		<link href="../styleSheets/masterPageStyle.css" type="text/css" rel="Stylesheet" />
    </head>
    <body>
	<?php
		include('../pageHeaderScripts/header.php');
    ?>
        <div id="content">
			<?php
				for ($i = 0; $i < mysql_num_rows($userResult); $i++)
				{
					$user_row_array = mysql_fetch_array($userResult);
			?>
			<table>
				<tr>
					<td style="vertical-align:text-top;">
						<table class="sellerInfoCell">
							<tr>
								<th>
									Contact Information
								</th>
							</tr>
							<tr>
								<td>
									<a href="listOfProducts.php?seller_num=<?php echo $user_row_array['user_num']; ?>"><?php echo $user_row_array['username']; ?></a>
								</td>
							</tr>
							<tr>
								<td>
									<?php echo $user_row_array['phone']; ?>
								</td>
							</tr>
							<tr>
								<td>
									<?php echo $user_row_array['email']; ?>
								</td>
							</tr>
						</table>
					</td>
					<td style="vertical-align:text-top;">
						<table class="sellerAddressCell">
							<tr>
								<th>
									Mailing Address
								</th>
							</tr>
							<tr>
								<td>
									<?php echo $user_row_array['address']; ?>
								</td>
							</tr>
							<tr>
								<td>
									<?php echo $user_row_array['city']; ?>, <?php echo $user_row_array['state']; ?>
								</td>
							</tr>
							<tr>
								<td>
									<?php echo $user_row_array['zip']; ?>
								</td>
							</tr>
						</table>
					</td>
					<td style="vertical-align:text-top;">
						<?php
							//get the top 3 best-selling items from this seller based on sales volume
							$sellerID = $user_row_array['user_num'];
							//echo "Curretn seller ID for inventory: ".$sellerID;
							$bestItemQuery = "SELECT item_num, item_name, sell_price FROM inventory WHERE seller_num = '$sellerID' AND quantity > 0 ORDER BY -sales_volume LIMIT 3;";
							
							//run messageQuery
							$bestItemResult = mysql_query($bestItemQuery);
							
							if (!$bestItemResult)
							{
								print mysql_error();
								exit;
							}
							else
							{
								//echo ("query run".'<br/>');
							}
						?>
						<table class="topSellingItems">
							<tr>
								<th colspan="2">
									Top selling items
								</th>
							</tr>
							<?php
								for ($j = 0; $j < mysql_num_rows($bestItemResult); $j++)
								{
									$best_item_row_array = mysql_fetch_array($bestItemResult);
							?>
							<tr>
								<td>
									<a href="viewItem.php?item_num=<?php echo $best_item_row_array['item_num']; ?>"><?php echo $best_item_row_array['item_name'] ?></a>
								</td>
								<td class="priceCell">
									<?php echo $best_item_row_array['sell_price'] ?>
								</td>
							</tr>
							<?php
								}
							?>
						</table>
					</td>
				</tr>
			</table>
			<?php
				}
				mysql_close();
			?>
        </div>
	<?php
		include('../pageFooterScripts/footer.php');
    ?>
    </body>
</html>