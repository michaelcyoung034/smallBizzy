<?php session_start();
    /****************************************************************************
    * File Name: invoice.php
    * Use-case: 
    * Author: Mike Young
    *
    * This php file allows sellers to generate invoices to sell items to users
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
	
	if(!isset($_SESSION['invoice']['CustomerID']))
	{
		reset_arrays();
	}
	
	switch ($_POST['instruction'])
	{
		case 'New Invoice':
		{
			$bid = $_POST['CustomerID'];
			$_SESSION['invoice']['CustomerID'] = $bid;
			$custIDQuery = "SELECT username FROM users WHERE (user_num = '$bid');";
			//run the statement
			$custIDResult = mysql_query($custIDQuery);
			
			if (!$custIDResult)
			{
				print mysql_error();
				exit;
			}
			else
			{
				//echo ("query run<br/>");
			}
			//if the user ID is returned and is not 0...
			//echo ("number of rows: ".mysql_num_rows($custIDResult)."<br/>");
			if (mysql_num_rows($custIDResult) == 1 && $bid != 0)
			{
				$row_array = mysql_fetch_array($custIDResult);
				//set the buyer ID
				$_SESSION['invoice']['CustomerID'] = $bid;
				//echo ("starting invoice...<br/>");
			}
			else if (mysql_num_rows($custIDResult) == 0)
			{
				unset($_SESSION['invoice']['CustomerID']);
				?>
					<script type="text/javascript" >
						alert("Account " + <?php echo $_POST['CustomerID'] ?> + " does not exist");
					</script>
				<?php
			}
		}
		break;
		case 'Add Item':
		{
			$_POST['mfg'] = strtoupper($_POST['mfg']);
			$_POST['part'] = strtoupper($_POST['part']);
			//make sure the part is available for sale
			//generate the select statement
			$mfg = $_POST['mfg'];
			$part = $_POST['part'];
			$sid = $_SESSION['user_num'];
			$query = "SELECT item_num, supplier, product, item_name, quantity, sell_price FROM inventory WHERE (supplier = '$mfg' AND product = '$part' AND seller_num = '$sid');";
			
			//run the statement
			$result = mysql_query($query);
			
			if (!$result)
			{
				print mysql_error();
				exit;
			}
			else
			{
				//echo ("query run");
			}
			//no returned results means the item is not available for sale
			if (mysql_num_rows($result) == 0)
			{
				?>
					<script type="text/javascript" >
						alert("Item <?php echo $_POST['mfg'] ?> <?php echo $_POST['part'] ?> does not exist in your inventory");
					</script>
				<?php
			}
			//otherwise, the item is available for sale
			else if (mysql_num_rows($result) == 1)
			{
				$row_array = mysql_fetch_array($result);
				//if it is for sale, make sure the seller has some on hand to sell
				$qty = $row_array['quantity'];
				if ($qty == 0)
				{
					?>
						<script type="text/javascript" >
							alert("you have 0 on-hand for item <?php echo $_POST['mfg'] ?> <?php echo $_POST['part'] ?>" );
						</script>
					<?php
				}
				else
				{
					//if the entered part is not already in the current session
					if (!in_array($row_array['item_num'],$_SESSION['invoice']['merchIDs']))
					{
						array_push($_SESSION['invoice']['merchIDs'],$row_array['item_num']);
						array_push($_SESSION['invoice']['mfgs'],$row_array['supplier']);
						array_push($_SESSION['invoice']['parts'],$row_array['product']);
						array_push($_SESSION['invoice']['descs'],$row_array['item_name']);
						array_push($_SESSION['invoice']['sells'],$row_array['sell_price']);
						//the default value for quantities is 1
						array_push($_SESSION['invoice']['qtys'],1);
						$_SESSION['invoice']['numOfItems']++;
					}
					else
					{
						?>
							<script type="text/javascript" >
								alert("Item already entered");
							</script>
						<?php
					}
				}
			}
		}
		break;
		case 'Cancel':
		{
			unset($_SESSION['invoice']['CustomerID']);
			reset_arrays();
		}
		break;
		case 'Delete Item':
		{
			//the index to delete is one less than the line number
			$lineToDelete = $_POST['line_number']-1;
			
			$_SESSION['invoice']['merchIDs'][$lineToDelete] = "VOID";
			$_SESSION['invoice']['mfgs'][$lineToDelete] = "VOID";
			$_SESSION['invoice']['parts'][$lineToDelete] = "VOID";
			$_SESSION['invoice']['descs'][$lineToDelete] = "VOID";
			$_SESSION['invoice']['sells'][$lineToDelete] = "VOID";
			$_SESSION['invoice']['qtys'][$lineToDelete] = "0";
			//$_SESSION['invoice']['numOfItems']--;
		}
		break;
		case 'Update QTY':
		{
			//var_dump($_POST['qtys']);
			for ($i = 0; $i < sizeof($_SESSION['invoice']['merchIDs']); $i++)
			{
				$item = $_SESSION['invoice']['merchIDs'][$i];
				$updateQtyQuery = "SELECT quantity FROM inventory WHERE item_num = '$item';";
			
				//run the statement
				$updateQtyResult = mysql_query($updateQtyQuery);
				
				if (!$updateQtyResult)
				{
					print mysql_error();
					exit;
				}
				else
				{
					//echo ("updateQtyQuery run<br/>");
				}
				$update_qty_row_array = mysql_fetch_array($updateQtyResult);
				//echo ("POST['qtys'][$i]: ".$_POST['qtys'][$i]."<br/>");
				//echo ("update_qty_row_array['quantity']: ".$update_qty_row_array['quantity']." run<br/>");
				if ($_POST['qtys'][$i] > $update_qty_row_array['quantity'])
				{
					?>
						<script type="text/javascript" >
							alert("Insufficient QOH for item <?php echo $_SESSION['invoice']['mfgs'][$i] ?> <?php echo $_SESSION['invoice']['parts'][$i] ?>. You only have <?php echo $update_qty_row_array['quantity']; ?> available");
						</script>
					<?php
				}
				else
				{
					$_SESSION['invoice']['qtys'][$i] = $_POST['qtys'][$i];
				}
			}
		}
		break;
		case 'Finalize':
		{
			if($_SESSION['invoice']['numOfItems'] != 0)
			{
				
				//insert the invoice header info
				$timestamp = date("Y-m-d H:i:s");
				//echo "timestamp: ".$timestamp."<br/>";
				$bid = $_SESSION['invoice']['CustomerID'];
				//echo "buyer id: ".$bid."<br/>";
				$sid = $_SESSION['user_num'];
				//echo "seller id: ".$sid."<br/>";
				$payment = $_POST['paymentType'];
				//echo "payment method: ".$payment."<br/>";
				$insertInvoiceQuery = "INSERT INTO invoices (order_timestamp, buyer_num, seller_num, status, payment_method) VALUES ('$timestamp', '$bid', '$sid', 'sold', '$payment');";
				//echo "insertInvoiceQuery: ".$insertInvoiceQuery."<br/>";
				$lastIDQuery = "SELECT LAST_INSERT_ID();";
				//execute insert query
				$insertInvoiceResult = mysql_query($insertInvoiceQuery);
				//execute invoice number query
				$lastIDResult = mysql_query($lastIDQuery);
				if (!$lastIDResult)
				{
					print mysql_error();
					exit;
				}
				else
				{
					//echo ("lastIDQuery run<br/>");
				}
				$last_id_row_array = mysql_fetch_array($lastIDResult);
				//always store the invoice's number so the line items can be attached.
				$inv = $last_id_row_array[0];
				//echo "invoice ID: ".$inv."<br/>";
				//insert the line items
				for ($i = 0; $i < sizeof($_SESSION['invoice']['merchIDs']); $i++)
				{
					$item = $_SESSION['invoice']['merchIDs'][$i];
					//echo "merch ID: ".$item."<br/>";
					$qty = $_SESSION['invoice']['qtys'][$i];
					//echo "qty: ".$qty."<br/>";
					$insertLineItemQuery = "INSERT INTO line_items (invoice_num, item_num, quantity) VALUES ('$inv', '$item', '$qty');";
					//echo "insert query: ".$insertLineItemQuery."<br/>";
					
					//decrease the quantity on hand and increase the sale volume of the item
					$sellQty = $_SESSION['invoice']['qtys'][$i];
					//echo "sell qty: ".$sellQty."<br/>";
					$invUpdateQuery = "UPDATE inventory SET quantity = quantity - '$sellQty', sales_volume = sales_volume + '$sellQty' WHERE item_num = '$item';";
					$invUpdateResult = mysql_query($invUpdateQuery);
					if (!$invUpdateResult)
					{
						print mysql_error();
						exit;
					}
					else
					{
						//echo ("update query run");
					}
					
					//echo $insertLineItemQuery."<br/>";
					$insertLineItemResult = mysql_query($insertLineItemQuery);
					if (!$insertLineItemResult)
					{
						print mysql_error();
						exit;
					}
					else
					{
						//echo ("insertLineItemResult run<br/>");
					}
				}
				//clear the session variables
				$_SESSION['invoice']['finalized'] = true;
				//reset_arrays();
				//unset($_SESSION['invoice']['CustomerID']);
			}
			else
			{
				?>
					<script type="text/javascript" >
						alert("invoice has 0 items");
					</script>
				<?php
			}
		}
		break;
		case 'New Invoice':
		{
			reset_arrays();
		}
		break;
	}
	//used to reset the arrays after an invoice has been finalized or if the session arrays are not properly instantiated or populated
	function reset_arrays()
	{
		$_SESSION['invoice']['merchIDs'] = array();
		$_SESSION['invoice']['mfgs'] = array();
		$_SESSION['invoice']['parts'] = array();
		$_SESSION['invoice']['descs'] = array();
		$_SESSION['invoice']['sells'] = array();
		$_SESSION['invoice']['qtys'] = array();
		$_SESSION['invoice']['numOfItems'] = 0;
		$_SESSION['invoice']['finalized'] = false;
	}
	//whenever the user makes a change to the data table, they expect the changes to take effect. this is where the data will be updated.
	function preserve_table_data()
	{
		$_SESSION['invoice']['qtys'] = $_POST['qtys'];
	}
	//The user must enter an account number and their clerk number for the invoice to be editable.
    //The user will enter item numbers and the items will be added to the list. if the database query returns one item, add that item to the list. Otherwise, take the user to the inventory selector screen to select the appropriate item. If zero items are found, give an error message of "no items found" and allow the user to keep entering item numbers.
	//create a series of lists to hold data about each item entered. Namely, $itemIDs, $manufacturers, $partNumbers, $descriptions, $quantities, and $unitPrices. these will all be arrays stored in $_SESSION for this page here.
		//If the user wishes to add an item, it will be pushed on to the end of the array.
		//If the user wishes to remove or update an item, they may do so by selecting the line number of the invoice, and removing or updating the items at any of the appropriate arrays at index [lineNumber - 1] (humans start counting from 1, but computers start counting from 0. This is the reason for the offset).
	//each new scanned item will add the entered item to the arrays with the database's item attributes a default quantity of 1.
    
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>Invoice</title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
		<!-- make sure the style sheet is accessible from all web pages -->
        <link href="../styleSheets/masterPageStyle.css" type="text/css" rel="Stylesheet" />
        <link href="../styleSheets/invoiceStyle.css" type="text/css" rel="Stylesheet" />
		<!-- ajax library -->
		<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/prototype/1.6.0.3/prototype.js"></script>
		<script type="text/javascript" src="../validationScripts/invoiceValidation.js"></script>
    </head>
    <body>
		<!-- the page header will be a separate file  -->
	<?php
		include('../pageHeaderScripts/header.php');
    ?>
        <div id="content">
			<!-- The user will be placed by default in the part number box -->
            <!-- There will be a table here displaying all of the currently scanned merchandise. the items will be stored in a list and the list will be appended when the input  -->
			<!-- This input will be for the next item -->
			<?php
				$startInvoice = isset($_SESSION['invoice']['CustomerID']);
				if (!$startInvoice)
				{
					?>
					<div id="custIDForm">
						<form name="invoiceHead" method="post" action="invoice.php" onsubmit="return custNumValidation();">
							<input name="CustomerID" id="CustomerID" />Customer ID
							<input type="submit" name="instruction" value="New Invoice"/>
						</form>
					</div><br/>
					<?php
				}
				if ($startInvoice)
				{
					//var_dump($_SESSION);
					?>
					<!-- invoice header info -->
					<table class="invoiceHeaderTable">
						<tr>
							<td>
								Customer ID<br/>
								<?php echo $_SESSION['invoice']['CustomerID'] ?>
							</td>
							<td>
								Buyer<br/>
								<?php
									$buyNum = $_SESSION['invoice']['CustomerID'];
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
									$sellNum = $_SESSION['user_num'];
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
						</tr>
					</table>
					<!-- line items -->
					<form name="lineItems" id="lineItems" method="post" action="invoice.php">
						<table class="lineItemsTable">
							<tr>
								<th>
									Line
								</th>
								<th>
									supplier
								</th>
								<th>
									Part Number
								</th>
								<th>
									Description
								</th>
								<th>
									Unit Price
								</th>
								<th>
									Quantity
								</th>
								<th>
									Extended Price
								</th>
							</tr>
							<?php
								$subtotal = 0.00;
								for($index=0; $index < sizeof($_SESSION['invoice']['merchIDs']); $index++)
								{
									$qty = $_SESSION['invoice']['qtys'][$index];
									$disabled = ($qty == "0");
									?>
									<tr>
										<td>
											<?php echo $index+1;?>
										</td>
										<td>
											<?php echo $_SESSION['invoice']['mfgs'][$index];?>
										</td>
										<td>
											<?php echo $_SESSION['invoice']['parts'][$index];?>
										</td>
										<td>
											<?php echo $_SESSION['invoice']['descs'][$index];?>
										</td>
										<td class="numberCell">
											$<?php echo $_SESSION['invoice']['sells'][$index];?>
										</td>
										<td class="numberCell">
											<input size="4" name="qtys[<?php echo $index ?>]" id="qtys[<?php echo $index ?>]" value="<?php echo $_SESSION['invoice']['qtys'][$index];?>" <?php if($disabled || $_SESSION['invoice']['finalized'])echo 'disabled' ?> />
										</td>
										<td class="numberCell">
											<div>
												<?php
													if (!$disabled)
													{
														$extPrx = floatval($_SESSION['invoice']['sells'][$index]) * $_SESSION['invoice']['qtys'][$index];
														$subtotal += $extPrx;
													}
													else
													{
														$extPrx = 0.00;
													}
													echo "$".number_format($extPrx, 2);
												?>
											</div>
										</td>
									</tr>
									<?php
								}
							?>
						</table>
						<!-- This table will be the total, tax calculation -->
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
								<td>
									Total Sale
								</td>
								<td class="numberCell">
									<?php
										$grandTotal = $subtotal + $tax;
										echo "$".number_format($grandTotal, 2);
									?>
								</td>
							</tr>
							<?php
								if(!$_SESSION['invoice']['finalized'])
								{
							?>
						</table>
						<table class="invoiceAction">
							<tr>
								<td>
									Enter next item:<br />
									<input name="mfg" id="mfg" maxlength="4" size="4"/><input name="part" id="part" maxlength="12" size="12"/><input name="instruction" value="Add Item" type="submit" onclick="return addItemValidation();"/><br/>
									<input name="line_number" id="line_number" size="4"/><input name="instruction" type="submit" value="Delete Item" onclick="return verifyDelete(<?php echo $_SESSION['invoice']['numOfItems']; ?>);" /><br/>
									<input name="instruction" value="Update QTY" type="submit" /><br/>
								</td>
							</tr>
							<tr>
								<td>
									<select name="paymentType" id="paymentType">
										<option value="none">Select a payment method</option>
										<?php
											//get the different payment types for the drop-down list of payment options
											$paymentQuery = "SELECT payment_type FROM payment_types;";
											//execute query
											$paymentResult = mysql_query($paymentQuery);
													
											if (!$paymentResult)
											{
												print mysql_error();
												exit;
											}
											else
											{
												//echo ("select query run");
											}
											//hold data for the details of each line item
											
											for($i = 0; $i < mysql_num_rows($paymentResult); $i++)
											{
												$payment_row_array=mysql_fetch_array($paymentResult);
										?>
											<option value="<?php echo $payment_row_array['payment_type'] ?>"><?php echo $payment_row_array['payment_type'] ?></option>
										<?php
											}
											mysql_close();
										?>
									</select>
								</td>
							</tr>
							<tr>
								<td>
									<input name="instruction" value="Finalize" type="submit" onclick="return verifyFinalize();"/><br/>
									<input name="instruction" value="Cancel" type="submit" onclick="return verifyCancel();"/>
							<?php
								}
								else
								{
							?>
							<input name="instruction" value="New Invoice" type="submit"/>
							<?php
								}
							?>
								</td>
							</tr>
						</table><br />
						<!-- add the invoice and line items to the database and clear the session variables -->
					</form>
				<?php
				}
			?>
        </div>
		<!-- Like the header, the footer will be present on ALL pages -->
	<?php
		include('../pageFooterScripts/footer.php');
    ?>
    </body>
</html>