<?php session_start();

    /****************************************************************************
    * File Name: editInventory.php
    * Use-case: vendor update inventory
    * Author: Derek Wang
	*
	* editInventory.php provides functionality for a vendor to retrieve a virtual table of 
	*	product line values previously written to the database for which the vendor can edit
	* 	and then update the values form the virtual table to the database.
    *****************************************************************************/

	switch ($_POST['instruction'])
	{
		/*
		case 'Submit':
		{
			//$_SESSION['invUpdate']['mfg'] = strtoupper($_POST['mfg']);
			$_SESSION['updateMfg'] = strtoupper($_POST['mfg']);
		}
		break;
		*/
		
		case 'Update':
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
			
			for ($index = 0; $index < sizeof($_POST["item_nums"]); $index++)
			{
				$itemNum = $_POST["item_nums"][$index];
				$supplier = mysql_real_escape_string(htmlspecialchars($_POST['supplier'][$index]));
				$item_name = mysql_real_escape_string(htmlspecialchars($_POST['item_name'][$index]));
				$description = mysql_real_escape_string(htmlspecialchars($_POST['description'][$index]));
				$qoh = mysql_real_escape_string(htmlspecialchars($_POST['qohs'][$index]));
				$opt = mysql_real_escape_string(htmlspecialchars($_POST['opts'][$index]));
				$purch = mysql_real_escape_string(htmlspecialchars($_POST['purchase_price'][$index]));
				$sell = mysql_real_escape_string(htmlspecialchars($_POST['sell_price'][$index]));
				
				$query = "UPDATE inventory SET supplier='$supplier', item_name='$item_name', quantity='$qoh', description='$description', max_quantity='$opt', purchase_price='$purch', sell_price='$sell' WHERE (item_num='$itemNum');";//(supplier='$supplier' AND product='$product') //change on subquery of what is updated?
				
				//run the statement
				$result = mysql_query($query);
				
				if (!$result)
				{
					print mysql_error();
					exit;
				}
				else
				{
					//echo "query to run: ".$query;
					//echo ("update query run<br/>");
				}
			}
			mysql_close();
		}
		break;
	}
	
	//determine whether the mfg code has been given
	$updateInv = isset($_SESSION['updateMfg']);
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>Update Inventory</title>
		<!-- make sure the style sheet is accessible from all web pages -->
        <link href="../styleSheets/masterPageStyle.css" type="text/css" rel="Stylesheet" />
		<script type="text/javascript" src="../validationScripts/addInventoryValidation.js"></script>
    </head>
    <body>
	<?php
		//echo "post:<br/>";
		//var_dump($_POST);
		//echo "<br/>";
		//echo "items:<br/>";
		//echo sizeof($_POST["item_nums"]);
		include('../pageHeaderScripts/header.php');
    ?>
        <div id="content">
			<?php
				/*}
				else
				{*/
			
				//Get the records of all parts under the given manufacturer
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
				
				$mfgCode=strtoupper($_SESSION['code']);
				
				$uid = $_SESSION['user_num'];
				$query = "SELECT item_num, supplier, item_name, description, quantity, max_quantity, purchase_price, sell_price FROM inventory WHERE seller_num = $uid;"; //WHERE (supplier = '$mfgCode') AND quantity >= 1
				
				//execute query
				$result = mysql_query($query);
						
				if (!$result)
				{
					print mysql_error();
					exit;
				}
				else
				{
					echo ("select query run");
					echo mysql_num_rows($result);
				}
				
			?>
			<form name="updateParts" method="post" action="editInventory.php" onsubmit="return validInput(<?php echo mysql_num_rows($result); ?>);">
				<br/>
				<!--<input name="instruction" value="Reset" type="submit" />-->
				<table class="editInventory">
					<tr class="editInventory">
						<th class="editInventory">
							Supplier
						</th>
						<th class="editInventory">
							Item Name
						</th>
						<th class="editInventory">
							Description
						</th>
						<th class="editInventory">
							Quantity on Hand
						</th>
						<th class="editInventory">
							Reorder Point
						</th>
						<th class="editInventory">
							Purchase Price
						</th>
						<th class="editInventory">
							Sell Price
						</th>
					</tr>
					<?php
						for ($index = 0; $index < mysql_num_rows($result); $index++)
						{
							$row_array=mysql_fetch_array($result);
					?>
					<tr class="editInventory">
						<td class="editInventory">
							<input name="supplier[<?php echo $index?>]" size="4" value="<?php echo $row_array['supplier'];?>" readonly />
							<input name="item_nums[<?php echo $index?>]" value="<?php echo $row_array['item_num'];?>" hidden />
						</td>
						<td class="editInventory">
							<input name="item_name[<?php echo $index?>]" id="item_name[<?php echo $index?>]" value="<?php echo $row_array['item_name'];?>" readonly />
						</td>
						<td class="editInventory">
							<input name="description[<?php echo $index?>]" id="description[<?php echo $index?>]" value="<?php echo $row_array['description']?>" readonly />
						</td>
						<td class="editInventory">
							<input name="qohs[<?php echo $index ?>]" id="qohs[<?php echo $index ?>]" size="4" value="<?php echo $row_array['quantity']?>" />
						</td>
						<td class="editInventory">
							<input name="opts[<?php echo $index ?>]" id="opts[<?php echo $index ?>]" size="4" value="<?php echo $row_array['max_quantity']?>" />
						</td>
						<td class="editInventory">
							<input name="purchase_price[<?php echo $index ?>]" id="purchase_price[<?php echo $index ?>]" value="<?php echo $row_array['purchase_price']?>" />
						</td>
						<td class="editInventory">
							<input name="sell_price[<?php echo $index ?>]" id="sell_price[<?php echo $index ?>]" value="<?php echo $row_array['sell_price']?>" />
						</td>
					</tr>
					<?php
						}
						mysql_close();
					?>
				</table>
				<br/>
				<input name="instruction" type="submit" value="Update" />
				<input name="instruction" value="Reset" type="reset" /><br/>
			</form>
			<?php
				//}
			?>
			<br/>
            <a href="manageMyShop.php">Back to inventory manager</a>
        </div>
		<!-- Like the header, the footer will be present on ALL pages, but it will be unchanged -->
	</br>
	</br>
	</br>
	<div>
	<?php
		include('../pageFooterScripts/footer.php');
    ?>
    </div>
    </body>
</html>