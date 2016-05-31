<?php session_start();
    /****************************************************************************
    * File Name: posCheckout.php
    * Use-case: 
    * Author: Mike Young
    *
    * //This screen will simply be a static page showing the items from the previous page and the totals on the bottom line.
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
	
	$invNum = $_GET['inv_num'];
	$invQuery = "SELECT invoices.invoice_num, invoices.order_timestamp, users.username, invoices.grand_total, invoices.payment_method FROM users INNER JOIN invoices ON invoices.buyer_num = users.user_num WHERE invoice_num = '$invNum';";

	//execute query
	$invResult = mysql_query($invQuery);
			
	if (!$invResult)
	{
		print mysql_error();
		exit;
	}
	else
	{
		echo ("select query run");
	}
	$invoice_row_array = mysql_fetch_array($invResult);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>Print Invoice</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
		<!-- make sure the style sheet is accessible from all web pages -->
        <link href="../styleSheets/masterPageStyle.css" type="text/css" rel="Stylesheet" />
    </head>
    <body>
		<!-- the page header will be a separate file  -->
	<?php
		include('../pageHeaderScripts/header.php');
    ?>
        <div id="content">
			Print Invoice from this screen<br/>
			<table id="invoiceInfo">
				<tr>
					<td>
						Invoice ID
					</td>
					<td>
						<?php echo $invoice_row_array['invoice_num'] ?>
					</td>
				</tr>
				<tr>
					<td>
						Time Placed
					</td>
					<td>
						<?php echo $invoice_row_array['order_timestamp'] ?>
					</td>
				</tr>
				<tr>
					<td>
						Buyer
					</td>
					<td>
						<?php echo $invoice_row_array['username']; ?>
					</td>
				</tr>
				<tr>
					<td>
						Payment Method
					</td>
					<td>
						<?php echo $invoice_row_array['payment_method'] ?>
					</td>
				</tr>
			</table>
			<table class="lneItemsTable">
				<tr>
					<td>
						SUPPLIER
					</td>
					<td>
						PRODUCT
					</td>
					<td>
						NAME
					</td>
					<td>
						QUANTITY
					</td>
					<td>
						UNIT PRICE
					</td>
					<td>
						EXTENDED PRICE
					</td>
				</tr>
				<?php
					//get the item numbers and quantities
					$lineItemsQuery = "SELECT inventory.item_num, inventory.supplier, inventory.product, inventory.item_name, inventory.sell_price, line_items.quantity FROM line_items INNER JOIN inventory ON inventory.item_num = line_items.item_num WHERE line_items.invoice_num = '$invNum';";
					//execute query
					$lineItemsResult = mysql_query($lineItemsQuery);
							
					if (!$lineItemsResult)
					{
						print mysql_error();
						exit;
					}
					else
					{
						//echo ("select query run");
					}
					
					//this variable calculates the subtotal of the invoice.
					$subtotal = 0.00;
					for ($i = 0; $i < mysql_num_rows($lineItemsResult); $i++)
					{
						//hold data for the line items
						$line_items_row_array=mysql_fetch_array($lineItemsResult);
				?>
				<tr>
					<td>
						<?php echo $line_items_row_array['supplier'] ?>
					</td>
					<td>
						<?php echo $line_items_row_array['product'] ?>
					</td>
					<td>
						<?php echo $line_items_row_array['item_name'] ?>
					</td>
					<td>
						<?php echo $line_items_row_array['quantity'] ?>
					</td>
					<td>
						$<?php echo $line_items_row_array['sell_price'] ?>
					</td>
					<td>
						$<?php
							$unitPrx = $line_items_row_array['sell_price'];
							//echo $unitPrx;
							$qty = $line_items_row_array['quantity'];
							//echo $qty;
							$extPrx = $unitPrx * $qty;
							echo number_format($extPrx, 2);
							//increase the running subtotal after displaying each item
							$subtotal += $extPrx;
						?>
					</td>
				</tr>
				<?php
					}
					mysql_close();
				?>
			</table>
			<!-- This table will be the total, tax calculation, and other charges before the grand total -->
			<table class="totalsTable">
				<tr>
					<td>
						Sub-total
					</td>
					<td>
						<?php echo "$".number_format($subtotal, 2); ?>
					</td>
				</tr>
				<tr>
					<td>
						Sale Tax
					</td>
					<td>
						<?php
							$tax = $subtotal * 0.06;
							echo "$".number_format($tax, 2);
						?>
					</td>
				</tr>
				<tr>
					<td>
						Total Sale
					</td>
					<td>
						<?php
							$grandTotal = $subtotal + $tax;
							echo "$".number_format($grandTotal, 2);
						?>
					</td>
				</tr>
			</table>
        </div>
		<!-- Like the header, the footer will be present on ALL pages -->
	<?php
		include('../pageFooterScripts/footer.php');
    ?>
    </body>
</html>