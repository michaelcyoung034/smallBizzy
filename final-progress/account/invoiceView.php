<?php session_start();
    /****************************************************************************
    * File Name: invoiceView.php
    * Use-case: 
    * Author: Mike Young
    *
	* This page displays a particular invoice for a user.
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
	$invQuery = "SELECT invoices.invoice_num, invoices.order_timestamp, invoices.seller_num, invoices.buyer_num, users.user_num, users.username, invoices.grand_total, invoices.payment_method FROM users INNER JOIN invoices ON invoices.buyer_num = users.user_num WHERE invoice_num = '$invNum';";

	//execute query
	$invResult = mysql_query($invQuery);
			
	if (!$invResult)
	{
		print mysql_error();
		exit;
	}
	else
	{
		//echo ("select query run");
	}
	$invoice_row_array = mysql_fetch_array($invResult);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>Invoice Detail</title>
		<!-- make sure the style sheet is accessible from all web pages -->
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <link href="../styleSheets/masterPageStyle.css" type="text/css" rel="Stylesheet" />
        <link href="../styleSheets/invoiceStyle.css" type="text/css" rel="Stylesheet" />
    </head>
    <body>
		<!-- the page header will be a separate file  -->
	<?php
		include('../pageHeaderScripts/header.php');
    ?>
        <div id="content">
            <table class="invoiceHeaderTable">
				<tr>
					<td>
						Invoice ID<br/>
						<span style="font-weight:bold;"><?php echo $invoice_row_array['invoice_num'] ?></span>
					</td>
					<td>
						Customer ID<br/>
						<?php echo $invoice_row_array['user_num'] ?>
					</td>
					<td>
						Time Placed<br/>
						<?php echo $invoice_row_array['order_timestamp'] ?>
					</td>
					<td>
						Buyer<br/>
						<?php
							$buyNum = $invoice_row_array['buyer_num'];
							$buyerQuery = "SELECT username FROM users WHERE user_num = '$buyNum';";

							//execute query
							$buyerResult = mysql_query($buyerQuery);
									
							if (!$buyerResult)
							{
								print mysql_error();
								exit;
							}
							else
							{
								//echo ("select query run");
							}
							$buyer_row_array = mysql_fetch_array($buyerResult);
							echo $buyer_row_array['username'];
						?>
					</td>
					<td>
						Seller<br/>
						<?php
							$sellNum = $invoice_row_array['seller_num'];
							$sellerQuery = "SELECT username FROM users WHERE user_num = '$sellNum';";

							//execute query
							$sellerResult = mysql_query($sellerQuery);
									
							if (!$sellerResult)
							{
								print mysql_error();
								exit;
							}
							else
							{
								//echo ("select query run");
							}
							$seller_row_array = mysql_fetch_array($sellerResult);
							echo $seller_row_array['username'];
						?>
					</td>
					<td>
						Payment Method<br/>
						<?php echo $invoice_row_array['payment_method'] ?>
					</td>
				</tr>
			</table>
            <table class="lineItemsTable">
				<tr>
					<th>
						SUPPLIER
					</th>
					<th>
						PRODUCT
					</th>
					<th>
						NAME
					</th>
					<th>
						QUANTITY
					</th>
					<th>
						UNIT PRICE
					</th>
					<th>
						EXTENDED PRICE
					</th>
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
					<td class="numberCell">
						<?php echo $line_items_row_array['quantity'] ?>
					</td>
					<td class="numberCell">
						$<?php echo $line_items_row_array['sell_price'] ?>
					</td>
					<td class="numberCell">
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
			<table class="subtotalTable">
				<tr>
					<td>
						Sub-total
					</td>
					<td class="numberCell">
						<?php echo "$".number_format($subtotal, 2); ?>
					</td>
				</tr>
				<tr>
					<td>
						Sale Tax
					</td>
					<td class="numberCell">
						<?php
							$tax = $subtotal * 0.06;
							echo "$".number_format($tax, 2);
						?>
					</td>
				</tr>
				<tr>
					<td class="totalCell">
						Total Sale
					</td>
					<td class="totalCell">
						<?php
							$grandTotal = $subtotal + $tax;
							echo "$".number_format($grandTotal, 2);
						?>
					</td>
				</tr>
			</table>
            <a href="invoiceListView.php">back to invoice list</a>
        </div>
		<!-- Like the header, the footer will be present on ALL pages -->
		<?php
			include('../pageFooterScripts/footer.php');
		?>
    </body>
</html>