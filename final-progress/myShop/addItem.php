<?php session_start();
    /****************************************************************************
    * File Name: addInventory.php
    * Use-case: add inventory
    * Author: Derek Wang
    *
	* This page allows sellers to add merchandise to sell. This page is designed for sellers intending to sell an individual item, or low-volume sellers.
    *****************************************************************************/
	//make sure the part is not already in the database.
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
	
	switch ($_POST['instruction'])
	{
		case 'Submit':
		{
		}
		break;
		case 'List Item':
		{
			//generate the statement to check for already existing records
			$name = mysql_real_escape_string(htmlspecialchars($_POST['item_name']));
			$desc = mysql_real_escape_string(htmlspecialchars($_POST['description']));
			$sid = mysql_real_escape_string(htmlspecialchars($_SESSION['user_num']));
			$query = "SELECT item_num FROM inventory WHERE (item_name = '$name' AND description = '$desc' AND seller_num = '$sid');";

			//(supplier = 'SELF' AND product = 'SINGLE_ITEM' AND item_name = '$name');";
			
			//run the statement
			$result = mysql_query($query);

			if (!$result)
			{
				print mysql_error();
				exit;
			}
			else
			{
				//echo ("query executed<br/>");
			}

			//no returned results means the record can be added

			//YO protective security measures necessary to prevent html, sql code injections!!!
			if (mysql_num_rows($result) == 0)
			{
				//$sid = $_SESSION['user_num'];
				//$name = $_POST['item_name'];
				$supplier = mysql_real_escape_string(htmlspecialchars($_POST['supplier']));
				$purchase = mysql_real_escape_string(htmlspecialchars($_POST['purchase']));
				//$desc = $_POST['description'];
				$qoh = mysql_real_escape_string(htmlspecialchars($_POST['qty']));
				$qmin = mysql_real_escape_string(htmlspecialchars($_POST['reorder_point']));
				$sell = mysql_real_escape_string(htmlspecialchars($_POST['sell']));
				
				//reassign the variable to insert the part
				$query = "INSERT INTO inventory (seller_num, item_name, supplier, purchase_price, description, quantity, max_quantity, sell_price) VALUES ('$sid', '$name', '$supplier', '$purchase', '$desc', '$qoh', '$qmin', '$sell');";
				
				//run the statement
				$result = mysql_query($query);
				
				if (!$result)
				{
					print mysql_error();
					exit;
				}
				else
				{
					//echo ("item added<br/>");
				}
				
				mysql_close();

				?>
					<script type="text/javascript">
						alert("<?php echo $name ?> successfully listed");
					</script>
				<?php
				}

			else {
				//echo "$name is already listed. Not added as unique item to inventory<br/>";
				mysql_close();
				?>
				<script type="text/javascript">
					alert("<?php echo $name ?> is already listed. Not added as unique item to inventory");
				</script>
			<?php
			}
		}
		break;
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>Add your listing</title>
		<!-- make sure the style sheet is accessible from all web pages -->
        <link href="../styleSheets/masterPageStyle.css" type="text/css" rel="Stylesheet" />
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
		<script type="text/javascript" src="../validationScripts/addInventoryValidation.js"></script>
		<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/prototype/1.6.0.3/prototype.js"></script>
		<script src="http://ajax.googleapis.com/ajax/libs/scriptaculous/1.8.2/scriptaculous.js" type="text/javascript"> </script>
    </head>
    <body>
	<?php
		include('../pageHeaderScripts/header.php');
    ?>
        <div id="content">
			<form name="additem" method="post" action="addItem.php" onsubmit="return validSingleItem();">
				<table id="inventoryAddTable">
					<tr>
						<td>
							List your item:
						</td>
					</tr>
					<tr>
						<td>
							</br>
						</td>
					</tr>
					<tr>
						<td>
							Item Name
						</td>
					</tr>
					<tr>
						<td>
							<input type="text" name="item_name" id="item_name" onblur = "checkItemName();"/>
							<span id = "msgbox"></span>
						</td>
					</tr>

<!-- debug FROM here -->
					<tr>
						<td>
							Supplier
						</td>
					</tr>
					<tr>
						<td>
							<input name="supplier" id="supplier" size="4" maxlength="4" />
						</td>
					</tr>

					<tr>
						<td>
							Purchase Price
						</td>
					</tr>
					<tr>
						<td>
							<input name="purchase" id="purchase" />
						</td>
					</tr>
<!-- debug TO here -->

					<tr>
						<td>
							Description
						</td>
					</tr>
					<tr>
						<td>
							<textarea name="description" id="description" cols="50" rows="10" > </textarea>
						</td>
					</tr>
					<tr>
						<td>
							Quantity
						</td>
					</tr>
					<tr>
						<td>
							<input id="qty" name="qty" value="" />
						</td>
					</tr>

<!-- debug FROM here -->					
					<tr>
						<td>
							Reorder point
						</td>
					</tr>
					<tr>
						<td>
							<input name="reorder_point" id="reorder_point" />
						</td>
					</tr>
<!-- debug TO here -->

					<tr>
						<td>
							Sell Price
						</td>
					</tr>
					<tr>
						<td>
							<input name="sell" id="sell" />
						</td>
					</tr>
				</table>
				</br>
				<input name="instruction" value="List Item" type="submit" /><br/>
			</form>
			</br>
			</br>
            <a href="manageMyShop.php">Back to inventory manager</a>
        </div>
		<!-- Like the header, the footer will be present on ALL pages, but it will be unchanged -->
	<?php
		include('../pageFooterScripts/footer.php');
    ?>
    </body>
</html>