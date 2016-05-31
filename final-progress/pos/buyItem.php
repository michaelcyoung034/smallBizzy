<?php session_start(); ?>
<!--
	/****************************************************************************
	* File Name: buyItem.php
	* Use-case: customer view inventory
	* Author: Kayoung Kim
	* E-mail: kayoung2@umbc.edu
	*
	* This php file implement user order.
	* Since this page is only from shoppincCart.php,
	*   it inserts order information to DB
	* Also, it display what user order items are, and
	*   display thank you message.
	*
	*****************************************************************************/
-->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Your Shopping Cart</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<link href="../styleSheets/masterPageStyle.css" type="text/css" rel="Stylesheet" />
	<script type="text/javascript" src="../validationScripts/kayoung2.js"></script>
</head>
<body>
 
<!--START HEADER-->
	<?php include '../pageHeaderScripts/header.php'; ?>
<!-- END HEADER-->

<!--STARTS buyitem-->



<br />
<table class ="receiptTB" align="center">
	<tr>
		<td colspan="4" height="30px;"><span class="title1">:: ORDER PROCESS ::</span></td>
	</tr>
	<tr><td colspan="4" class="receiptLine"></td></tr>
	<tr class="reciptHeadlineTR">
		<td width="400px"><span class="title2"> Product   </span></td>
		<td width="100px"><span class="title2"> Price  </span></td>
		<td width="100px"><span class="title2"> Quantity  </span></td>
		<td width="100px"><span class="title2"> Item Total </span></td>
	</tr>
	<tr><td colspan="4" class="receiptLine"></td></tr>
	<tr><td colspan="4" height="10"></td></tr>
	<tr><td colspan="4" valign="top">
		<table class="reciptDetailTB">
<?php

	$uid = $_SESSION['user_num'];

	$grandTotal = 0;

	$servername = "studentdb-maria.gl.umbc.edu";
	$username = "hf28974";
	$password = "hf28974";
	$dbname = "hf28974";

  
	//DB connect info and SELECT Query
	$con = mysql_connect("$servername", "$username", "$password");
	if (!$con){
		echo '<script type="text/javascript">alert("CANNOT CONNECT TO DB: mysql_connect error!")</script>';
		exit;
	}

	$db = mysql_select_db("$dbname", $con);
	if(! $db){
		//echo '.....onclick="removeElement(&quot;div'.$x.'&quot;)"...';
		echo '<script type="text/javascript">alert("CANNOT SELECT DB: mysql_select_db error!")</script>';
		exit;
	}


		//$query = "SELECT post_id, post_title, post_text, post_tag FROM hw3db";
		$scQ = "SELECT * FROM shopping_cart WHERE user_num = $uid";
		$selectResult = mysql_query($scQ);
		if (!$selectResult) {

			if ($uid == NULL) {
				echo '<tr><td colspan="4">USER shopping cart is empty!<br/>Not available purchase!</td></tr>';
				echo '</table></td></tr></table>';
			} else {
				print "ERROR SELECT2: ";
				print mysql_error();
			}

			include '../pageFooterScripts/footer.php';
			echo '</body></html>';
			exit;
		}


		$num = mysql_numrows($selectResult);


		$i=0;
		$grandTotal = 0;
		while ($i < $num) {
			$eachItemNum = mysql_result($selectResult, $i, "item_num");

			$eachQuantity = mysql_result($selectResult, $i, "quantity");
			if ($eachQuantity == 0) {
				# code...
			}else{
				$itemQ = "SELECT item_name, sell_price FROM inventory WHERE item_num = $eachItemNum";
				$InvenResult = mysql_query($itemQ);
				if(! $InvenResult){
					print("ERROR 3: QUARY");
					print mysql_error();
					exit;
				}

				$eachItemName = mysql_result($InvenResult, 0, 'item_name');

				$eachPrice = mysql_result($InvenResult, 0, 'sell_price');

				$grandTotal = $grandTotal + ($eachPrice * $eachQuantity);
				echo '<tr class="reciptDetailTR" >';
				echo '<td class="reciptDetailTD" width="550">'.$eachItemName.'</td>';
				echo '<td width="130"> $&nbsp;'.$eachPrice.' </td>';
				echo '<td width="130">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;x&nbsp;'.$eachQuantity.' </td>';
				echo '<td width="140"> &nbsp;&nbsp;&nbsp; $ ';
				echo number_format(($eachPrice * $eachQuantity), 2, '.', '').' </td></tr>';
				}
			$i++;
		}
	


	//insert a new invoice to the database with the 'pending' status. 
		//The items in the shopping cart may come from many different sellers, 
		//so it will be necessary to make group the items based on the item's sellerID.
	//step 1: get a list of UNIQUE sellers and put them in a list.
	$bid = $_SESSION['user_num'];
	$sellerNumbersQuery = "SELECT DISTINCT inventory.seller_num FROM inventory INNER JOIN shopping_cart ON inventory.item_num = shopping_cart.item_num WHERE shopping_cart.user_num = '$bid';";
				
	//execute query
	$sellerNumbersResult = mysql_query($sellerNumbersQuery);
	
	if (!$sellerNumbersResult)
	{
		print mysql_error();
		exit;
	}

	//store the resulting UNIQUE sellers in an array to create a purchase order for each one
	$sellerNums = array();
	
	for($i = 0; $i < mysql_num_rows($sellerNumbersResult); $i++)
	{
		//store the resulting query
		$seller_numbers_row_array = mysql_fetch_array($sellerNumbersResult);
		array_push($sellerNums, $seller_numbers_row_array['seller_num']);
	}
	
	//step 2: reserve space in the database for each invoice that will be placed for 
	//each order to the respective sellers
	//for each seller the buyer is buying from...
	for($i = 0; $i < sizeof($sellerNums); $i++)
	{
		//insert a blank invoice. the line items will be added later.
		$timestamp = date("Y-m-d H:i:s");
		$sid = $sellerNums[$i];
		$bid = $_SESSION['user_num'];
		$insertInvoiceQuery = "INSERT INTO invoices (order_timestamp, buyer_num, seller_num) VALUES ('$timestamp', '$bid', '$sid');";
		$lastIDQuery = "SELECT LAST_INSERT_ID();";
		//execute insert query
		$insertInvoiceResult = mysql_query($insertInvoiceQuery);
		//execute invoice number query
		$lastIDResult = mysql_query($lastIDQuery);
		
		if (!$insertInvoiceResult)
		{
			print mysql_error();
			exit;
		}

		if (!$lastIDResult)
		{
			print mysql_error();
			exit;
		}

		//store the resulting query
		$last_id_row_array = mysql_fetch_array($lastIDResult);
		
		//always store the invoice's number so the line items can be attached.
		$invoice_num = $last_id_row_array[0];
		
		//step 3: insert the line items for each invoice. While the items are being added, 
		//the subtotal, tax, and grand total can be calculated and inserted as well. 
		//The totals will be added after the line items are added in.
		//select the item numbers from the current seller.
		$bid = $_SESSION['user_num'];
		$lineItemsQuery = "SELECT inventory.item_num, inventory.sell_price, shopping_cart.quantity FROM inventory INNER JOIN shopping_cart ON inventory.item_num = shopping_cart.item_num WHERE shopping_cart.user_num = '$bid' AND inventory.seller_num = '$sid';";
		$lineItemsResult = mysql_query($lineItemsQuery);
		
		if (!$lineItemsResult)
		{
			print mysql_error();
			exit;
		}

		for($j = 0; $j < mysql_num_rows($lineItemsResult); $j++)
		{
			//store the resulting query
			$line_items_row_array = mysql_fetch_array($lineItemsResult);
			
			//insert the line items with the invoice number
			$item = $line_items_row_array['item_num'];
			$qty = $line_items_row_array['quantity'];
			$inv = $last_id_row_array[0];
			$insertLineItemsQuery = "INSERT INTO line_items (invoice_num, item_num, quantity) VALUES ('$inv', '$item', '$qty');";
			//execute line item insert query
			$insertLineItemsResult = mysql_query($insertLineItemsQuery);
			if (!$insertLineItemsResult)
			{
				print mysql_error();
				exit;
			}

		}
	}
	
	//step 4: clear the shopping cart so the user doesn't send a duplicate order through.
	$uid = $_SESSION['user_num'];
	$deleteQuery = "DELETE FROM shopping_cart WHERE user_num='$uid';";

	//execute query
	$deleteResult = mysql_query($deleteQuery);

	if (!$deleteResult)
	{
		print mysql_error();
		exit;
	}

?>

	</table></td></tr>
	<tr><td colspan="4" height="30"></td></tr>

	<tr valign="bottom">
		<td colspan="4" valign="bottom">
	<center><h3>" Thank you for buying our products. "</h3></center>   
		</td>
	</tr>

	<tr><td colspan="4" class="receiptLine"></td></tr>
	<tr><td colspan="4" valign="bottom" align="right">
		<table>
			
			<tr><td height="25px"><span class="title2">Subtotal: &nbsp;&nbsp;&nbsp;</span></td>
				<td><span class="title2">$&nbsp;</span></td>
				<td><span class="title2">
					<?php
						echo number_format($grandTotal, 2, '.', '');
					?>
					</span>
				</td>
			</tr>
			<tr><td height="25px"><span class="title2">Tax: &nbsp;&nbsp;&nbsp;</span></td>
				<td><span class="title2">$&nbsp;</span></td>
				<td><span class="title2">
					<?php

					$taxrate = 0.06; // % value
					$temp = $grandTotal * 0.06;
					$salesTax = number_format($temp, 2, '.', '');
					echo $salesTax;

					?>
					</span>
				</td>
			</tr>
			<tr><td height="25px"><span class="title2">Total: &nbsp;&nbsp;&nbsp;</span></td>
				<td><span class="title2">$&nbsp;</span></td>
				<td><span class="title2">
					<?php
						$grandTotal = $grandTotal + $salesTax;
						
						echo number_format($grandTotal, 2, '.', '');
					?>
				</span>
				</td>
			</tr>
			
		</table>
		<br/>

</table>


<!--ENDS buyitem -->


<!--START FOOTER-->
	<br />
	<?php include '../pageFooterScripts/footer.php'; ?>
<!-- END FOOTER-->

</body>
</html>


